<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class OrdersApiClient
{
    private const JWT_CACHE_KEY = 'orders_api.jwt';
    private const TIMEOUT_SECONDS = 10;
    private const JWT_TTL_MINUTES = 55;

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

    private function url(string $path): string
    {
        return $this->baseUrl . '/' . ltrim($path, '/');
    }

    private function fetchJwt(): string
    {
        if ($this->clientId === '' || $this->clientSecret === '') {
            throw new RuntimeException('Faltan ORDERS_API_CLIENT_ID o ORDERS_API_CLIENT_SECRET en el Portal.');
        }

        $res = Http::acceptJson()
            ->timeout(self::TIMEOUT_SECONDS)
            ->post($this->url('auth/token'), [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ]);

        $res->throw();

        $json = $res->json();
        $jwt = $json['access_token'] ?? null;

        if (!is_string($jwt) || $jwt === '') {
            throw new RuntimeException(
                'No se recibió access_token válido desde /auth/token. Respuesta: ' . json_encode($json)
            );
        }

        return $jwt;
    }

    private function jwt(): string
    {
        // expires_in = 3600, cacheamos un poco menos para evitar expiración justo en el borde
        return Cache::remember(
            self::JWT_CACHE_KEY,
            now()->addMinutes(self::JWT_TTL_MINUTES),
            fn () => $this->fetchJwt()
        );
    }

    private function client(): PendingRequest
    {
        return Http::withToken($this->jwt())
            ->acceptJson()
            ->timeout(self::TIMEOUT_SECONDS);
    }

    private function computeTotalAmount(array $items): float
    {
        $total = 0.0;

        foreach ($items as $it) {
            $qty = (float) ($it['qty'] ?? 0);
            $price = (float) ($it['price'] ?? 0);
            $total += $qty * $price;
        }

        return round($total, 2);
    }

    public function list(): array
    {
        $res = $this->client()->get($this->url('orders'));
        $res->throw();

        return $res->json();
    }

    public function get(int $id): array
    {
        $res = $this->client()->get($this->url("orders/{$id}"));
        $res->throw();

        return $res->json();
    }

    public function createDemo(): array
    {
        $items = [
            ['sku' => 'SKU-1', 'qty' => 1, 'price' => 10.50],
        ];

        $payload = [
            'customer_email' => 'hayland@example.com',
            'items' => $items,
            'total_amount' => $this->computeTotalAmount($items),
        ];

        $res = $this->client()->post($this->url('orders'), $payload);
        $res->throw();

        return $res->json();
    }

    public function create(array $payload): array
    {
        // si no viene total_amount, lo calculamos
        if (!isset($payload['total_amount']) || $payload['total_amount'] === null || $payload['total_amount'] === '') {
            $payload['total_amount'] = $this->computeTotalAmount((array) ($payload['items'] ?? []));
        }

        $res = $this->client()->post($this->url('orders'), $payload);
        $res->throw();

        return $res->json();
    }

    public function getDailyAnalytics(): array
    {
        $res = $this->client()->get($this->url('analytics/daily'));

        if (!$res->successful()) {
            throw new RuntimeException('orders_api_analytics_daily_failed');
        }

        $json = $res->json();

        return $json['data'] ?? [];
    }

    public function getLiveAnalytics(): array
    {
        $res = $this->client()->get($this->url('analytics/live'));

        if (!$res->successful()) {
            // Live es opcional si no está implementado aún
            return [
                'enabled' => false,
                'message' => 'Live analytics no disponible',
            ];
        }

        return $res->json();
    }

    public function buildDaily(?string $date = null): void
    {
        $payload = $date ? ['date' => $date] : [];

        $res = $this->client()->post($this->url('analytics/build'), $payload);

        if (!$res->successful()) {
            throw new RuntimeException('orders_api_analytics_build_failed');
        }
    }
}
