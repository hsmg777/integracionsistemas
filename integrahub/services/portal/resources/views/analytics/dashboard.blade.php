<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>IntegraHub · Analytics</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
      --accent: #7dd3fc;
      --radius: 16px;
      --radius-sm: 12px;
      --max: 1200px;
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
        --accent:#2563eb;
      }
    }

    *{ box-sizing:border-box; }
    html,body{ height:100%; }
    body{
      margin:0;
      font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
      background:
        radial-gradient(900px 480px at 20% 0%, rgba(125,211,252,.14), transparent 55%),
        radial-gradient(900px 480px at 80% 10%, rgba(167,139,250,.12), transparent 55%),
        var(--bg);
      color: var(--text);
      -webkit-font-smoothing: antialiased;
      line-height: 1.35;
    }

    a{ color:inherit; text-decoration:none; }
    .wrap{ max-width: var(--max); margin: 0 auto; padding: 26px 18px 40px; }

    .top{
      display:flex; align-items:flex-start; justify-content:space-between; gap: 12px; margin-bottom: 18px;
    }
    .title{
      margin:0;
      font-size: 20px;
      font-weight: 750;
      letter-spacing: -.01em;
    }
    .subtitle{
      margin-top: 6px;
      color: var(--muted);
      font-size: 13px;
      line-height: 1.5;
    }

    .btn{
      border: 1px solid var(--border);
      background: var(--panel);
      padding: 10px 12px;
      border-radius: 12px;
      font-weight: 650;
      font-size: 13px;
      transition: transform .12s ease, background .12s ease, border-color .12s ease;
      cursor:pointer;
    }
    .btn:hover{ background: var(--panel2); border-color: var(--border2); transform: translateY(-1px); }
    .btn:focus{ outline:none; box-shadow: 0 0 0 4px var(--ring); }

    .notice{
      border: 1px solid var(--border);
      background: rgba(52,211,153,.10);
      padding: 12px 14px;
      border-radius: 12px;
      margin-bottom: 16px;
      color: var(--text);
      font-size: 13px;
    }

    .grid{
      display:grid;
      grid-template-columns: repeat(12, 1fr);
      gap: 14px;
      margin-bottom: 14px;
    }

    .card{
      border: 1px solid var(--border);
      background: linear-gradient(180deg, var(--panel), rgba(255,255,255,.03));
      border-radius: var(--radius);
      box-shadow: var(--shadow);
    }

    .kpiCard{ padding: 14px 14px; grid-column: span 3; }
    @media (max-width: 980px){ .kpiCard{ grid-column: span 6; } }
    @media (max-width: 520px){ .kpiCard{ grid-column: span 12; } }

    .kpiLabel{ color: var(--muted); font-size: 12px; }
    .kpiValue{
      margin-top: 8px;
      font-size: 24px;
      font-weight: 800;
      letter-spacing: -.02em;
    }

    .wide{ grid-column: span 8; }
    .side{ grid-column: span 4; }
    @media (max-width: 980px){ .wide, .side{ grid-column: span 12; } }

    .cardHeader{
      padding: 14px 14px 0;
      display:flex; align-items:center; justify-content:space-between; gap: 10px;
    }
    .cardTitle{
      font-weight: 750;
      font-size: 13px;
      letter-spacing: .2px;
    }
    .cardBody{ padding: 12px 14px 14px; }

    .table{
      width:100%;
      border-collapse: separate;
      border-spacing: 0;
      overflow:hidden;
      border: 1px solid var(--border);
      border-radius: 14px;
      background: rgba(255,255,255,.03);
    }
    .table th, .table td{
      padding: 10px 10px;
      font-size: 13px;
      border-bottom: 1px solid var(--border);
      text-align: left;
      white-space: nowrap;
    }
    .table th{
      color: var(--muted);
      font-weight: 650;
      background: rgba(255,255,255,.03);
    }
    .table tr:last-child td{ border-bottom: none; }

    .muted{ color: var(--muted); font-size: 12px; }

    .row{
      display:flex;
      gap: 10px;
      flex-wrap:wrap;
      align-items:center;
    }

    .input{
      border: 1px solid var(--border);
      background: rgba(255,255,255,.04);
      color: var(--text);
      padding: 10px 12px;
      border-radius: 12px;
      font-size: 13px;
    }
    .input:focus{ outline:none; box-shadow: 0 0 0 4px var(--ring); border-color: var(--border2); }

    .pill{
      border: 1px solid var(--border);
      background: rgba(255,255,255,.04);
      padding: 6px 10px;
      border-radius: 999px;
      font-size: 12px;
      color: var(--muted);
    }

    .rightActions{
      display:flex; gap: 10px; align-items:center; flex-wrap:wrap;
    }
  </style>
</head>

<body>
  <div class="wrap">
    <div class="top">
      <div>
        <h1 class="title">Analytics</h1>
        <div class="subtitle">Orders · Inventory · Revenue · ETL</div>
      </div>

      <div class="rightActions">
        <span class="pill">Dashboard</span>
      </div>
    </div>

    @if(session('success'))
      <div class="notice">{{ session('success') }}</div>
    @endif

    <div class="grid">
      <div class="card kpiCard">
        <div class="kpiLabel">Orders confirmados</div>
        <div class="kpiValue">{{ $live['orders_confirmed'] ?? 0 }}</div>
      </div>

      <div class="card kpiCard">
        <div class="kpiLabel">Revenue total</div>
        <div class="kpiValue">$ {{ $live['revenue_total'] ?? 0 }}</div>
      </div>

      <div class="card kpiCard">
        <div class="kpiLabel">Items vendidos</div>
        <div class="kpiValue">{{ $live['items_sold'] ?? 0 }}</div>
      </div>

      <div class="card kpiCard">
        <div class="kpiLabel">Estado sistema</div>
        <div class="kpiValue">OK</div>
      </div>

      <div class="card wide">
        <div class="cardHeader">
          <div class="cardTitle">Revenue diario</div>
          <div class="muted">Serie agregada por fecha</div>
        </div>
        <div class="cardBody">
          <canvas id="revenueChart" height="105"></canvas>
        </div>
      </div>

      <div class="card side">
        <div class="cardHeader">
          <div class="cardTitle">Ejecutar ETL manual</div>
          <div class="muted">Reconstrucción puntual</div>
        </div>
        <div class="cardBody">
          <form method="POST" action="{{ route('analytics.rebuild') }}" class="row">
            @csrf
            <input class="input" type="date" name="date">
            <button class="btn" type="submit">Ejecutar</button>
          </form>
          <div class="muted" style="margin-top:10px;">
            Si no seleccionas fecha, el backend decide el rango por defecto (según tu implementación).
          </div>
        </div>
      </div>

      <div class="card" style="grid-column: span 12;">
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
                  <td colspan="6" class="muted" style="padding: 14px;">No hay datos de analytics aún</td>
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
          borderColor: '#111',
          backgroundColor: 'rgba(0,0,0,0.05)',
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
