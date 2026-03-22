<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Receipt #{{ $order->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #111827; font-size: 12px; }
        h1 { margin: 0 0 10px 0; font-size: 20px; color: #4c1d95; }
        h2 { margin: 18px 0 8px 0; font-size: 14px; color: #4c1d95; }
        .muted { color: #6b7280; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #d1d5db; padding: 8px; text-align: left; }
        th { background: #f3f4f6; }
        .total { margin-top: 12px; text-align: right; font-size: 14px; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Soccery Receipt</h1>
    <div class="muted">Order #{{ $order->id }}</div>
    <div class="muted">Date: {{ $order->created_at?->format('M d, Y h:i A') }}</div>

    <h2>Customer Details</h2>
    <div>Name: {{ $order->user->first_name }} {{ $order->user->last_name }}</div>
    <div>Email: {{ $order->user->email }}</div>
    <div>Payment method: {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</div>
    <div>Status: {{ ucfirst(str_replace('_', ' ', $order->status)) }}</div>

    <h2>Order Details</h2>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Brand</th>
                <th>Category</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Line total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item['product']->name }}</td>
                    <td>{{ optional($item['product']->brand)->name ?? '-' }}</td>
                    <td>{{ optional($item['product']->category)->name ?? '-' }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>&#8369;{{ number_format((float) $item['product']->price, 2) }}</td>
                    <td>&#8369;{{ number_format((float) $item['line_total'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">Total: &#8369;{{ number_format((float) $total, 2) }}</div>
</body>
</html>
