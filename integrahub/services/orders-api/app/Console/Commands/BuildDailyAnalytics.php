<?php

namespace App\Console\Commands;

use App\Models\AnalyticsDaily;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BuildDailyAnalytics extends Command
{
    protected $signature = 'analytics:build-daily {--date=}';
    protected $description = 'ETL diario: construye analytics_daily desde orders';

    public function handle(): int
    {
        $date = $this->option('date')
            ? now()->parse($this->option('date'))->toDateString()
            : now()->toDateString();

        $this->info("📊 Building analytics for {$date}");

        // EXTRACT
        $orders = Order::query()
            ->whereDate('created_at', $date)
            ->get();

        if ($orders->isEmpty()) {
            $this->warn('No orders found');
            return self::SUCCESS;
        }

        // TRANSFORM
        $ordersTotal = $orders->count();
        $confirmed = $orders->where('status', 'CONFIRMED');
        $rejected  = $orders->where('status', 'REJECTED');

        $revenue = $confirmed->sum('total_amount');

        $itemsSold = $confirmed->sum(function ($order) {
            return collect($order->items ?? [])
                ->sum(fn ($i) => (int) ($i['qty'] ?? 0));
        });

        // LOAD (UPSERT)
        AnalyticsDaily::updateOrCreate(
            ['date' => $date],
            [
                'orders_total'     => $ordersTotal,
                'orders_confirmed' => $confirmed->count(),
                'orders_rejected'  => $rejected->count(),
                'revenue_total'    => $revenue,
                'items_sold'       => $itemsSold,
            ]
        );

        $this->info('✅ Analytics generated');

        return self::SUCCESS;
    }
}
