<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>IntegraHub - Analytics</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
      --max: 1200px;
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
        --grid: rgba(148, 163, 184, .08);
      }
    }

    * {
      box-sizing: border-box;
    }

    html,
    body {
      height: 100%;
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
    }

    .eyebrow {
      font-size: 11px;
      letter-spacing: .2em;
      text-transform: uppercase;
      color: var(--muted);
    }

    .title {
      margin: 6px 0 0;
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
      transition: transform .12s ease, background .12s ease, border-color .12s ease;
      cursor: pointer;
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

    .btnPrimary {
      border: none;
      background: linear-gradient(135deg, #2563eb, #0ea5a4);
      color: #ffffff;
      box-shadow: 0 4px 12px rgba(37, 99, 235, .3);
    }

    .btnPrimary:hover {
      box-shadow: 0 6px 16px rgba(37, 99, 235, .4);
    }

    .notice {
      border: 1px solid var(--border);
      background: var(--panel);
      padding: 12px 14px;
      border-radius: 14px;
      margin-bottom: 16px;
      color: var(--text);
      font-size: 13px;
      backdrop-filter: blur(10px);
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(12, 1fr);
      gap: 14px;
      margin-bottom: 14px;
    }

    .card {
      border: 1px solid var(--border);
      background: var(--panel);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      backdrop-filter: blur(16px);
    }

    .kpiCard {
      padding: 16px;
      grid-column: span 3;
    }

    @media (max-width: 980px) {
      .kpiCard {
        grid-column: span 6;
      }
    }

    @media (max-width: 520px) {
      .kpiCard {
        grid-column: span 12;
      }
    }

    .kpiLabel {
      color: var(--muted);
      font-size: 12px;
    }

    .kpiValue {
      margin-top: 8px;
      font-size: 28px;
      font-weight: 800;
      letter-spacing: -.02em;
    }

    .wide {
      grid-column: span 8;
    }

    .side {
      grid-column: span 4;
    }

    @media (max-width: 980px) {

      .wide,
      .side {
        grid-column: span 12;
      }
    }

    .cardHeader {
      padding: 16px 16px 0;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
    }

    .cardTitle {
      font-weight: 750;
      font-size: 13px;
      letter-spacing: .2px;
    }

    .cardBody {
      padding: 14px 16px 16px;
    }

    .table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      overflow: hidden;
      border: 1px solid var(--border);
      border-radius: 16px;
      background: var(--panel2);
    }

    .table th,
    .table td {
      padding: 10px 10px;
      font-size: 13px;
      border-bottom: 1px solid var(--border);
      text-align: left;
      white-space: nowrap;
    }

    .table th {
      color: var(--muted);
      font-weight: 650;
      background: rgba(255, 255, 255, .03);
    }

    .table tr:last-child td {
      border-bottom: none;
    }

    .muted {
      color: var(--muted);
      font-size: 12px;
    }

    .row {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      align-items: center;
    }

    .input {
      border: 1px solid var(--border);
      background: var(--panel2);
      color: var(--text);
      padding: 10px 12px;
      border-radius: 12px;
      font-size: 13px;
    }

    .input:focus {
      outline: none;
      box-shadow: 0 0 0 4px var(--ring);
      border-color: var(--border2);
    }

    .pill {
      border: 1px solid var(--border);
      background: rgba(255, 255, 255, .04);
      padding: 6px 10px;
      border-radius: 999px;
      font-size: 12px;
      color: var(--muted);
    }

    .rightActions {
      display: flex;
      gap: 10px;
      align-items: center;
      flex-wrap: wrap;
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

        <a class="navLink isActive" href="{{ route('analytics.dashboard') }}">Analytics</a>
      </nav>
    </div>

    <div class="pageTitle reveal" style="--delay:.1s;">
      <div class="eyebrow">Analytics Hub</div>
      <h1 class="title">Analytics</h1>
      <div class="subtitle">Orders, Inventory, Revenue y ETL en un solo panel.</div>
    </div>

    @if(session('success'))
      <div class="notice reveal" style="--delay:.12s;">{{ session('success') }}</div>
    @endif

    <div class="grid">
      <div class="card kpiCard reveal" style="--delay:.16s;">
        <div class="kpiLabel">Orders confirmados</div>
        <div class="kpiValue">{{ $live['orders_confirmed'] ?? 0 }}</div>
      </div>

      <div class="card kpiCard reveal" style="--delay:.18s;">
        <div class="kpiLabel">Revenue total</div>
        <div class="kpiValue">$ {{ $live['revenue_total'] ?? 0 }}</div>
      </div>

      <div class="card kpiCard reveal" style="--delay:.2s;">
        <div class="kpiLabel">Items vendidos</div>
        <div class="kpiValue">{{ $live['items_sold'] ?? 0 }}</div>
      </div>

      <div class="card kpiCard reveal" style="--delay:.22s;">
        <div class="kpiLabel">Estado sistema</div>
        <div class="kpiValue">OK</div>
      </div>

      <div class="card wide reveal" style="--delay:.24s;">
        <div class="cardHeader">
          <div class="cardTitle">Revenue diario</div>
          <div class="muted">Serie agregada por fecha</div>
        </div>
        <div class="cardBody">
          <canvas id="revenueChart" height="105"></canvas>
        </div>
      </div>

      <div class="card side reveal" style="--delay:.26s;">
        <div class="cardHeader">
          <div class="cardTitle">Ejecutar ETL manual</div>
          <div class="muted">Reconstruccion puntual</div>
        </div>
        <div class="cardBody">
          <form method="POST" action="{{ route('analytics.rebuild') }}" class="row">
            @csrf
            <input class="input" type="date" name="date">
            <button class="btn btnPrimary" type="submit">Ejecutar</button>
          </form>
          <div class="muted" style="margin-top:10px;">
            Si no seleccionas fecha, el backend decide el rango por defecto.
          </div>
        </div>
      </div>

      <div class="card reveal" style="grid-column: span 12; --delay:.3s;">
        <div class="cardHeader">
          <div class="cardTitle">Analytics diarios</div>
          <div class="muted">Tabla ETL</div>
        </div>
        <div class="cardBody" style="overflow:auto;">
          <table class="table">
            <thead>
              <tr>
                <th>Fecha</th>
                <th>Pedidos</th>
                <th>Confirmados</th>
                <th>Rechazados</th>
                <th>Revenue</th>
                <th>Items</th>
              </tr>
            </thead>
            <tbody>
              @forelse($daily as $row)
                @php $date = $row['date'] ?? $row['day'] ?? '-'; @endphp
                <tr>
                  <td>{{ $date }}</td>
                  <td>{{ $row['orders_total'] ?? 0 }}</td>
                  <td>{{ $row['orders_confirmed'] ?? 0 }}</td>
                  <td>{{ $row['orders_rejected'] ?? 0 }}</td>
                  <td>$ {{ $row['revenue_total'] ?? 0 }}</td>
                  <td>{{ $row['items_sold'] ?? 0 }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="muted" style="padding: 14px;">No hay datos de analytics aun</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>

  <script>
    const daily = @json($daily ?? []);

    const labels = daily.map(d => d.date ?? d.day ?? '');
    const values = daily.map(d => Number(d.revenue_total ?? 0));

    const ctx = document.getElementById('revenueChart');

    new Chart(ctx, {
      type: 'line',
      data: {
        labels,
        datasets: [{
          label: 'Revenue',
          data: values,
          borderColor: '#2563eb',
          backgroundColor: 'rgba(37,99,235,0.12)',
          tension: 0.35,
          pointRadius: 0,
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          x: { grid: { display: false } },
          y: { grid: { display: true } }
        }
      }
    });
  </script>
</body>

</html>