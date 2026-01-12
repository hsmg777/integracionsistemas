<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'correlation_id',
        'customer_email',
        'total_amount',
        'currency',
        'status',
        'items',
        'payload',
        'last_event',
        'last_event_at',
    ];

    protected $casts = [
        'items' => 'array',
        'payload' => 'array',
        'total_amount' => 'decimal:2',
        'last_event_at' => 'datetime',
    ];


}
