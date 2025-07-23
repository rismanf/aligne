<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>QR Code Invitation</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 40px;">
    <h2>Hello {{ $full_name }}</h2>
    <p>Your registration is <strong>confirmed</strong>. Please present this QR code at the entrance:</p>

    {{-- QR CODE IMAGE --}}
    <div style="margin: 20px 0; text-align: center;">
        <img src="{{ $qr_url }}" width="200" alt="QR Code">
    </div>


    <p><strong>Unique Code:</strong> {{ $unique_code }}</p>

    <p>Thank you,<br>NeutraDC Team</p>
</body>

</html>
