<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsDaily extends Model
{
    protected $table = 'analytics_daily';

    protected $fillable = [
        'date',
        'orders_total',
        'orders_confirmed',
        'orders_rejected',
        'revenue_total',
        'items_sold',
    ];

    protected $casts = [
        'date' => 'date',
        'revenue_total' => 'decimal:2',
    ];
}
