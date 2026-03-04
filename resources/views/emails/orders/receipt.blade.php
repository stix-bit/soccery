@php
    /** @var \App\Models\Order $order */
    $order = $order ?? null;
@endphp

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Soccery Order Receipt</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #ffffff; color: #111827;">
    <h2 style="color:#6b21a8;">Thank you for your purchase from Soccery!</h2>
    <p>Hi {{ $order->user->name }},</p>
    <p>Your order #{{ $order->id }} has been received.</p>

    <h3 style="color:#6b21a8;">Order summary</h3>
    <table width="100%" cellpadding="6" cellspacing="0" border="1" style="border-collapse: collapse; border-color:#e5e7eb;">
        <thead>
            <tr>
                <th align="left">Product</th>
                <th align="left">Quantity</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top:16px;">Payment method: <strong>{{ ucfirst(str_replace('_',' ', $order->payment_method)) }}</strong></p>
    <p style="margin-top:24px;">Best regards,<br>Soccery Team</p>
</body>
</html>

