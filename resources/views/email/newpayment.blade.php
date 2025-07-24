<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Payment Information</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 40px;">
    <h2 style="color: #333;">Have new Payment Confirmation,</h2>

    <p><strong> {{ $data['paid_at'] }}</strong></p>
    <p><strong>Details:</strong></p>
    <ul>
        <li><strong>Name:</strong> {{ $data['name'] }}</li>
        <li><strong>Email:</strong> {{ $data['email'] }}</li>
        <li><strong>Phone:</strong> {{ $data['phone'] }}</li>
        <li><strong>Invoice:</strong> {{ $data['invoice'] }}</li>
        <li><strong>Bank:</strong> {{ $data['payment_method'] }}</li>
        <li><strong>Total:</strong>IDR {{ number_format($data['total_price']) }}</li>

    </ul>
</body>

</html>
