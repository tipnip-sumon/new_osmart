<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Package Activated Successfully</title>
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
            border-bottom: 2px solid #28a745;
        }
        .header h1 {
            color: #28a745;
            font-size: 28px;
            margin: 0;
        }
        .emoji {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .package-details {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 25px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        .package-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .investment-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #007bff;
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
        .cta-button {
            display: inline-block;
            background: #007bff;
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
            <div class="emoji">🎉</div>
            <h1>Package Activated Successfully!</h1>
            <p style="color: #666; margin: 5px 0 0 0;">Congratulations {{ $user->first_name }}!</p>
        </div>

        <!-- Greeting -->
        <p>Dear <strong>{{ $user->first_name }} {{ $user->last_name }}</strong>,</p>
        
        <p>Great news! Your package has been successfully activated and is now ready to provide you with the benefits and features included in your selected plan.</p>

        <!-- Package Details -->
        <div class="package-details">
            <div class="package-name">{{ $plan->name }}</div>
            <p style="margin: 0; opacity: 0.9;">Activated on {{ $activation_date }}</p>
        </div>

        <!-- Investment Information -->
        <div class="investment-info">
            <h3 style="margin-top: 0; color: #007bff;">
                <span class="success-icon">✅</span> Activation Summary
            </h3>
            
            @if($amount_deducted > 0)
                <div class="info-row">
                    <span>Wallet Deduction:</span>
                    <span><strong>৳{{ number_format($amount_deducted, 2) }}</strong></span>
                </div>
            @endif
            
            <div class="info-row">
                <span>Points Used:</span>
                <span><strong>{{ number_format($points_used) }} points</strong></span>
            </div>
            
            <div class="info-row">
                <span>Total Investment:</span>
                <span><strong>{{ $total_cost }}</strong></span>
            </div>
        </div>

        <!-- Benefits -->
        <div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <h3 style="margin-top: 0; color: #856404;">
                🏆 What's Next?
            </h3>
            <ul style="margin: 0; padding-left: 20px; color: #856404;">
                <li>Your package is now active and all features are available</li>
                <li>Enjoy the benefits and rewards included in your package</li>
                <li>Track your progress and earnings in the member dashboard</li>
                <li>Refer more members to unlock additional benefits and commissions</li>
            </ul>
        </div>

        <!-- CTA Button -->
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ config('app.url') }}/member/packages" class="cta-button">
                View My Packages
            </a>
        </div>

        <!-- Additional Info -->
        <p>If you have any questions about your package benefits or how to maximize your earnings, please don't hesitate to contact our support team.</p>

        <p>Thank you for choosing our platform!</p>

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