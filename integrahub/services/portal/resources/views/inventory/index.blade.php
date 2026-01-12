<!doctype html>
<html>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"></head>
<body style="font-family: Arial; padding: 24px;">
  <h1>Inventario (CSV Inbox)</h1>

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

  <form method="POST" action="{{ route('inventory.upload') }}" enctype="multipart/form-data"
        style="border:1px solid #ddd; padding:12px; margin-bottom:18px;">
    @csrf
    <input type="file" name="file" required>
    <button type="submit" style="padding:8px 12px;">Subir CSV</button>
  </form>

  <table style="width:100%; border-collapse:collapse;">
    <thead>
      <tr>
        <th style="border:1px solid #ddd; padding:8px;">SKU</th>
        <th style="border:1px solid #ddd; padding:8px;">Nombre</th>
        <th style="border:1px solid #ddd; padding:8px;">Stock</th>
        <th style="border:1px solid #ddd; padding:8px;">Precio</th>
      </tr>
    </thead>
    <tbody>
      @forelse($items as $it)
        <tr>
          <td style="border:1px solid #ddd; padding:8px; font-family: monospace;">{{ $it['sku'] ?? '' }}</td>
          <td style="border:1px solid #ddd; padding:8px;">{{ $it['name'] ?? '' }}</td>
          <td style="border:1px solid #ddd; padding:8px;">{{ $it['stock'] ?? '' }}</td>
          <td style="border:1px solid #ddd; padding:8px;">{{ $it['price'] ?? '' }}</td>
        </tr>
      @empty
        <tr><td colspan="4" style="border:1px solid #ddd; padding:10px; text-align:center;">Sin datos.</td></tr>
      @endforelse
    </tbody>
  </table>
</body>
</html>
