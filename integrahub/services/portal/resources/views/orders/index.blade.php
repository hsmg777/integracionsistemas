<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>IntegraHub · Orders</title>
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
      --max: 1200px;

      --ok: rgba(52,211,153,.14);
      --warn: rgba(250,204,21,.14);
      --bad: rgba(251,113,133,.14);
      --neutral: rgba(255,255,255,.06);
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

        --ok: rgba(5,150,105,.12);
        --warn: rgba(202,138,4,.12);
        --bad: rgba(225,29,72,.10);
        --neutral: rgba(15,23,42,.04);
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

    .wrap{ max-width: var(--max); margin: 0 auto; padding: 26px 18px 40px; }

    .top{
      display:flex; align-items:flex-start; justify-content:space-between; gap: 12px; margin-bottom: 16px;
    }
    .title{ margin:0; font-size: 20px; font-weight: 750; letter-spacing: -.01em; }
    .subtitle{ margin-top: 6px; color: var(--muted); font-size: 13px; line-height: 1.5; }

    .notice{
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 12px 14px;
      margin-bottom: 14px;
      font-size: 13px;
      background: rgba(255,255,255,.03);
    }
    .notice.ok{ background: rgba(52,211,153,.10); }
    .notice.err{ background: rgba(251,113,133,.10); }

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
      background: rgba(251,113,133,.10);
      border-color: rgba(251,113,133,.22);
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

    .mono{ font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono","Courier New", monospace; }

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
  </style>
</head>

<body>
  <div class="wrap">
    <div class="top">
      <div>
        <h1 class="title">Orders</h1>
        <div class="subtitle">Creación de pedidos y trazabilidad por eventos</div>
      </div>
    </div>

    @if(isset($health))
      <div class="notice">
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
      <div class="notice ok">{{ session('success') }}</div>
    @endif

    @if (session('error'))
      <div class="notice err">{{ session('error') }}</div>
    @endif

    <div class="card">
      <div class="cardHeader">
        <div class="cardTitle">Crear pedido</div>
        <div class="muted">Envía customer_email y items</div>
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
                     placeholder="Si vacío, se calcula por items">
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

    <div class="card">
      <div class="cardHeader">
        <div class="cardTitle">Pedidos</div>
        <div class="muted">Listado y último evento</div>
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
                <td colspan="6" class="muted" style="padding: 14px;">No hay pedidos todavía</td>
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
