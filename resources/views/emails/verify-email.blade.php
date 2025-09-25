<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email Address</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            padding: 0;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 16px;
        }
        .content {
            padding: 40px 30px;
            background: white;
        }
        .content h2 {
            color: #333;
            margin: 0 0 20px 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content p {
            margin: 15px 0;
            font-size: 16px;
            line-height: 1.6;
        }
        .verification-box {
            background: #f8f9ff;
            border: 2px solid #e8ebff;
            border-radius: 10px;
            padding: 25px;
            margin: 25px 0;
            text-align: center;
        }
        .verify-btn {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 35px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 18px;
            margin: 20px 0;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .verify-btn:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .security-note {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
            font-size: 14px;
            color: #856404;
        }
        .footer {
            background: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        .footer p {
            margin: 5px 0;
            font-size: 14px;
            color: #6c757d;
        }
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
        .user-info {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin: 20px 0;
        }
        .user-info strong {
            color: #1976d2;
        }
        @media (max-width: 600px) {
            .container {
                margin: 10px;
                border-radius: 5px;
            }
            .header, .content, .footer {
                padding: 20px;
            }
            .header h1 {
                font-size: 24px;
            }
            .verify-btn {
                padding: 12px 25px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
            <p>Email Verification Required</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <h2>Hello {{ $user->firstname ?? $user->name ?? 'Member' }}!</h2>
            
            <p>Thank you for registering with {{ config('app.name') }}! To complete your account setup and ensure the security of your account, we need to verify your email address.</p>
            
            <!-- User Information -->
            <div class="user-info">
                <strong>Account Details:</strong><br>
                <strong>Name:</strong> {{ $user->firstname ?? $user->name }} {{ $user->lastname ?? '' }}<br>
                <strong>Email:</strong> {{ $user->email }}<br>
                <strong>Registration Date:</strong> {{ $user->created_at->format('M d, Y h:i A') }}
            </div>
            
            <!-- Verification Button -->
            <div class="verification-box">
                <p><strong>Click the button below to verify your email address:</strong></p>
                <a href="{{ $verificationUrl }}" class="verify-btn">Verify Email Address</a>
                <p style="font-size: 14px; color: #666; margin-top: 15px;">
                    This link will expire in 60 minutes for security purposes.
                </p>
            </div>
            
            <!-- Alternative Link -->
            <p><strong>Having trouble with the button?</strong> Copy and paste this URL into your browser:</p>
            <p style="word-break: break-all; background: #f8f9fa; padding: 10px; border-radius: 4px; font-family: monospace; font-size: 12px;">
                {{ $verificationUrl }}
            </p>
            
            <!-- Security Note -->
            <div class="security-note">
                <strong>Security Notice:</strong> If you did not create an account with us, please ignore this email. Your email address will not be added to our system without verification.
            </div>
            
            <p><strong>Why verify your email?</strong></p>
            <ul>
                <li>✅ Access all member features and services</li>
                <li>✅ Receive important account notifications</li>
                <li>✅ Enhanced account security</li>
                <li>✅ Enable withdrawal and transaction capabilities</li>
                <li>✅ Password reset functionality</li>
            </ul>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p><strong>{{ config('app.name') }}</strong></p>
            <p>If you have any questions, please contact our support team at 
               <a href="mailto:support@{{ str_replace(['http://', 'https://', 'www.'], '', config('app.url')) }}">
                   support@{{ str_replace(['http://', 'https://', 'www.'], '', config('app.url')) }}
               </a>
            </p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>