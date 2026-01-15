<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>IntegraHub - Inventory</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800|space-mono:400,700" rel="stylesheet" />

  <style>
    :root{
      --bg:#f7f7f2;
      --bg-2:#eef2f7;
      --text:#0c111b;
      --muted:#5b6474;
      --panel: rgba(255,255,255,.7);
      --panel2: rgba(255,255,255,.9);
      --border: rgba(15,23,42,.12);
      --border2: rgba(15,23,42,.2);
      --shadow: 0 24px 60px rgba(15,23,42,.12);
      --ring: rgba(37,99,235,.25);
      --accent: #2563eb;
      --accent-2: #0ea5a4;
      --accent-3: #f59e0b;
      --radius: 20px;
      --radius-sm: 14px;
      --max: 1100px;
      --okBg: rgba(14,165,164,.16);
      --errBg: rgba(239,68,68,.16);
      --grid: rgba(15,23,42,.06);
    }

    @media (prefers-color-scheme: dark){
      :root{
        --bg:#0b0f14;
        --bg-2:#0f1520;
        --text: rgba(255,255,255,.92);
        --muted: rgba(255,255,255,.6);
        --panel: rgba(15,23,42,.6);
        --panel2: rgba(15,23,42,.8);
        --border: rgba(148,163,184,.2);
        --border2: rgba(148,163,184,.35);
        --shadow: 0 24px 60px rgba(0,0,0,.4);
        --ring: rgba(14,165,164,.35);
        --okBg: rgba(14,165,164,.2);
        --errBg: rgba(239,68,68,.2);
        --grid: rgba(148,163,184,.08);
      }
    }

    *{ box-sizing:border-box; }
    body{
      margin:0;
      font-family: "Manrope", "Segoe UI", ui-sans-serif, system-ui, -apple-system, Arial, sans-serif;
      background:
        radial-gradient(900px 480px at 8% -10%, rgba(37,99,235,.18), transparent 60%),
        radial-gradient(900px 520px at 92% 5%, rgba(14,165,164,.18), transparent 60%),
        linear-gradient(180deg, var(--bg) 0%, var(--bg-2) 100%);
      color: var(--text);
      -webkit-font-smoothing: antialiased;
    }
    body::before{
      content:"";
      position: fixed;
      inset: 0;
      background-image:
        linear-gradient(to right, var(--grid) 1px, transparent 1px),
        linear-gradient(to bottom, var(--grid) 1px, transparent 1px);
      background-size: 140px 140px;
      opacity: .22;
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
      width: 40px;
      height: 40px;
      border-radius: 14px;
      background: linear-gradient(135deg, rgba(37,99,235,.92), rgba(14,165,164,.92));
      box-shadow: 0 12px 24px rgba(37,99,235,.25);
      display:flex;
      align-items:center;
      justify-content:center;
      font-weight: 800;
      color: #f8fafc;
      font-size: 13px;
      letter-spacing: .08em;
    }
    .brandName{ font-weight: 800; letter-spacing: -.01em; }
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
      padding: 8px 14px;
      border-radius: 999px;
      font-weight: 700;
      font-size: 12px;
      transition: transform .15s ease, background .15s ease, border-color .15s ease;
      backdrop-filter: blur(10px);
    }
    .navLink:hover{ background: var(--panel2); border-color: var(--border2); transform: translateY(-1px); }
    .navLink.isActive{
      background: linear-gradient(135deg, rgba(37,99,235,.16), rgba(14,165,164,.16));
      border-color: rgba(37,99,235,.35);
      color: var(--text);
    }

    .pageTitle{ margin: 12px 0 16px; }
    .eyebrow{
      font-size: 11px;
      letter-spacing: .2em;
      text-transform: uppercase;
      color: var(--muted);
    }
    .title{ margin:6px 0 0; font-size: 30px; font-weight: 800; letter-spacing: -.02em; }
    .subtitle{ margin-top: 6px; color: var(--muted); font-size: 13px; line-height: 1.6; max-width: 64ch; }

    .card{
      border: 1px solid var(--border);
      background: var(--panel);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow:hidden;
      backdrop-filter: blur(16px);
    }
    .cardHeader{ padding: 16px 16px 0; }
    .cardTitle{ font-size: 13px; font-weight: 750; }
    .cardBody{ padding: 14px 16px 16px; }

    .notice{
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 12px 14px;
      margin-bottom: 14px;
      font-size: 13px;
      background: var(--panel);
      backdrop-filter: blur(10px);
    }
    .notice.ok{ background: var(--okBg); }
    .notice.err{ background: var(--errBg); }

    .row{ display:flex; gap: 10px; flex-wrap:wrap; align-items:center; }
    .input{
      border: 1px solid var(--border);
      background: var(--panel2);
      color: var(--text);
      padding: 10px 12px;
      border-radius: 12px;
      font-size: 13px;
    }
    .input:focus{ outline:none; box-shadow: 0 0 0 4px var(--ring); border-color: var(--border2); }

    .btn{
      border: 1px solid var(--border);
      background: var(--panel);
      padding: 10px 14px;
      border-radius: 12px;
      font-weight: 700;
      font-size: 13px;
      cursor:pointer;
      transition: transform .12s ease, background .12s ease, border-color .12s ease;
      backdrop-filter: blur(10px);
    }
    .btn:hover{ background: var(--panel2); border-color: var(--border2); transform: translateY(-1px); }
    .btn:focus{ outline:none; box-shadow: 0 0 0 4px var(--ring); }
    .btnPrimary{
      border-color: rgba(37,99,235,.35);
      background: linear-gradient(135deg, rgba(37,99,235,.18), rgba(14,165,164,.18));
    }

    .tableWrap{ overflow:auto; }
    table{
      width:100%;
      border-collapse: separate;
      border-spacing: 0;
      border: 1px solid var(--border);
      border-radius: 16px;
      overflow:hidden;
      background: var(--panel2);
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

    .mono{ font-family: "Space Mono", ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; }
    .empty{ color: var(--muted); padding: 14px; text-align:center; }

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
        <a class="navLink isActive" href="{{ route('inventory.index') }}">Inventory</a>
        <a class="navLink {{ request()->routeIs('analytics.*') ? 'isActive' : '' }}" href="{{ route('analytics.dashboard') }}">Analytics</a>
      </nav>
    </div>

    <div class="pageTitle reveal" style="--delay:.1s;">
      <div class="eyebrow">Inventory Hub</div>
      <h1 class="title">Inventory</h1>
      <div class="subtitle">Carga de CSV y visualizacion de items con feedback inmediato.</div>
    </div>

    @if (session('success'))
      <div class="notice ok reveal" style="--delay:.12s;">{{ session('success') }}</div>
    @endif

    @if (session('error'))
      <div class="notice err reveal" style="--delay:.12s;">{{ session('error') }}</div>
    @endif

    <div class="card reveal" style="margin-bottom:14px; --delay:.16s;">
      <div class="cardHeader">
        <div class="cardTitle">Subir CSV</div>
      </div>
      <div class="cardBody">
        <form method="POST" action="{{ route('inventory.upload') }}" enctype="multipart/form-data" class="row">
          @csrf
          <input class="input" type="file" name="file" required>
          <button class="btn btnPrimary" type="submit">Subir</button>
        </form>
        <div style="margin-top:10px; color: var(--muted); font-size:12px;">
          Formato esperado: SKU, Name, Stock, Price (segun tu backend).
        </div>
      </div>
    </div>

    <div class="card reveal" style="--delay:.2s;">
      <div class="cardHeader">
        <div class="cardTitle">Items</div>
      </div>
      <div class="cardBody tableWrap">
        <table>
          <thead>
            <tr>
              <th>SKU</th>
              <th>Nombre</th>
              <th>Stock</th>
              <th>Precio</th>
            </tr>
          </thead>
          <tbody>
            @forelse($items as $it)
              <tr>
                <td class="mono">{{ $it['sku'] ?? '' }}</td>
                <td>{{ $it['name'] ?? '' }}</td>
                <td>{{ $it['stock'] ?? '' }}</td>
                <td>{{ $it['price'] ?? '' }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="empty">Sin datos</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>
</body>
</html>
