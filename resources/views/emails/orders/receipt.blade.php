@php
    /** @var \App\Models\Order $order */
    $order = $order ?? null;
    $items = $items ?? [];
    $total = $total ?? 0;
@endphp

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Soccery Order Receipt</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #ffffff; color: #111827;">
    <h2 style="color:#6b21a8;">Thank you for your purchase from Soccery!</h2>
    <p>Hi {{ $order->user->first_name }} {{ $order->user->last_name }},</p>
    <p>Your order #{{ $order->id }} has been received.</p>

    <h3 style="color:#6b21a8;">Customer Details</h3>
    <p>Name: {{ $order->user->first_name }} {{ $order->user->last_name }}</p>
    <p>Email: {{ $order->user->email }}</p>
    <p>Order date: {{ $order->created_at?->format('M d, Y h:i A') }}</p>

    <h3 style="color:#6b21a8;">Order Summary</h3>
    <table width="100%" cellpadding="6" cellspacing="0" border="1" style="border-collapse: collapse; border-color:#e5e7eb;">
        <thead>
            <tr>
                <th align="left">Product</th>
                <th align="left">Brand</th>
                <th align="left">Category</th>
                <th align="left">Quantity</th>
                <th align="left">Price</th>
                <th align="left">Line total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item['product']->name }}</td>
                    <td>{{ optional($item['product']->brand)->name ?? '-' }}</td>
                    <td>{{ optional($item['product']->category)->name ?? '-' }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>£{{ number_format((float) $item['product']->price, 2) }}</td>
                    <td>£{{ number_format((float) $item['line_total'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top:16px;">Order status: <strong>{{ ucfirst(str_replace('_', ' ', $order->status)) }}</strong></p>
    <p style="margin-top:16px;">Payment method: <strong>{{ ucfirst(str_replace('_',' ', $order->payment_method)) }}</strong></p>
    <p style="margin-top:8px;">Order total: <strong>£{{ number_format((float) $total, 2) }}</strong></p>
    <p style="margin-top:8px;">Your PDF receipt is attached to this email.</p>
    <p style="margin-top:24px;">Best regards,<br>Soccery Team</p>
</body>
</html>

