<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    protected $fillable = ['sku', 'name', 'stock', 'price']; // + 'reserved' si lo agregas

    protected $casts = [
        'stock' => 'integer',
        'price' => 'decimal:2',
        // 'reserved' => 'integer',
    ];

    public function setSkuAttribute($value): void
    {
        $this->attributes['sku'] = strtoupper(trim((string)$value));
    }
}

