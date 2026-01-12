<?php

namespace App\Http\Controllers;

use App\Services\OrdersApiClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Throwable;

class PortalOrdersController extends Controller
{
    public function index(): View
    {
        try {
            $payload = OrdersApiClient::make()->list();

            $orders = $payload['data'] ?? [];
            $meta   = $payload['meta'] ?? null;
            $links  = $payload['links'] ?? null;

        } catch (Throwable $e) {
            $orders = [];
            $meta = null;
            $links = null;
            session()->flash('error', 'No se pudo consultar Orders API: ' . $e->getMessage());
        }

        return view('orders.index', [
            'orders' => $orders,
            'meta'   => $meta,
            'links'  => $links,
        ]);
    }

    public function poll(int $id): JsonResponse
    {
        try {
            $order = OrdersApiClient::make()->get($id);
            return response()->json($order);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'No se pudo consultar el pedido',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function show(int $id): View
    {
        try {
            $order = OrdersApiClient::make()->get($id);
        } catch (Throwable $e) {
            abort(404, 'No se pudo obtener el pedido: ' . $e->getMessage());
        }

        return view('orders.show', [
            'order' => $order,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'customer_email' => ['required','email'],
            'total_amount' => ['nullable','numeric','min:0'],
            'items' => ['required','array','min:1'],
            'items.*.sku' => ['required','string'],
            'items.*.qty' => ['required','integer','min:1'],
            'items.*.price' => ['required','numeric','min:0'],
        ]);

        try {
            $created = OrdersApiClient::make()->create($data);
        } catch (Throwable $e) {
            return back()->withInput()->with('error', 'No se pudo crear el pedido: ' . $e->getMessage());
        }

        $id = $created['id'] ?? null;

        if ($id) {
            return redirect()->route('orders.show', $id)->with('success', "Pedido creado (ID {$id})");
        }

        return redirect()->route('orders.index')->with('success', 'Pedido creado.');
    }

    public function createDemo(): RedirectResponse
    {
        try {
            $created = OrdersApiClient::make()->createDemo();
        } catch (Throwable $e) {
            return back()->with('error', 'No se pudo crear el pedido demo: ' . $e->getMessage());
        }

        $id = $created['id'] ?? null;

        if ($id) {
            return redirect()
                ->route('orders.show', $id)
                ->with('success', "Pedido demo creado (ID {$id})");
        }

        return redirect()
            ->route('orders.index')
            ->with('success', 'Pedido demo creado.');
    }
}
