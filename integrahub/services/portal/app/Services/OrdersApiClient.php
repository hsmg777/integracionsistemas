<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class OrdersApiClient
{
    public function __construct(
        private readonly string $baseUrl,        // http://nginx/api
        private readonly string $clientId,       // demo-client
        private readonly string $clientSecret    // demo-secret
    ) {}

    public static function make(): self
    {
        return new self(
            rtrim((string) config('services.orders_api.base_url'), '/'),
            (string) config('services.orders_api.client_id'),
            (string) config('services.orders_api.client_secret')
        );
    }

    private function fetchJwt(): string
    {
        if ($this->clientId === '' || $this->clientSecret === '') {
            throw new RuntimeException('Faltan ORDERS_API_CLIENT_ID o ORDERS_API_CLIENT_SECRET en el Portal.');
        }

        $res = Http::acceptJson()
            ->timeout(10)
            ->post($this->baseUrl . '/auth/token', [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ]);

        $res->throw();

        $json = $res->json();
        $jwt = $json['access_token'] ?? null;

        if (!is_string($jwt) || $jwt === '') {
            throw new RuntimeException('No se recibió access_token válido desde /auth/token. Respuesta: ' . json_encode($json));
        }

        return $jwt;
    }

    private function jwt(): string
    {
        // expires_in = 3600, cacheamos un poco menos para evitar expiración justo en el borde
        return Cache::remember('orders_api.jwt', now()->addMinutes(55), fn () => $this->fetchJwt());
    }

    private function client()
    {
        return Http::withToken($this->jwt())
            ->acceptJson()
            ->timeout(10);
    }

    public function list(): array
    {
        $res = $this->client()->get($this->baseUrl . '/orders');
        $res->throw();
        return $res->json();
    }

    public function get(int $id): array
    {
        $res = $this->client()->get($this->baseUrl . "/orders/{$id}");
        $res->throw();
        return $res->json();
    }

    public function createDemo(): array
    {
        $items = [
            ["sku" => "SKU-1", "qty" => 1, "price" => 10.50],
        ];

        $totalAmount = 0.0;
        foreach ($items as $it) {
            $totalAmount += ((float) $it['qty']) * ((float) $it['price']);
        }

        $payload = [
            "customer_email" => "hayland@example.com",
            "items" => $items,
            "total_amount" => round($totalAmount, 2),
        ];

        $res = $this->client()->post($this->baseUrl . '/orders', $payload);
        $res->throw();

        return $res->json();
    }

    public function create(array $payload): array
    {
        // si no viene total_amount, lo calculamos
        if (!isset($payload['total_amount']) || $payload['total_amount'] === null || $payload['total_amount'] === '') {
            $total = 0.0;
            foreach (($payload['items'] ?? []) as $it) {
                $qty = (float) ($it['qty'] ?? 0);
                $price = (float) ($it['price'] ?? 0);
                $total += $qty * $price;
            }
            $payload['total_amount'] = round($total, 2);
        }

        $res = $this->client()->post($this->baseUrl . '/orders', $payload);
        $res->throw();
        return $res->json();
    }

}
