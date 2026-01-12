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

  <p><b>Status:</b> <span id="status">{{ $order['status'] ?? '' }}</span></p>
  <p><b>Correlation ID:</b> <span style="font-family: monospace;" id="correlation">{{ $order['correlation_id'] ?? '' }}</span></p>

  <p><b>Last event:</b> <span id="last_event">{{ $order['last_event'] ?? '-' }}</span></p>
  <p><b>Last event at:</b> <span id="last_event_at">{{ $order['last_event_at'] ?? '-' }}</span></p>

  <div id="live" style="margin:12px 0; padding:10px; background:#eef6ff; border:1px solid #9bc5ff;">
    ⏳ Actualizando automáticamente...
  </div>

  <h3>Respuesta completa</h3>
  <pre id="raw" style="background:#f4f4f4; padding:12px;">{{ json_encode($order, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>

  <script>
    const orderId = {{ (int) ($order['id'] ?? 0) }};
    const pollUrl = "{{ route('orders.poll', ['id' => (int) ($order['id'] ?? 0)]) }}";

    function isFinal(status) {
      return status === 'CONFIRMED' || status === 'REJECTED';
    }

    async function poll() {
      try {
        const res = await fetch(pollUrl, { headers: { 'Accept': 'application/json' }});
        const json = await res.json();

        document.getElementById('status').textContent = json.status ?? '';
        document.getElementById('correlation').textContent = json.correlation_id ?? '';
        document.getElementById('last_event').textContent = json.last_event ?? '-';
        document.getElementById('last_event_at').textContent = json.last_event_at ?? '-';
        document.getElementById('raw').textContent = JSON.stringify(json, null, 2);

        if (isFinal(json.status)) {
          document.getElementById('live').textContent = '✅ Proceso finalizado.';
          clearInterval(window.__pollTimer);
        }
      } catch (e) {
        document.getElementById('live').textContent = '⚠️ No se pudo actualizar (reintentando...)';
      }
    }

    // solo si no es final, activamos polling
    const initialStatus = document.getElementById('status').textContent;
    if (!isFinal(initialStatus)) {
      window.__pollTimer = setInterval(poll, 1500);
      poll();
    } else {
      document.getElementById('live').textContent = '✅ Proceso finalizado.';
    }
  </script>
</body>
</html>
