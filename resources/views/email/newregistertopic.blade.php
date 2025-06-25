<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Registration Information</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 40px;">
    <h2 style="color: #333;">Have new registration,</h2>

    <p><strong>Details:</strong></p>
    <ul>
        <li><strong>Name:</strong> {{ $data['full_name'] }}</li>
        <li><strong>Email:</strong> {{ $data['email'] }}</li>
        <li><strong>Phone:</strong> {{ $data['phone'] }}</li>
        <li><strong>Company:</strong> {{ $data['company'] }}</li>
        <li><strong>Country:</strong> {{ $data['country'] }}</li>
        <li><strong>Job Title:</strong> {{ $data['job_title'] }}</li>
        <li><strong>Industry:</strong> {{ $data['industry'] }}</li>
        <li><strong>Registration Type:</strong> {{ $data['user_type'] }}</li>
    </ul>
</body>

</html>
