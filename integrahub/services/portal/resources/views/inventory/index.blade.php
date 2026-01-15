<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>IntegraHub · Inventory</title>
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
      --max: 1100px;
      --okBg: rgba(52,211,153,.10);
      --errBg: rgba(251,113,133,.10);
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
        --errBg: rgba(225,29,72,.10);
      }
    }

    *{ box-sizing:border-box; }
    body{
      margin:0;
      font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
      background:
        radial-gradient(900px 480px at 25% 0%, rgba(125,211,252,.14), transparent 55%),
        radial-gradient(900px 480px at 85% 10%, rgba(167,139,250,.12), transparent 55%),
        var(--bg);
      color: var(--text);
      -webkit-font-smoothing: antialiased;
    }

    .wrap{ max-width: var(--max); margin: 0 auto; padding: 26px 18px 40px; }
    .top{ display:flex; align-items:flex-start; justify-content:space-between; gap: 12px; margin-bottom: 16px; }
    .title{ margin:0; font-size: 20px; font-weight: 750; letter-spacing: -.01em; }
    .subtitle{ margin-top: 6px; color: var(--muted); font-size: 13px; line-height: 1.5; }

    .card{
      border: 1px solid var(--border);
      background: linear-gradient(180deg, var(--panel), rgba(255,255,255,.03));
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow:hidden;
    }
    .cardHeader{ padding: 14px 14px 0; }
    .cardTitle{ font-size: 13px; font-weight: 750; }
    .cardBody{ padding: 12px 14px 14px; }

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

    .mono{ font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; }
    .empty{ color: var(--muted); padding: 14px; text-align:center; }
  </style>
</head>

<body>
  <div class="wrap">
    <div class="top">
      <div>
        <h1 class="title">Inventory</h1>
        <div class="subtitle">Carga de CSV y visualización de items</div>
      </div>
    </div>

    @if (session('success'))
      <div class="notice ok">{{ session('success') }}</div>
    @endif

    @if (session('error'))
      <div class="notice err">{{ session('error') }}</div>
    @endif

    <div class="card" style="margin-bottom:14px;">
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
          Formato esperado: SKU, Name, Stock, Price (según tu backend).
        </div>
      </div>
    </div>

    <div class="card">
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
