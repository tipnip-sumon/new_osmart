<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to {{ $site_name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px 20px;
            border: 1px solid #e9ecef;
        }
        .footer {
            background: #343a40;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 10px 10px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
        .btn:hover {
            background: #0056b3;
        }
        .info-box {
            background: #e7f3ff;
            border: 1px solid #b3d7ff;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .sponsor-info {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .highlight {
            color: #007bff;
            font-weight: bold;
        }
        .password-box {
            background: #fff3cd;
            border: 2px solid #ffeaa7;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 5px solid #f39c12;
        }
        .password-highlight {
            background: #f8f9fa;
            padding: 8px 12px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-weight: bold;
            color: #dc3545;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎉 Welcome to {{ $site_name }}!</h1>
        <p>Your account has been created successfully</p>
    </div>

    <div class="content">
        <h2>Hello {{ $full_name }}!</h2>
        
        <p>Congratulations and welcome to <strong>{{ $site_name }}</strong>! Your account has been successfully created and you're now part of our growing community.</p>

        <div class="info-box">
            <h3>📋 Your Account Details</h3>
            <ul>
                <li><strong>Username:</strong> {{ $username }}</li>
                <li><strong>Email:</strong> {{ $user->email }}</li>
                <li><strong>Phone:</strong> {{ $user->phone ?? 'Not provided' }}</li>
                @if($include_password && $password)
                <li><strong>Password:</strong> <span class="highlight">{{ $password }}</span></li>
                @endif
                <li><strong>Referral Code:</strong> <span class="highlight">{{ $referral_code }}</span></li>
                <li><strong>Registration Date:</strong> {{ $user->created_at->format('F j, Y \a\t g:i A') }}</li>
            </ul>
        </div>

        @if($include_password && $password)
        <div class="password-box">
            <h3>🔐 Your Login Credentials</h3>
            <p><strong>Important:</strong> Please save these credentials in a secure location:</p>
            <table style="width: 100%; margin: 15px 0;">
                <tr>
                    <td style="padding: 8px; font-weight: bold;">Username:</td>
                    <td style="padding: 8px;"><span class="password-highlight">{{ $username }}</span></td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: bold;">Password:</td>
                    <td style="padding: 8px;"><span class="password-highlight">{{ $password }}</span></td>
                </tr>
            </table>
            <p style="color: #856404; font-style: italic; margin-top: 15px;">
                <strong>Security Note:</strong> For your security, we recommend changing your password after your first login.
            </p>
        </div>
        @endif

        @if($user->sponsor)
        <div class="sponsor-info">
            <h3>🤝 Your Sponsor Information</h3>
            <p>You were referred by <strong>{{ $sponsor_name }}</strong></p>
            <p>Your position in the network: <span class="highlight">{{ ucfirst($user->position ?? 'Auto') }}</span></p>
        </div>
        @endif

        <div class="info-box">
            <h3>🚀 What's Next?</h3>
            <p>Here are some things you can do to get started:</p>
            <ul>
                <li>Complete your profile information</li>
                <li>Explore our platform features</li>
                <li>Start referring friends and family</li>
                <li>Check your dashboard for updates</li>
            </ul>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $login_url }}" class="btn">Login to Your Account</a>
        </div>

        <div class="info-box">
            <h3>💡 Important Information</h3>
            <ul>
                <li>Keep your login credentials secure</li>
                @if($include_password && $password)
                <li><strong>Change your password</strong> after your first login for better security</li>
                <li>Never share your password with anyone</li>
                @endif
                <li>Your referral code is: <strong>{{ $referral_code }}</strong></li>
                <li>Share your referral link with friends to earn rewards</li>
                <li>Contact support if you need any assistance</li>
            </ul>
        </div>

        <p><strong>Need Help?</strong><br>
        If you have any questions or need assistance, please don't hesitate to contact our support team at <strong>{{ $support_email }}</strong>. We're here to help you succeed!</p>

        <p>Thank you for joining {{ $site_name }}. We're excited to have you on board!</p>

        <p>Best regards,<br>
        <strong>The {{ $site_name }} Team</strong></p>
    </div>

    <div class="footer">
        <p>&copy; {{ $current_year }} {{ $site_name }}. All rights reserved.</p>
        <p>This is an automated email. Please do not reply to this message.</p>
        <p>Support: {{ $support_email }}</p>
    </div>
</body>
</html>
