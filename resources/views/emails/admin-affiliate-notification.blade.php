<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Affiliate Registration - {{ $site_name }}</title>
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
            background: linear-gradient(135deg, #dc3545, #bd2130);
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
        .alert {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .action-required {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            background: #dc3545;
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
        <h1>🔔 Admin Notification</h1>
        <p>New Affiliate Registration</p>
    </div>
    
    <div class="content">
        <h2>New Affiliate Registration Alert</h2>
        
        <div class="alert">
            <strong>Action Required:</strong> A new affiliate has registered and requires account approval.
        </div>

        <div class="details">
            <h3>Affiliate Information:</h3>
            <ul>
                <li><strong>Name:</strong> {{ $user->firstname }} {{ $user->lastname }}</li>
                <li><strong>Username:</strong> {{ $user->username }}</li>
                <li><strong>Email:</strong> {{ $user->email }}</li>
                <li><strong>Phone:</strong> {{ $user->phone }}</li>
                @if($user->country)
                <li><strong>Country:</strong> {{ $user->country }}</li>
                @endif
                @if($user->address)
                <li><strong>Address:</strong> {{ $user->address }}</li>
                @endif
            </ul>
        </div>

        <div class="details">
            <h3>MLM Network Information:</h3>
            <ul>
                <li><strong>Sponsor:</strong> {{ $sponsor->username }} ({{ $sponsor->firstname }} {{ $sponsor->lastname }})</li>
                <li><strong>Position:</strong> {{ ucfirst($user->position) }}</li>
                <li><strong>Placement Type:</strong> {{ ucfirst($user->placement_type) }}</li>
                @if($user->upline_username)
                <li><strong>Upline:</strong> {{ $user->upline_username }}</li>
                @endif
                <li><strong>Referral Code:</strong> {{ $user->referral_code }}</li>
                <li><strong>Registration Date:</strong> {{ $user->created_at->format('F j, Y \a\t g:i A') }}</li>
            </ul>
        </div>

        <div class="action-required">
            <h3>⚠️ Approval Required</h3>
            <p>This affiliate account is currently <strong>pending approval</strong>. Please review the account details and take appropriate action:</p>
            <ul>
                <li>Verify the provided information</li>
                <li>Check for duplicate accounts</li>
                <li>Approve or reject the application</li>
                <li>Send notification to the applicant</li>
            </ul>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $admin_dashboard_url }}" class="button">Admin Dashboard</a>
            <a href="{{ $user_details_url }}" class="button">View User Details</a>
        </div>

        <p><strong>System Information:</strong></p>
        <ul>
            <li>User ID: {{ $user->id }}</li>
            <li>Sponsor ID: {{ $sponsor->id }}</li>
            <li>IP Address: {{ request()->ip() ?? 'Unknown' }}</li>
            <li>User Agent: {{ request()->userAgent() ?? 'Unknown' }}</li>
        </ul>
        
        <p>Best regards,<br>
        {{ $site_name }} System</p>
    </div>
    
    <div class="footer">
        <p>&copy; 2025 {{ $site_name }}. All rights reserved.</p>
        <p>This is an automated admin notification from {{ $site_name }}.</p>
    </div>
</body>
</html>
