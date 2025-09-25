<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Referral - {{ $site_name }}</title>
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
            background: linear-gradient(135deg, #28a745, #1e7e34);
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
        .congratulations {
            font-size: 18px;
            margin-bottom: 20px;
            color: #28a745;
        }
        .details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .highlight {
            background: #e8f5e8;
            padding: 15px;
            border-left: 4px solid #28a745;
            margin: 15px 0;
        }
        .button {
            display: inline-block;
            background: #28a745;
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
        <h1>🎉 Congratulations!</h1>
        <p>You Have a New Referral</p>
    </div>
    
    <div class="content">
        <div class="congratulations">
            <h2>Hello {{ $sponsor->firstname }} {{ $sponsor->lastname }}!</h2>
            <p>Great news! A new affiliate has joined your team at {{ $site_name }}.</p>
        </div>

        <div class="highlight">
            <h3>🎯 Your Network is Growing!</h3>
            <p>Your referral efforts are paying off. Keep up the excellent work building your affiliate network!</p>
        </div>

        <div class="details">
            <h3>New Referral Details:</h3>
            <ul>
                <li><strong>Name:</strong> {{ $new_user->firstname }} {{ $new_user->lastname }}</li>
                <li><strong>Username:</strong> {{ $new_user->username }}</li>
                <li><strong>Email:</strong> {{ $new_user->email }}</li>
                <li><strong>Position:</strong> {{ ucfirst($new_user->position) }}</li>
                <li><strong>Placement Type:</strong> {{ ucfirst($new_user->placement_type) }}</li>
                <li><strong>Registration Date:</strong> {{ $new_user->created_at->format('F j, Y \a\t g:i A') }}</li>
            </ul>
        </div>

        <div class="highlight">
            <h3>What's Next?</h3>
            <ul>
                <li>Welcome your new team member and provide guidance</li>
                <li>Share tips for getting started with affiliate marketing</li>
                <li>Monitor their progress through your dashboard</li>
                <li>Help them understand the commission structure</li>
            </ul>
        </div>

        <p><strong>Remember:</strong> The success of your team members directly contributes to your earning potential. Consider reaching out to welcome them personally!</p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $dashboard_url }}" class="button">View Your Dashboard</a>
        </div>

        <p>Keep building your network and watch your commissions grow!</p>
        
        <p>Best regards,<br>
        The {{ $site_name }} Team</p>
    </div>
    
    <div class="footer">
        <p>&copy; 2025 {{ $site_name }}. All rights reserved.</p>
        <p>This email was sent because someone registered as your referral at {{ $site_name }}.</p>
    </div>
</body>
</html>
