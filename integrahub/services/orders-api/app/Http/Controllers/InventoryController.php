<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\InventoryService;

class InventoryController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => InventoryItem::query()->orderBy('sku')->get(),
        ]);
    }

    public function reserve(Request $request, InventoryService $inventory): JsonResponse
{
    $data = $request->validate([
        'items' => ['required', 'array', 'min:1'],
        'items.*.sku' => ['required', 'string', 'max:64'],
        'items.*.qty' => ['required', 'integer', 'min:1'],
    ]);

    try {
        $inventory->reserve($data['items']);
        return response()->json(['ok' => true]);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json(['error' => 'sku_not_found'], 404);
    } catch (\RuntimeException $e) {
        if (str_starts_with($e->getMessage(), 'inventory_insufficient:')) {
            [$tag, $sku, $available, $requested] = explode(':', $e->getMessage());
            return response()->json([
                'error' => 'inventory_insufficient',
                'sku' => $sku,
                'available' => (int) $available,
                'requested' => (int) $requested,
            ], 409);
        }
        return response()->json(['error' => 'reserve_failed'], 500);
    }
}

}
