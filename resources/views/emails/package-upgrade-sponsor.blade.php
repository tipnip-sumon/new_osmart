<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Member Package Upgrade</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #007bff;
        }
        .header h1 {
            color: #007bff;
            font-size: 28px;
            margin: 0;
        }
        .emoji {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .member-details {
            background: linear-gradient(135deg, #007bff, #6f42c1);
            color: white;
            padding: 25px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        .member-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .member-email {
            opacity: 0.9;
            font-size: 16px;
        }
        .upgrade-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .info-row:last-child {
            border-bottom: none;
            font-weight: bold;
            color: #28a745;
        }
        .commission-highlight {
            background: linear-gradient(135deg, #ffd700, #ffed4a);
            color: #333;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
            border: 2px solid #f1c40f;
        }
        .cta-button {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
        .success-icon {
            color: #28a745;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="emoji">💰</div>
            <h1>Team Member Upgraded!</h1>
            <p style="color: #666; margin: 5px 0 0 0;">Commission Opportunity Alert</p>
        </div>

        <!-- Greeting -->
        <p>Dear <strong>{{ $sponsor->first_name }} {{ $sponsor->last_name }}</strong>,</p>
        
        <p>Excellent news! One of your team members has successfully upgraded their package. This upgrade may qualify for commission bonuses according to your compensation plan.</p>

        <!-- Member Details -->
        <div class="member-details">
            <div class="member-name">{{ $user->first_name }} {{ $user->last_name }}</div>
            <div class="member-email">{{ $user->email }}</div>
        </div>

        <!-- Upgrade Information -->
        <div class="upgrade-info">
            <h3 style="margin-top: 0; color: #007bff;">
                <span class="success-icon">✅</span> Upgrade Details
            </h3>
            
            <div class="info-row">
                <span>New Package:</span>
                <span><strong>{{ $plan->name }}</strong></span>
            </div>
            
            <div class="info-row">
                <span>Activation Date:</span>
                <span><strong>{{ $activation_date }}</strong></span>
            </div>
            
            @if($amount_deducted > 0)
                <div class="info-row">
                    <span>Wallet Investment:</span>
                    <span><strong>৳{{ number_format($amount_deducted, 2) }}</strong></span>
                </div>
            @endif
            
            <div class="info-row">
                <span>Points Used:</span>
                <span><strong>{{ number_format($points_used) }} points</strong></span>
            </div>
            
            <div class="info-row">
                <span>Total Investment:</span>
                <span><strong>{{ $total_investment }}</strong></span>
            </div>
        </div>

        <!-- Commission Highlight -->
        <div class="commission-highlight">
            <h3 style="margin-top: 0; color: #333;">
                🏆 Potential Commission Earnings
            </h3>
            <p style="margin-bottom: 0; font-size: 16px;">
                This upgrade may qualify for direct sponsor commissions and generation bonuses based on your current package level and compensation plan. Check your commission dashboard for updates.
            </p>
        </div>

        <!-- Team Building Tips -->
        <div style="background: #e8f5e8; border: 1px solid #c3e6c3; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <h3 style="margin-top: 0; color: #155724;">
                📈 Keep Building Your Team
            </h3>
            <ul style="margin: 0; padding-left: 20px; color: #155724;">
                <li>Continue supporting your team members with guidance and training</li>
                <li>Share success strategies to help them grow their own networks</li>
                <li>Monitor your commission dashboard for earnings updates</li>
                <li>Consider upgrading your own package for higher commission rates</li>
            </ul>
        </div>

        <!-- CTA Button -->
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ config('app.url') }}/member/team/downline" class="cta-button">
                View My Team
            </a>
        </div>

        <!-- Additional Info -->
        <p>Keep up the great work building your team! Member upgrades like this are a key indicator of a healthy and growing network.</p>

        <p>For questions about commissions or team building strategies, please contact our support team.</p>

        <p style="margin-bottom: 0;">
            Best regards,<br>
            <strong>{{ config('app.name') }} Team</strong>
        </p>

        <!-- Footer -->
        <div class="footer">
            <p>This is an automated email. Please do not reply to this message.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>