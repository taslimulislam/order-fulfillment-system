<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Confirmation</title>
</head>
<body>
    <h2>Thank you for your order, {{ $buyer->name }}!</h2>
    <p>Your order ID is: <strong>{{ $order->order_number }}</strong></p>

    <h4>Order Details:</h4>
    <ul>
        @foreach ($items as $item)
            <li>{{ $item->product->name }} (x{{ $item->quantity }}) — ${{ $item->price }}</li>
        @endforeach
    </ul>

    <p><strong>Total Amount:</strong> ${{ $order->total_amount }}</p>
    <p>Status: {{ ucfirst($order->status) }}</p>

    <p>We will notify you once your items are shipped.</p>
</body>
</html>
