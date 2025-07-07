<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Membership Registration Information</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 40px;">
    <h2 style="color: #333;">Have new Membership Registration,</h2>

    <p><strong>Details:</strong></p>
    <ul>
        <li><strong>Name:</strong> {{ $data['name'] }}</li>
        <li><strong>Email:</strong> {{ $data['email'] }}</li>
        <li><strong>Phone:</strong> {{ $data['phone'] }}</li>
    </ul>
</body>

</html>
