<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Circular Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #007BFF;
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 20px;
        }
        a {
            color: #007BFF;
            text-decoration: none;
        }
        .footer {
            font-size: 14px;
            color: #777;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Circular Notification</h1>
        <p>Hello {{ $recipientData['name'] ?? 'Recipient' }}, {{ ucfirst($recipientType) }}</p>
        <p>{{ $recipientData['message'] }}</p>
        @if($recipientData['file'])
            <p><a href="{{ Storage::url($recipientData['file']) }}">Download Attachment</a></p>
        @endif
        <div class="footer">
            <p>If you have any questions, please contact us at [Your Contact Email].</p>
            <p>Thank you for your attention.</p>
        </div>
    </div>
</body>
</html>
