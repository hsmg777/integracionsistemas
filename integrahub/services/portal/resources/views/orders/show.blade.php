<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Pedido #{{ $order['id'] ?? '' }} · IntegraHub</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <style>
    :root{
      --bg:#0b0d12;
      --text: rgba(255,255,255,.92);
      --muted: rgba(255,255,255,.62);
      --panel: rgba(255,255,255,.06);
      --panel2: rgba(255,255,255,.08);
      --border: rgba(255,255,255,.10);
      --border2: rgba(255,255,255,.14);
      --shadow: 0 18px 40px rgba(0,0,0,.35);
      --ring: rgba(125,211,252,.35);
      --radius: 16px;
      --radius-sm: 12px;
      --max: 980px;

      --okBg: rgba(52,211,153,.10);
      --warnBg: rgba(250,204,21,.10);
      --badBg: rgba(251,113,133,.10);
    }

    @media (prefers-color-scheme: light){
      :root{
        --bg:#f7f8fb;
        --text: rgba(15,23,42,.92);
        --muted: rgba(15,23,42,.62);
        --panel: rgba(15,23,42,.04);
        --panel2: rgba(15,23,42,.06);
        --border: rgba(15,23,42,.10);
        --border2: rgba(15,23,42,.14);
        --shadow: 0 18px 40px rgba(15,23,42,.12);
        --ring: rgba(59,130,246,.22);

        --okBg: rgba(5,150,105,.10);
        --warnBg: rgba(202,138,4,.10);
        --badBg: rgba(225,29,72,.10);
      }
    }

    *{ box-sizing:border-box; }
    body{
      margin:0;
      font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
      background:
        radial-gradient(900px 480px at 20% 0%, rgba(125,211,252,.14), transparent 55%),
        radial-gradient(900px 480px at 85% 10%, rgba(167,139,250,.12), transparent 55%),
        var(--bg);
      color: var(--text);
      -webkit-font-smoothing: antialiased;
      line-height: 1.35;
    }

    a{ color:inherit; text-decoration:none; }
    .wrap{ max-width: var(--max); margin: 0 auto; padding: 26px 18px 40px; }

    .top{
      display:flex;
      align-items:flex-start;
      justify-content:space-between;
      gap: 12px;
      margin-bottom: 16px;
    }
    .title{ margin:0; font-size: 20px; font-weight: 750; letter-spacing: -.01em; }
    .subtitle{ margin-top: 6px; color: var(--muted); font-size: 13px; line-height: 1.5; }

    .btn{
      border: 1px solid var(--border);
      background: var(--panel);
      padding: 10px 12px;
      border-radius: 12px;
      font-weight: 650;
      font-size: 13px;
      cursor:pointer;
      transition: transform .12s ease, background .12s ease, border-color .12s ease;
      display:inline-block;
    }
    .btn:hover{ background: var(--panel2); border-color: var(--border2); transform: translateY(-1px); }
    .btn:focus{ outline:none; box-shadow: 0 0 0 4px var(--ring); }

    .card{
      border: 1px solid var(--border);
      background: linear-gradient(180deg, var(--panel), rgba(255,255,255,.03));
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow:hidden;
      margin-bottom: 14px;
    }
    .cardHeader{
      padding: 14px 14px 0;
      display:flex; align-items:center; justify-content:space-between; gap: 10px;
    }
    .cardTitle{ font-size: 13px; font-weight: 750; }
    .cardBody{ padding: 12px 14px 14px; }

    .grid{
      display:grid;
      grid-template-columns: repeat(12, 1fr);
      gap: 12px;
    }
    .col6{ grid-column: span 6; }
    .col12{ grid-column: span 12; }
    @media (max-width: 760px){
      .col6{ grid-column: span 12; }
    }

    .label{ color: var(--muted); font-size: 12px; margin-bottom: 6px; }
    .value{ font-weight: 750; font-size: 14px; }
    .mono{ font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono","Courier New", monospace; }

    .statusPill{
      display:inline-flex;
      align-items:center;
      padding: 7px 10px;
      border-radius: 999px;
      border: 1px solid var(--border);
      font-size: 12px;
      font-weight: 800;
      letter-spacing: .02em;
      background: rgba(255,255,255,.04);
    }
    .sOk{ background: var(--okBg); }
    .sWarn{ background: var(--warnBg); }
    .sBad{ background: var(--badBg); }

    pre{
      margin:0;
      background: rgba(255,255,255,.03);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 12px;
      overflow:auto;
      font-size: 12px;
      line-height: 1.5;
    }

    .live{
      border: 1px solid var(--border);
      background: rgba(125,211,252,.10);
      padding: 12px 14px;
      border-radius: 12px;
      font-size: 13px;
      color: var(--text);
    }

    .muted{ color: var(--muted); font-size: 12px; }
  </style>
</head>

<body>
  <div class="wrap">
    <div class="top">
      <div>
        <h1 class="title">Pedido #{{ $order['id'] ?? '' }}</h1>
        <div class="subtitle">Detalle y estado en tiempo real</div>
      </div>
      <div>
        <a class="btn" href="{{ route('orders.index') }}">Volver</a>
      </div>
    </div>

    <div class="card">
      <div class="cardHeader">
        <div class="cardTitle">Estado</div>
        <div class="muted">Actualización por polling</div>
      </div>
      <div class="cardBody">
        <div class="grid">
          <div class="col6">
            <div class="label">Status</div>
            @php
              $st = strtoupper((string)($order['status'] ?? ''));
              $cls = $st === 'CONFIRMED' ? 'sOk' : ($st === 'REJECTED' ? 'sBad' : 'sWarn');
            @endphp
            <div class="value">
              <span id="status" class="statusPill {{ $cls }}">{{ $order['status'] ?? '' }}</span>
            </div>
          </div>

          <div class="col6">
            <div class="label">Correlation ID</div>
            <div class="value mono" id="correlation">{{ $order['correlation_id'] ?? '' }}</div>
          </div>

          <div class="col6">
            <div class="label">Last event</div>
            <div class="value mono" id="last_event">{{ $order['last_event'] ?? '-' }}</div>
          </div>

          <div class="col6">
            <div class="label">Last event at</div>
            <div class="value mono" id="last_event_at">{{ $order['last_event_at'] ?? '-' }}</div>
          </div>

          <div class="col12">
            <div id="live" class="live">Actualizando automáticamente</div>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="cardHeader">
        <div class="cardTitle">Respuesta completa</div>
        <div class="muted">JSON del pedido</div>
      </div>
      <div class="cardBody">
        <pre id="raw">{{ json_encode($order, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
      </div>
    </div>
  </div>

  <script>
    const orderId = {{ (int) ($order['id'] ?? 0) }};
    const pollUrl = "{{ route('orders.poll', ['id' => (int) ($order['id'] ?? 0)]) }}";

    function isFinal(status) {
      return status === 'CONFIRMED' || status === 'REJECTED';
    }

    function statusClass(status){
      const s = (status ?? '').toUpperCase();
      if (s === 'CONFIRMED') return 'sOk';
      if (s === 'REJECTED') return 'sBad';
      return 'sWarn';
    }

    async function poll() {
      try {
        const res = await fetch(pollUrl, { headers: { 'Accept': 'application/json' }});
        const json = await res.json();

        const stEl = document.getElementById('status');
        const status = (json.status ?? '').toString();

        stEl.textContent = status;
        stEl.classList.remove('sOk','sBad','sWarn');
        stEl.classList.add(statusClass(status));

        document.getElementById('correlation').textContent = json.correlation_id ?? '';
        document.getElementById('last_event').textContent = json.last_event ?? '-';
        document.getElementById('last_event_at').textContent = json.last_event_at ?? '-';
        document.getElementById('raw').textContent = JSON.stringify(json, null, 2);

        if (isFinal(status)) {
          document.getElementById('live').textContent = 'Proceso finalizado';
          clearInterval(window.__pollTimer);
        } else {
          document.getElementById('live').textContent = 'Actualizando automáticamente';
        }
      } catch (e) {
        document.getElementById('live').textContent = 'No se pudo actualizar (reintentando)';
      }
    }

    const initialStatus = (document.getElementById('status').textContent ?? '').trim().toUpperCase();
    if (!isFinal(initialStatus)) {
      window.__pollTimer = setInterval(poll, 1500);
      poll();
    } else {
      document.getElementById('live').textContent = 'Proceso finalizado';
    }
  </script>
</body>
</html>
