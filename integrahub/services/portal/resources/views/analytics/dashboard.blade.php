<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>IntegraHub · Analytics</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body { font-family: Arial; padding: 24px; background:#fafafa; }
        h1 { margin-bottom: 6px; }
        .grid { display:grid; grid-template-columns: repeat(auto-fit,minmax(220px,1fr)); gap:16px; margin:20px 0; }
        .card {
            background:white; border:1px solid #ddd; padding:16px;
            box-shadow:0 1px 3px rgba(0,0,0,.06);
        }
        .kpi { font-size:28px; font-weight:bold; }
        .muted { color:#777; font-size:13px; }
        table { width:100%; border-collapse:collapse; margin-top:12px; }
        th,td { border:1px solid #ddd; padding:8px; text-align:center; }
        th { background:#f4f4f4; }
        button {
            padding:10px 14px; border:1px solid #333; background:#111; color:white;
            cursor:pointer;
        }
        button:hover { background:#333; }
        .success { background:#e9ffe9; border:1px solid #7dd87d; padding:10px; margin-bottom:10px; }
        .warn { color:#999; font-size:14px; }
    </style>
</head>

<body>

<h1>📊 IntegraHub · Analytics ETL</h1>
<div class="muted">Orders · Inventory · Revenue · Streaming</div>

@if(session('success'))
    <div class="success">✅ {{ session('success') }}</div>
@endif

{{-- ================= KPI CARDS ================= --}}
<div class="grid">
    <div class="card">
        <div class="muted">Orders confirmados</div>
        <div class="kpi">{{ $live['orders_confirmed'] ?? 0 }}</div>
    </div>
    <div class="card">
        <div class="muted">Revenue total</div>
        <div class="kpi">$ {{ $live['revenue_total'] ?? 0 }}</div>
    </div>
    <div class="card">
        <div class="muted">Items vendidos</div>
        <div class="kpi">{{ $live['items_sold'] ?? 0 }}</div>
    </div>
    <div class="card">
        <div class="muted">Estado sistema</div>
        <div class="kpi">🟢 OK</div>
    </div>
</div>

{{-- ================= CHART ================= --}}
<div class="card">
    <h3>📈 Revenue diario</h3>
    <canvas id="revenueChart" height="90"></canvas>
</div>

{{-- ================= TABLE ================= --}}
<div class="card">
    <h3>📅 Analytics diarios (ETL)</h3>

    <table>
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
            @php
                $date = $row['date'] ?? $row['day'] ?? '-';
            @endphp
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
                <td colspan="6" class="warn">No hay datos de analytics aún</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

{{-- ================= ETL MANUAL ================= --}}
<div class="card">
    <h3>⚙️ Ejecutar ETL manual</h3>

    <form method="POST" action="{{ route('analytics.rebuild') }}">
        @csrf
        <input type="date" name="date">
        <button type="submit">Ejecutar ETL</button>
    </form>
</div>

<script>
    const daily = @json($daily ?? []);

    const labels = daily.map(d => d.date ?? d.day ?? '');
    const values = daily.map(d => d.revenue_total ?? 0);

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
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display:false } }
        }
    });
</script>

</body>
</html>
