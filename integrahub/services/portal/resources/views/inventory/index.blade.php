<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>IntegraHub - Inventory</title>
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
      --max: 1100px;
      --okBg: rgba(20,184,166,.12);
      --errBg: rgba(248,113,113,.12);
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
        --okBg: rgba(15,118,110,.12);
        --errBg: rgba(225,29,72,.12);
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

    .card{
      border: 1px solid var(--border);
      background: linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,.02));
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow:hidden;
    }
    .cardHeader{ padding: 16px 16px 0; }
    .cardTitle{ font-size: 13px; font-weight: 750; }
    .cardBody{ padding: 14px 16px 16px; }

    .notice{
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 12px 14px;
      margin-bottom: 14px;
      font-size: 13px;
      background: rgba(255,255,255,.03);
    }
    .notice.ok{ background: var(--okBg); }
    .notice.err{ background: var(--errBg); }

    .row{ display:flex; gap: 10px; flex-wrap:wrap; align-items:center; }
    .input{
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
    }
    .btn:hover{ background: var(--panel2); border-color: var(--border2); transform: translateY(-1px); }
    .btn:focus{ outline:none; box-shadow: 0 0 0 4px var(--ring); }

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

    .mono{ font-family: "IBM Plex Mono", ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; }
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
          <button class="btn" type="submit">Subir</button>
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
