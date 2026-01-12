<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Pedido #{{ $order['id'] ?? '' }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body style="font-family: Arial; padding: 24px;">
  <a href="{{ route('orders.index') }}">← volver</a>

  <h1>Pedido #{{ $order['id'] ?? '' }}</h1>

  <p><b>Status:</b> <span id="order-status">{{ $order['status'] ?? '' }}</span></p>
  <p><b>Correlation ID:</b> <span style="font-family: monospace;" id="order-correlation">{{ $order['correlation_id'] ?? '' }}</span></p>

  <p><b>Last event:</b> <span id="order-last-event" style="font-family: monospace;">{{ $order['last_event'] ?? '-' }}</span></p>
  <p><b>Last event at:</b> <span id="order-last-event-at">{{ $order['last_event_at'] ?? '-' }}</span></p>

  <hr style="margin:18px 0;">

  <h3>Respuesta completa</h3>
  <pre id="order-json" style="background:#f4f4f4; padding:12px;">{{ json_encode($order, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>

  {{-- Polling cada 2s para ver cambios en vivo --}}
  @if (!empty($order['id']))
    <script>
      const pollUrl = @json(route('orders.poll', $order['id']));

      async function pollOrder() {
        try {
          const res = await fetch(pollUrl, { headers: { 'Accept': 'application/json' } });
          if (!res.ok) return;

          const data = await res.json();

          // Actualiza campos
          const statusEl = document.getElementById('order-status');
          const corrEl = document.getElementById('order-correlation');
          const lastEventEl = document.getElementById('order-last-event');
          const lastEventAtEl = document.getElementById('order-last-event-at');
          const jsonEl = document.getElementById('order-json');

          if (statusEl) statusEl.textContent = data.status ?? '';
          if (corrEl) corrEl.textContent = data.correlation_id ?? '';
          if (lastEventEl) lastEventEl.textContent = data.last_event ?? '-';
          if (lastEventAtEl) lastEventAtEl.textContent = data.last_event_at ?? '-';
          if (jsonEl) jsonEl.textContent = JSON.stringify(data, null, 2);

          const s = String(data.status || '').toUpperCase();
          if (['CONFIRMED', 'REJECTED'].includes(s)) {
            clearInterval(window.__pollInterval);
          }
        } catch (e) {
          // silencioso para demo
        }
      }

      window.__pollInterval = setInterval(pollOrder, 2000);
      pollOrder();
    </script>
  @endif
</body>
</html>
