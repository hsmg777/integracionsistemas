<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class InventoryApiClient
{
    public function __construct(private string $baseUrl, private int $timeoutSeconds = 2)
    {
    }

    public static function make(): self
    {
        $baseUrl = rtrim((string) env('ORDERS_API_BASE_URL', 'http://nginx/api'), '/');
        return new self($baseUrl);
    }

    public function list(): array
    {
        $res = Http::timeout($this->timeoutSeconds)->get($this->baseUrl . '/inventory');
        if (!$res->successful()) {
            throw new RuntimeException('inventory_api_http_' . $res->status());
        }
        return $res->json();
    }
}
