<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>IntegraHub - Demo Portal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body style="font-family: Arial; padding: 24px;">
  <h1>IntegraHub - Demo Portal</h1>

  @if (session('success'))
    <div style="margin:12px 0; padding:10px; background:#e9ffe9; border:1px solid #7dd87d;">
      ✅ {{ session('success') }}
    </div>
  @endif

  @if (session('error'))
    <div style="margin:12px 0; padding:10px; background:#ffe9e9; border:1px solid #e07b7b;">
      ❌ {{ session('error') }}
    </div>
  @endif

  <h2>Crear pedido</h2>

  <form method="POST" action="{{ route('orders.create') }}"
        style="border:1px solid #ddd; padding:12px; margin-bottom:18px;">
    @csrf

    <div style="margin-bottom:10px;">
      <label>Email cliente</label><br>
      <input name="customer_email" type="email" required
             value="{{ old('customer_email', 'hayland@example.com') }}"
             style="padding:8px; width:320px;">
    </div>

    <div style="margin-bottom:10px;">
      <label>Total (opcional)</label><br>
      <input name="total_amount" type="number" step="0.01"
             value="{{ old('total_amount') }}"
             placeholder="Si lo dejas vacío, se calcula por items"
             style="padding:8px; width:320px;">
    </div>

    <h3>Items</h3>

    <div id="items">
      <div class="item-row" style="display:flex; gap:8px; margin-bottom:8px;">
        <input name="items[0][sku]" placeholder="SKU" required value="SKU-1"
               style="padding:8px; width:140px;">
        <input name="items[0][qty]" placeholder="Qty" type="number" required value="1"
               style="padding:8px; width:90px;">
        <input name="items[0][price]" placeholder="Price" type="number" step="0.01" required value="10.50"
               style="padding:8px; width:120px;">
        <button type="button" onclick="removeRow(this)">X</button>
      </div>
    </div>

    <button type="button" onclick="addItem()" style="padding:8px 10px; margin-top:6px;">
      + Agregar item
    </button>

    <div style="margin-top:12px;">
      <button type="submit" style="padding:10px 14px; cursor:pointer;">
        Crear pedido
      </button>
    </div>
  </form>

  <h2>Pedidos</h2>

  <table style="width:100%; border-collapse:collapse;">
    <thead>
      <tr>
        <th style="border:1px solid #ddd; padding:8px;">ID</th>
        <th style="border:1px solid #ddd; padding:8px;">Correlation</th>
        <th style="border:1px solid #ddd; padding:8px;">Status</th>
        <th style="border:1px solid #ddd; padding:8px;">Last event</th>
        <th style="border:1px solid #ddd; padding:8px;">Last event at</th>
        <th style="border:1px solid #ddd; padding:8px;"></th>
      </tr>
    </thead>

    <tbody>
      @forelse(($orders ?? []) as $o)
        @php
          $status = strtoupper((string)($o['status'] ?? ''));
          $badgeBg =
            $status === 'CONFIRMED' ? '#e9ffe9' :
            ($status === 'REJECTED' ? '#ffe9e9' :
            ($status === 'PROCESSING' ? '#fff6d6' : '#f4f4f4'));

          $badgeBorder =
            $status === 'CONFIRMED' ? '#7dd87d' :
            ($status === 'REJECTED' ? '#e07b7b' :
            ($status === 'PROCESSING' ? '#e0c66a' : '#ddd'));
        @endphp

        <tr>
          <td style="border:1px solid #ddd; padding:8px;">{{ $o['id'] ?? '' }}</td>

          <td style="border:1px solid #ddd; padding:8px; font-family: monospace;">
            {{ $o['correlation_id'] ?? '' }}
          </td>

          <td style="border:1px solid #ddd; padding:8px;">
            <span style="padding:4px 8px; border:1px solid {{ $badgeBorder }}; background:{{ $badgeBg }};">
              <b>{{ $o['status'] ?? '' }}</b>
            </span>
          </td>

          <td style="border:1px solid #ddd; padding:8px; font-family: monospace;">
            {{ $o['last_event'] ?? '-' }}
          </td>

          <td style="border:1px solid #ddd; padding:8px;">
            {{ $o['last_event_at'] ?? '-' }}
          </td>

          <td style="border:1px solid #ddd; padding:8px;">
            @if(!empty($o['id']))
              <a href="{{ route('orders.show', $o['id']) }}">ver</a>
            @endif
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" style="border:1px solid #ddd; padding:10px; text-align:center;">
            No hay pedidos todavía.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <script>
    let idx = 1;

    function addItem() {
      const container = document.getElementById('items');
      const row = document.createElement('div');
      row.className = 'item-row';
      row.style = "display:flex; gap:8px; margin-bottom:8px;";

      row.innerHTML = `
        <input name="items[${idx}][sku]" placeholder="SKU" required style="padding:8px; width:140px;">
        <input name="items[${idx}][qty]" placeholder="Qty" type="number" required value="1" style="padding:8px; width:90px;">
        <input name="items[${idx}][price]" placeholder="Price" type="number" step="0.01" required value="0.00" style="padding:8px; width:120px;">
        <button type="button" onclick="removeRow(this)">X</button>
      `;
      container.appendChild(row);
      idx++;
    }

    function removeRow(btn) {
      const row = btn.closest('.item-row');
      if (row) row.remove();
    }
  </script>
</body>
</html>
