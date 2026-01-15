<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>IntegraHub - Orders</title>
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
      --max: 1180px;

      --ok: rgba(20,184,166,.18);
      --warn: rgba(245,158,11,.18);
      --bad: rgba(248,113,113,.18);
      --neutral: rgba(255,255,255,.06);
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
        --ok: rgba(15,118,110,.14);
        --warn: rgba(180,83,9,.14);
        --bad: rgba(225,29,72,.12);
        --neutral: rgba(17,24,39,.04);
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

    .pageTitle{ margin: 12px 0 16px; }
    .title{ margin:0; font-size: 26px; font-weight: 750; letter-spacing: -.02em; }
    .subtitle{ margin-top: 6px; color: var(--muted); font-size: 13px; line-height: 1.6; max-width: 64ch; }

    .notice{
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 12px 14px;
      margin-bottom: 14px;
      font-size: 13px;
      background: rgba(255,255,255,.03);
    }
    .notice.ok{ background: rgba(20,184,166,.12); }
    .notice.err{ background: rgba(248,113,113,.12); }

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

    .row{ display:flex; gap: 10px; flex-wrap:wrap; align-items:flex-end; }
    .field{ min-width: 220px; flex: 1; }
    .label{ display:block; font-size: 12px; color: var(--muted); margin-bottom: 6px; }
    .input{
      width:100%;
      border: 1px solid var(--border);
      background: rgba(255,255,255,.04);
      color: var(--text);
      padding: 10px 12px;
      border-radius: 12px;
      font-size: 13px;
    }
    .input:focus{ outline:none; box-shadow: 0 0 0 4px var(--ring); border-color: var(--border2); }

    .btn{
      border: 1px solid var(--border);
      background: var(--panel);
      padding: 10px 12px;
      border-radius: 12px;
      font-weight: 650;
      font-size: 13px;
      cursor:pointer;
      transition: transform .12s ease, background .12s ease, border-color .12s ease;
      height: 40px;
    }
    .btn:hover{ background: var(--panel2); border-color: var(--border2); transform: translateY(-1px); }
    .btn:focus{ outline:none; box-shadow: 0 0 0 4px var(--ring); }

    .itemsHeader{
      margin-top: 10px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap: 10px;
    }
    .muted{ color: var(--muted); font-size: 12px; }

    .itemRow{
      display:grid;
      grid-template-columns: 1.2fr .6fr .8fr auto;
      gap: 10px;
      margin-top: 10px;
    }
    @media (max-width: 780px){
      .itemRow{ grid-template-columns: 1fr 1fr; }
      .itemRow .btn{ width: 100%; }
    }

    .btnDanger{
      background: rgba(248,113,113,.12);
      border-color: rgba(248,113,113,.3);
    }

    .tableWrap{ overflow:auto; }
    table{
      width:100%;
      border-collapse: separate;
      border-spacing: 0;
      border: 1px solid var(--border);
      border-radius: 14px;
      overflow:hidden;
      background: rgba(255,255,255,.03);
    }
    th,td{
      padding: 10px 10px;
      border-bottom: 1px solid var(--border);
      font-size: 13px;
      text-align:left;
      white-space: nowrap;
    }
    th{
      color: var(--muted);
      font-weight: 650;
      background: rgba(255,255,255,.03);
    }
    tr:last-child td{ border-bottom:none; }

    .mono{ font-family: "IBM Plex Mono", ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono","Courier New", monospace; }

    .badge{
      display:inline-flex;
      align-items:center;
      gap: 8px;
      padding: 6px 10px;
      border-radius: 999px;
      border: 1px solid var(--border);
      background: var(--neutral);
      font-weight: 700;
      font-size: 12px;
      letter-spacing: .02em;
    }
    .bOk{ background: var(--ok); }
    .bWarn{ background: var(--warn); }
    .bBad{ background: var(--bad); }

    .link{
      text-decoration: underline;
      text-underline-offset: 3px;
      color: inherit;
    }

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
        <a class="navLink isActive" href="{{ route('orders.index') }}">Orders</a>
        <a class="navLink {{ request()->routeIs('inventory.*') ? 'isActive' : '' }}" href="{{ route('inventory.index') }}">Inventory</a>
        <a class="navLink {{ request()->routeIs('analytics.*') ? 'isActive' : '' }}" href="{{ route('analytics.dashboard') }}">Analytics</a>
      </nav>
    </div>

    <div class="pageTitle reveal" style="--delay:.1s;">
      <h1 class="title">Orders</h1>
      <div class="subtitle">Creacion de pedidos y trazabilidad por eventos en un flujo claro.</div>
    </div>

    @if(isset($health))
      <div class="notice reveal" style="--delay:.12s;">
        <span style="color: var(--muted); font-size: 12px;">System status</span><br>
        @if(($health['ok'] ?? false) === true)
          <span style="font-weight: 750;">OK</span>
        @else
          <span style="font-weight: 750;">DOWN</span>
          <span class="muted">{{ $health['error'] ?? '' }}</span>
        @endif
      </div>
    @endif

    @if (session('success'))
      <div class="notice ok reveal" style="--delay:.14s;">{{ session('success') }}</div>
    @endif

    @if (session('error'))
      <div class="notice err reveal" style="--delay:.14s;">{{ session('error') }}</div>
    @endif

    <div class="card reveal" style="--delay:.18s;">
      <div class="cardHeader">
        <div class="cardTitle">Crear pedido</div>
        <div class="muted">Envia customer_email y items</div>
      </div>

      <div class="cardBody">
        <form method="POST" action="{{ route('orders.create') }}">
          @csrf

          <div class="row">
            <div class="field">
              <label class="label">Email cliente</label>
              <input class="input" name="customer_email" type="email" required
                     value="{{ old('customer_email', 'hayland@example.com') }}">
            </div>

            <div class="field" style="min-width: 220px; max-width: 320px;">
              <label class="label">Total (opcional)</label>
              <input class="input" name="total_amount" type="number" step="0.01"
                     value="{{ old('total_amount') }}"
                     placeholder="Si vacio, se calcula por items">
            </div>
          </div>

          <div class="itemsHeader">
            <div class="cardTitle" style="margin-top: 12px;">Items</div>
            <div class="muted">SKU, cantidad, precio</div>
          </div>

          <div id="items">
            <div class="itemRow">
              <div>
                <label class="label">SKU</label>
                <input class="input" name="items[0][sku]" placeholder="SKU" required value="SKU-1">
              </div>
              <div>
                <label class="label">Qty</label>
                <input class="input" name="items[0][qty]" placeholder="Qty" type="number" required value="1">
              </div>
              <div>
                <label class="label">Price</label>
                <input class="input" name="items[0][price]" placeholder="Price" type="number" step="0.01" required value="10.50">
              </div>
              <div style="display:flex; align-items:end;">
                <button class="btn btnDanger" type="button" onclick="removeRow(this)">Quitar</button>
              </div>
            </div>
          </div>

          <div class="row" style="margin-top: 12px;">
            <button class="btn" type="button" onclick="addItem()">Agregar item</button>
            <button class="btn" type="submit">Crear pedido</button>
          </div>
        </form>
      </div>
    </div>

    <div class="card reveal" style="--delay:.22s;">
      <div class="cardHeader">
        <div class="cardTitle">Pedidos</div>
        <div class="muted">Listado y ultimo evento</div>
      </div>

      <div class="cardBody tableWrap">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Correlation</th>
              <th>Status</th>
              <th>Last event</th>
              <th>Last event at</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @forelse(($orders ?? []) as $o)
              @php
                $status = strtoupper((string)($o['status'] ?? ''));
                $badgeClass =
                  $status === 'CONFIRMED' ? 'bOk' :
                  ($status === 'REJECTED' ? 'bBad' :
                  ($status === 'PROCESSING' ? 'bWarn' : ''));
              @endphp

              <tr>
                <td>{{ $o['id'] ?? '' }}</td>
                <td class="mono">{{ $o['correlation_id'] ?? '' }}</td>
                <td>
                  <span class="badge {{ $badgeClass }}">{{ $o['status'] ?? '' }}</span>
                </td>
                <td class="mono">{{ $o['last_event'] ?? '-' }}</td>
                <td>
                  {{ !empty($o['last_event_at']) ? \Carbon\Carbon::parse($o['last_event_at'])->format('Y-m-d H:i:s') : '-' }}
                </td>
                <td>
                  @if(!empty($o['id']))
                    <a class="link" href="{{ route('orders.show', $o['id']) }}">ver</a>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="muted" style="padding: 14px;">No hay pedidos todavia</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>

  <script>
    let idx = 1;

    function addItem() {
      const container = document.getElementById('items');
      const row = document.createElement('div');
      row.className = 'itemRow';
      row.innerHTML = `
        <div>
          <label class="label">SKU</label>
          <input class="input" name="items[${idx}][sku]" placeholder="SKU" required>
        </div>
        <div>
          <label class="label">Qty</label>
          <input class="input" name="items[${idx}][qty]" placeholder="Qty" type="number" required value="1">
        </div>
        <div>
          <label class="label">Price</label>
          <input class="input" name="items[${idx}][price]" placeholder="Price" type="number" step="0.01" required value="0.00">
        </div>
        <div style="display:flex; align-items:end;">
          <button class="btn btnDanger" type="button" onclick="removeRow(this)">Quitar</button>
        </div>
      `;
      container.appendChild(row);
      idx++;
    }

    function removeRow(btn) {
      const row = btn.closest('.itemRow');
      if (row) row.remove();
    }
  </script>
</body>
</html>
