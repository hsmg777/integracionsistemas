<?php

namespace App\Services;

use App\Models\InventoryItem;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class InventoryService
{
    /**
     * @param array<int, array{sku:string, qty:int|string}> $items
     */
    public function reserve(array $items): void
    {
        $bySku = collect($items)
            ->map(function ($it) {
                $sku = strtoupper(trim((string)($it['sku'] ?? '')));
                $qty = (int)($it['qty'] ?? 0);
                return ['sku' => $sku, 'qty' => $qty];
            })
            ->filter(fn ($it) => $it['sku'] !== '' && $it['qty'] > 0)
            ->groupBy('sku')
            ->map(fn ($group) => (int)$group->sum('qty'))
            ->all();

        if (empty($bySku)) {
            throw new RuntimeException('order_items_empty');
        }

        DB::transaction(function () use ($bySku) {

            foreach ($bySku as $sku => $qty) {

                $item = InventoryItem::query()
                    ->where('sku', $sku)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($item->stock < $qty) {
                    throw new RuntimeException("inventory_insufficient:{$sku}:{$item->stock}:{$qty}");
                }

                $affected = InventoryItem::query()
                    ->where('sku', $sku)
                    ->where('stock', '>=', $qty)
                    ->decrement('stock', $qty);

                if ($affected !== 1) {
                    throw new RuntimeException("inventory_not_updated:{$sku}");
                }
            }

        }, 3);
    }
}
