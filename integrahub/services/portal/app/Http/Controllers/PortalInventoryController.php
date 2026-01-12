<?php

namespace App\Http\Controllers;

use App\Services\InventoryApiClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

class PortalInventoryController extends Controller
{
    public function index(): View
    {
        $items = [];

        try {
            $payload = InventoryApiClient::make()->list();
            $items = $payload['data'] ?? [];
        } catch (Throwable $e) {
            session()->flash('error', 'No se pudo consultar Inventory API: ' . $e->getMessage());
        }

        return view('inventory.index', ['items' => $items]);
    }

    public function upload(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ]);

        $file = $request->file('file');

        $name = now()->format('Ymd_His') . '_inventory.csv';
        Storage::disk('local')->putFileAs('inbox/inventory', $file, $name);

        return back()->with('success', "Archivo recibido: {$name}. Se procesará automáticamente.");
    }
}
