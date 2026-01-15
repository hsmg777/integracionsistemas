<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>Pedido #{{ $order['id'] ?? '' }} - IntegraHub</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800|space-mono:400,700" rel="stylesheet" />

  <style>
    :root {
      --bg: #f7f7f2;
      --bg-2: #eef2f7;
      --text: #0c111b;
      --muted: #5b6474;
      --panel: rgba(255, 255, 255, .7);
      --panel2: rgba(255, 255, 255, .9);
      --border: rgba(15, 23, 42, .12);
      --border2: rgba(15, 23, 42, .2);
      --shadow: 0 24px 60px rgba(15, 23, 42, .12);
      --ring: rgba(37, 99, 235, .25);
      --accent: #2563eb;
      --accent-2: #0ea5a4;
      --accent-3: #f59e0b;
      --radius: 20px;
      --radius-sm: 14px;
      --max: 980px;

      --okBg: #10b981;
      --warnBg: #f59e0b;
      --badBg: #ef4444;
      --grid: rgba(15, 23, 42, .06);
    }

    @media (prefers-color-scheme: dark) {
      :root {
        --bg: #0b0f14;
        --bg-2: #0f1520;
        --text: rgba(255, 255, 255, .92);
        --muted: rgba(255, 255, 255, .6);
        --panel: rgba(15, 23, 42, .6);
        --panel2: rgba(15, 23, 42, .8);
        --border: rgba(148, 163, 184, .2);
        --border2: rgba(148, 163, 184, .35);
        --shadow: 0 24px 60px rgba(0, 0, 0, .4);
        --ring: rgba(14, 165, 164, .35);
        --okBg: #059669;
        --warnBg: #d97706;
        --badBg: #dc2626;
        --grid: rgba(148, 163, 184, .08);
      }
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: "Manrope", "Segoe UI", ui-sans-serif, system-ui, -apple-system, Arial, sans-serif;
      background:
        radial-gradient(900px 480px at 8% -10%, rgba(37, 99, 235, .18), transparent 60%),
        radial-gradient(900px 520px at 92% 5%, rgba(14, 165, 164, .18), transparent 60%),
        linear-gradient(180deg, var(--bg) 0%, var(--bg-2) 100%);
      color: var(--text);
      -webkit-font-smoothing: antialiased;
      line-height: 1.35;
    }

    body::before {
      content: "";
      position: fixed;
      inset: 0;
      background-image:
        linear-gradient(to right, var(--grid) 1px, transparent 1px),
        linear-gradient(to bottom, var(--grid) 1px, transparent 1px);
      background-size: 140px 140px;
      opacity: .22;
      pointer-events: none;
      mask-image: radial-gradient(circle at 20% 0%, rgba(0, 0, 0, .9), transparent 65%);
    }

    a {
      color: inherit;
      text-decoration: none;
    }

    .wrap {
      max-width: var(--max);
      margin: 0 auto;
      padding: 30px 18px 48px;
      position: relative;
      z-index: 1;
    }

    .topbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      margin-bottom: 22px;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .brandMark {
      width: 40px;
      height: 40px;
      border-radius: 14px;
      background: linear-gradient(135deg, rgba(37, 99, 235, .92), rgba(14, 165, 164, .92));
      box-shadow: 0 12px 24px rgba(37, 99, 235, .25);
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 800;
      color: #f8fafc;
      font-size: 13px;
      letter-spacing: .08em;
    }

    .brandName {
      font-weight: 800;
      letter-spacing: -.01em;
    }

    .brandSub {
      color: var(--muted);
      font-size: 12px;
    }

    .nav {
      display: flex;
      align-items: center;
      gap: 8px;
      flex-wrap: wrap;
    }

    .navLink {
      border: 1px solid var(--border);
      background: var(--panel);
      padding: 8px 14px;
      border-radius: 999px;
      font-weight: 700;
      font-size: 12px;
      transition: transform .15s ease, background .15s ease, border-color .15s ease;
      backdrop-filter: blur(10px);
    }

    .navLink:hover {
      background: var(--panel2);
      border-color: var(--border2);
      transform: translateY(-1px);
    }

    .navLink.isActive {
      background: linear-gradient(135deg, rgba(37, 99, 235, .16), rgba(14, 165, 164, .16));
      border-color: rgba(37, 99, 235, .35);
      color: var(--text);
    }

    .pageTitle {
      margin: 12px 0 16px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      flex-wrap: wrap;
    }

    .eyebrow {
      font-size: 11px;
      letter-spacing: .2em;
      text-transform: uppercase;
      color: var(--muted);
    }

    .title {
      margin: 0;
      font-size: 30px;
      font-weight: 800;
      letter-spacing: -.02em;
    }

    .subtitle {
      margin-top: 6px;
      color: var(--muted);
      font-size: 13px;
      line-height: 1.6;
    }

    .btn {
      border: 1px solid var(--border);
      background: var(--panel);
      padding: 10px 14px;
      border-radius: 12px;
      font-weight: 700;
      font-size: 13px;
      cursor: pointer;
      transition: transform .12s ease, background .12s ease, border-color .12s ease;
      display: inline-block;
      backdrop-filter: blur(10px);
    }

    .btn:hover {
      background: var(--panel2);
      border-color: var(--border2);
      transform: translateY(-1px);
    }

    .btn:focus {
      outline: none;
      box-shadow: 0 0 0 4px var(--ring);
    }

    .card {
      border: 1px solid var(--border);
      background: var(--panel);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow: hidden;
      margin-bottom: 14px;
      backdrop-filter: blur(16px);
    }

    .cardHeader {
      padding: 16px 16px 0;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
    }

    .cardTitle {
      font-size: 13px;
      font-weight: 750;
    }

    .cardBody {
      padding: 14px 16px 16px;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(12, 1fr);
      gap: 12px;
    }

    .col6 {
      grid-column: span 6;
    }

    .col12 {
      grid-column: span 12;
    }

    @media (max-width: 760px) {
      .col6 {
        grid-column: span 12;
      }
    }

    .label {
      color: var(--muted);
      font-size: 12px;
      margin-bottom: 6px;
    }

    .value {
      font-weight: 750;
      font-size: 14px;
    }

    .mono {
      font-family: "Space Mono", ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
    }

    .statusPill {
      display: inline-flex;
      align-items: center;
      padding: 7px 10px;
      border-radius: 999px;
      border: 1px solid var(--border);
      font-size: 11px;
      font-weight: 800;
      letter-spacing: .08em;
      text-transform: uppercase;
      background: var(--panel2);
    }

    .sOk {
      background: var(--okBg);
      color: #fff;
    }

    .sWarn {
      background: var(--warnBg);
      color: #fff;
    }

    .sBad {
      background: var(--badBg);
      color: #fff;
    }

    pre {
      margin: 0;
      background: var(--panel2);
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 12px;
      overflow: auto;
      font-size: 12px;
      line-height: 1.5;
    }

    .live {
      border: 1px solid var(--border);
      background: var(--panel);
      padding: 12px 14px;
      border-radius: 14px;
      font-size: 13px;
      color: var(--text);
      backdrop-filter: blur(10px);
    }

    .muted {
      color: var(--muted);
      font-size: 12px;
    }

    .reveal {
      opacity: 0;
      transform: translateY(10px);
      animation: rise .6s ease forwards;
      animation-delay: var(--delay, 0s);
    }

    @keyframes rise {
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .tableWrap {
      overflow: auto;
    }

    table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      overflow: hidden;
      background: var(--panel2);
    }

    th,
    td {
      padding: 10px 10px;
      border-bottom: 1px solid var(--border);
      font-size: 13px;
      text-align: left;
      white-space: nowrap;
    }

    th {
      color: var(--muted);
      font-weight: 650;
      background: rgba(255, 255, 255, .03);
    }

    tr:last-child td {
      border-bottom: none;
    }

    @media (max-width: 760px) {
      .topbar {
        flex-direction: column;
        align-items: flex-start;
      }
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
        <a class="navLink {{ request()->routeIs('orders.*') ? 'isActive' : '' }}"
          href="{{ route('orders.index') }}">Orders</a>
        <a class="navLink {{ request()->routeIs('inventory.*') ? 'isActive' : '' }}"
          href="{{ route('inventory.index') }}">Inventory</a>
        <a class="navLink {{ request()->routeIs('analytics.*') ? 'isActive' : '' }}"
          href="{{ route('analytics.dashboard') }}">Analytics</a>
      </nav>
    </div>

    <div class="pageTitle reveal" style="--delay:.1s;">
      <div>
        <div class="eyebrow">Order Detail</div>
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
              $st = strtoupper((string) ($order['status'] ?? ''));
              $cls = 'sWarn';
              if ($st === 'CONFIRMED')
                $cls = 'sOk';
              if ($st === 'REJECTED')
                $cls = 'sBad';
              if ($st === 'PENDING')
                $cls = 'sWarn';
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
        <div class="cardTitle">Detalle del Pedido</div>
      </div>
      <div class="cardBody">
        <div class="grid" style="margin-bottom: 24px;">
          <div class="col6">
            <div class="label">Customer Email</div>
            <div class="value">{{ $order['customer_email'] ?? '-' }}</div>
          </div>
          <div class="col6">
            <div class="label">Total Amount</div>
            <div class="value mono">${{ number_format((float) ($order['total_amount'] ?? 0), 2) }}</div>
          </div>
          <div class="col6">
            <div class="label">Created At</div>
            <div class="value mono">{{ $order['created_at'] ?? '-' }}</div>
          </div>
          <div class="col6">
            <div class="label">Updated At</div>
            <div class="value mono">{{ $order['updated_at'] ?? '-' }}</div>
          </div>
        </div>

        <h3 class="cardTitle" style="margin-bottom:12px;">Items</h3>
        <div class="tableWrap">
          <table>
            <thead>
              <tr>
                <th>Product ID</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              @forelse(($order['items'] ?? []) as $item)
                <tr>
                  <td class="mono">{{ $item['product_id'] ?? '-' }}</td>
                  <td class="mono">{{ $item['quantity'] ?? 0 }}</td>
                  <td class="mono">${{ number_format((float) ($item['price'] ?? 0), 2) }}</td>
                  <td class="mono">${{ number_format((float) (($item['quantity'] ?? 0) * ($item['price'] ?? 0)), 2) }}
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" style="text-align:center; color:var(--muted); padding: 20px;">No items found</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script>
    const orderId = {{ (int) ($order['id'] ?? 0) }};
    const pollUrl = "{{ route('orders.poll', ['id' => (int) ($order['id'] ?? 0)]) }}";

    function isFinal(status) {
      return status === 'CONFIRMED' || status === 'REJECTED';
    }

    function statusClass(status) {
      const s = (status ?? '').toUpperCase();
      if (s === 'CONFIRMED') return 'sOk';
      if (s === 'REJECTED') return 'sBad';
      if (s === 'PENDING') return 'sWarn';
      return 'sWarn';
    }

    async function poll() {
      try {
        const res = await fetch(pollUrl, { headers: { 'Accept': 'application/json' } });
        const json = await res.json();

        const stEl = document.getElementById('status');
        const status = (json.status ?? '').toString();

        stEl.textContent = status;
        stEl.classList.remove('sOk', 'sBad', 'sWarn');
        stEl.classList.add(statusClass(status));

        document.getElementById('correlation').textContent = json.correlation_id ?? '';
        document.getElementById('last_event').textContent = json.last_event ?? '-';
        document.getElementById('last_event_at').textContent = json.last_event_at ?? '-';
        document.getElementById('last_event_at').textContent = json.last_event_at ?? '-';
        // document.getElementById('raw').textContent = JSON.stringify(json, null, 2);

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