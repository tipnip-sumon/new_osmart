<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email - {{ $site_name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        .title {
            color: #333;
            font-size: 22px;
            margin-bottom: 20px;
        }
        .content {
            margin-bottom: 30px;
        }
        .verify-button {
            display: inline-block;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
        }
        .verify-button:hover {
            background-color: #0056b3;
            color: #ffffff;
            text-decoration: none;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 14px;
            color: #666;
            text-align: center;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .link-text {
            word-break: break-all;
            font-size: 12px;
            color: #666;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">{{ $site_name }}</div>
            <h1 class="title">Verify Your Email Address</h1>
        </div>

        <div class="content">
            <p>Hello {{ $user->firstname }} {{ $user->lastname }},</p>
            
            <p>Thank you for registering with {{ $site_name }}! To complete your registration and start using your affiliate account, please verify your email address by clicking the button below:</p>
            
            <div style="text-align: center;">
                <a href="{{ $verification_url }}" class="verify-button">Verify Email Address</a>
            </div>
            
            <div class="warning">
                <strong>Important:</strong> This verification link will expire in 60 minutes for security reasons.
            </div>
            
            <p>If the button above doesn't work, you can copy and paste the following link into your browser:</p>
            <div class="link-text">{{ $verification_url }}</div>
            
            <p>If you did not create an account with {{ $site_name }}, no further action is required and you can safely ignore this email.</p>
        </div>

        <div class="footer">
            <p>Best regards,<br>The {{ $site_name }} Team</p>
            <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
            <p>If you have any questions, please contact us at <a href="mailto:{{ $support_email }}">{{ $support_email }}</a></p>
            <p><small>This is an automated email. Please do not reply to this message.</small></p>
        </div>
    </div>
</body>
</html>
