<?php
namespace App\Repositories;

use App\Services\OrdersApiClient;

class AnalyticsRepository
{
    protected OrdersApiClient $client;

    public function __construct()
    {
        $this->client = OrdersApiClient::make();
    }

    public function daily(): array
    {
        return $this->client->getDailyAnalytics();
    }

    public function live(): array
    {
        return $this->client->getLiveAnalytics();
    }

    public function rebuild(?string $date = null): void
    {
        $this->client->buildDaily($date);
    }
}
