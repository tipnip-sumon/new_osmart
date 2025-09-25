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
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: white;
            padding: 30px;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .welcome-message {
            font-size: 18px;
            margin-bottom: 20px;
        }
        .details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6c757d;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to {{ $site_name }}!</h1>
        <p>Your Affiliate Journey Begins Here</p>
    </div>
    
    <div class="content">
        <div class="welcome-message">
            <h2>Hello {{ $user->firstname }} {{ $user->lastname }}!</h2>
            <p>Welcome to {{ $site_name }}! We're excited to have you join our affiliate network.</p>
        </div>

        <div class="details">
            <h3>Your Account Details:</h3>
            <ul>
                <li><strong>Username:</strong> {{ $user->username }}</li>
                <li><strong>Email:</strong> {{ $user->email }}</li>
                <li><strong>User ID:</strong> {{ $user->id }}</li>
                <li><strong>Referral Code:</strong> {{ $user->referral_code ?? 'Will be assigned shortly' }}</li>
                <li><strong>Account Status:</strong> Active</li>
                <li><strong>Role:</strong> Affiliate</li>
            </ul>
        </div>

        <p>🎉 Congratulations! You have successfully upgraded to an affiliate account. Your account is now active and you can start earning commissions immediately!</p>
        
        <div style="background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <h4 style="color: #155724; margin: 0 0 10px 0;">🚀 What You Can Do Now:</h4>
            <ul style="margin: 0; color: #155724;">
                <li><strong>Start Referring:</strong> Share your referral link and earn commissions</li>
                <li><strong>Access Dashboard:</strong> View your earnings and referral statistics</li>
                <li><strong>Build Network:</strong> Grow your affiliate team in our MLM system</li>
                <li><strong>Track Progress:</strong> Monitor your commissions and bonuses in real-time</li>
            </ul>
        </div>

        <div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <h4 style="color: #856404; margin: 0 0 10px 0;">📝 Next Steps:</h4>
            <ol style="margin: 0; color: #856404;">
                <li><strong>Complete Your Profile:</strong> Add additional details to maximize your success</li>
                <li><strong>Get Your Referral Link:</strong> Access your unique referral link from the dashboard</li>
                <li><strong>Learn the System:</strong> Review our affiliate program guidelines and commission structure</li>
                <li><strong>Start Promoting:</strong> Begin sharing products and building your network</li>
            </ol>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $affiliate_dashboard_url }}" class="button">Access Your Dashboard</a>
            <a href="{{ $referral_link }}" class="button" style="background: #28a745;">Get Referral Link</a>
        </div>

        <p><strong>🎯 Your Referral Link:</strong><br>
        <code style="background: #f8f9fa; padding: 10px; border-radius: 3px; display: block; margin: 10px 0;">{{ $referral_link }}</code></p>

        <p>Share this link with friends and family to start earning commissions on their purchases and referrals!</p>

        <p>If you have any questions or need assistance, please don't hesitate to contact our support team.</p>
        
        <p>Best regards,<br>
        The {{ $site_name }} Team</p>
    </div>
    
    <div class="footer">
        <p>&copy; 2025 {{ $site_name }}. All rights reserved.</p>
        <p>This email was sent because you registered for an affiliate account at {{ $site_name }}.</p>
    </div>
</body>
</html>
