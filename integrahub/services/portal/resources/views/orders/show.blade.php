<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Pedido #{{ $order['id'] ?? '' }} - IntegraHub</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|ibm-plex-mono:400,500" rel="stylesheet" />

  <style>
    :root{
      --bg:#0c0b0a;
      --bg-2:#141110;
      --text: rgba(255,255,255,.94);
      --muted: rgba(255,255,255,.6);
      --panel: rgba(255,255,255,.06);
      --panel2: rgba(255,255,255,.1);
      --border: rgba(255,255,255,.12);
      --border2: rgba(255,255,255,.2);
      --shadow: 0 20px 50px rgba(0,0,0,.45);
      --ring: rgba(20,184,166,.35);
      --accent: #14b8a6;
      --accent-2: #f59e0b;
      --accent-3: #fb7185;
      --radius: 18px;
      --radius-sm: 12px;
      --max: 980px;

      --okBg: rgba(20,184,166,.18);
      --warnBg: rgba(245,158,11,.18);
      --badBg: rgba(248,113,113,.18);
      --grid: rgba(255,255,255,.05);
    }

    @media (prefers-color-scheme: light){
      :root{
        --bg:#f6f2ec;
        --bg-2:#fffdf9;
        --text: rgba(17,24,39,.92);
        --muted: rgba(17,24,39,.62);
        --panel: rgba(17,24,39,.04);
        --panel2: rgba(17,24,39,.06);
        --border: rgba(17,24,39,.12);
        --border2: rgba(17,24,39,.2);
        --shadow: 0 16px 40px rgba(17,24,39,.12);
        --ring: rgba(15,118,110,.28);
        --accent: #0f766e;
        --accent-2: #b45309;
        --accent-3: #e11d48;
        --okBg: rgba(15,118,110,.14);
        --warnBg: rgba(180,83,9,.14);
        --badBg: rgba(225,29,72,.12);
        --grid: rgba(17,24,39,.06);
      }
    }

    *{ box-sizing:border-box; }
    body{
      margin:0;
      font-family: "Space Grotesk", "IBM Plex Sans", ui-sans-serif, system-ui, -apple-system, "Segoe UI", Arial, sans-serif;
      background:
        radial-gradient(1100px 600px at 10% -10%, rgba(20,184,166,.22), transparent 60%),
        radial-gradient(1000px 520px at 90% 0%, rgba(245,158,11,.2), transparent 60%),
        radial-gradient(900px 520px at 50% 100%, rgba(248,113,113,.12), transparent 60%),
        var(--bg);
      color: var(--text);
      -webkit-font-smoothing: antialiased;
      line-height: 1.35;
    }
    body::before{
      content:"";
      position: fixed;
      inset: 0;
      background-image:
        linear-gradient(to right, var(--grid) 1px, transparent 1px),
        linear-gradient(to bottom, var(--grid) 1px, transparent 1px);
      background-size: 120px 120px;
      opacity: .18;
      pointer-events: none;
      mask-image: radial-gradient(circle at 20% 0%, rgba(0,0,0,.9), transparent 65%);
    }

    a{ color:inherit; text-decoration:none; }
    .wrap{ max-width: var(--max); margin: 0 auto; padding: 30px 18px 48px; position: relative; z-index: 1; }

    .topbar{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap: 16px;
      margin-bottom: 22px;
    }
    .brand{
      display:flex;
      align-items:center;
      gap: 12px;
    }
    .brandMark{
      width: 38px;
      height: 38px;
      border-radius: 12px;
      background: linear-gradient(135deg, rgba(20,184,166,.9), rgba(245,158,11,.9));
      box-shadow: 0 10px 20px rgba(20,184,166,.25);
      display:flex;
      align-items:center;
      justify-content:center;
      font-weight: 700;
      color: #0b0a09;
      font-size: 14px;
    }
    .brandName{ font-weight: 700; letter-spacing: -.01em; }
    .brandSub{ color: var(--muted); font-size: 12px; }

    .nav{
      display:flex;
      align-items:center;
      gap: 8px;
      flex-wrap: wrap;
    }
    .navLink{
      border: 1px solid var(--border);
      background: var(--panel);
      padding: 8px 12px;
      border-radius: 999px;
      font-weight: 600;
      font-size: 12px;
      transition: transform .15s ease, background .15s ease, border-color .15s ease;
    }
    .navLink:hover{ background: var(--panel2); border-color: var(--border2); transform: translateY(-1px); }
    .navLink.isActive{
      background: linear-gradient(135deg, rgba(20,184,166,.22), rgba(245,158,11,.22));
      border-color: rgba(20,184,166,.4);
      color: var(--text);
    }

    .pageTitle{ margin: 12px 0 16px; display:flex; align-items:center; justify-content:space-between; gap: 10px; flex-wrap:wrap; }
    .title{ margin:0; font-size: 26px; font-weight: 750; letter-spacing: -.02em; }
    .subtitle{ margin-top: 6px; color: var(--muted); font-size: 13px; line-height: 1.6; }

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
      background: linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,.02));
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow:hidden;
      margin-bottom: 14px;
    }
    .cardHeader{
      padding: 16px 16px 0;
      display:flex; align-items:center; justify-content:space-between; gap: 10px;
    }
    .cardTitle{ font-size: 13px; font-weight: 750; }
    .cardBody{ padding: 14px 16px 16px; }

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
    .mono{ font-family: "IBM Plex Mono", ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono","Courier New", monospace; }

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
      background: linear-gradient(120deg, rgba(20,184,166,.12), rgba(245,158,11,.12));
      padding: 12px 14px;
      border-radius: 12px;
      font-size: 13px;
      color: var(--text);
    }

    .muted{ color: var(--muted); font-size: 12px; }

    .reveal{
      opacity: 0;
      transform: translateY(10px);
      animation: rise .6s ease forwards;
      animation-delay: var(--delay, 0s);
    }
    @keyframes rise{
      to{ opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 760px){
      .topbar{ flex-direction: column; align-items:flex-start; }
    }
  </style>
</head>

<body>
  <div class="wrap">
    <div class="topbar reveal" style="--delay:.05s;">
      <div class="brand">
        <div class="brandMark">IH</div>
        <div>
          <div class="brandName">IntegraHub</div>
          <div class="brandSub">Operations portal</div>
        </div>
      </div>
      <nav class="nav">
        <a class="navLink {{ request()->routeIs('orders.*') ? 'isActive' : '' }}" href="{{ route('orders.index') }}">Orders</a>
        <a class="navLink {{ request()->routeIs('inventory.*') ? 'isActive' : '' }}" href="{{ route('inventory.index') }}">Inventory</a>
        <a class="navLink {{ request()->routeIs('analytics.*') ? 'isActive' : '' }}" href="{{ route('analytics.dashboard') }}">Analytics</a>
      </nav>
    </div>

    <div class="pageTitle reveal" style="--delay:.1s;">
      <div>
        <h1 class="title">Pedido #{{ $order['id'] ?? '' }}</h1>
        <div class="subtitle">Detalle y estado en tiempo real</div>
      </div>
      <a class="btn" href="{{ route('orders.index') }}">Volver</a>
    </div>

    <div class="card reveal" style="--delay:.14s;">
      <div class="cardHeader">
        <div class="cardTitle">Estado</div>
        <div class="muted">Actualizacion por polling</div>
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
            <div id="live" class="live">Actualizando automaticamente</div>
          </div>
        </div>
      </div>
    </div>

    <div class="card reveal" style="--delay:.18s;">
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
          document.getElementById('live').textContent = 'Actualizando automaticamente';
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
