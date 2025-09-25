<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New User Registration - {{ $site_name }}</title>
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
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
            margin: 10px 5px;
        }
        .btn:hover {
            background: #0056b3;
        }
        .btn-success {
            background: #28a745;
        }
        .btn-success:hover {
            background: #1e7e34;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: white;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .info-table th,
        .info-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }
        .info-table th {
            background: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }
        .info-table tr:last-child td {
            border-bottom: none;
        }
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .alert-info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }
        .alert-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            background: #007bff;
            color: white;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-success {
            background: #28a745;
        }
        .badge-warning {
            background: #ffc107;
            color: #212529;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>👤 New User Registration</h1>
        <p>A new user has registered on {{ $site_name }}</p>
    </div>

    <div class="content">
        <div class="alert alert-success">
            <strong>📢 New Registration Alert!</strong><br>
            A new user <strong>{{ $user->username }}</strong> has successfully registered on your platform.
        </div>

        <h3>👤 User Information</h3>
        <table class="info-table">
            <tr>
                <th>Full Name</th>
                <td>{{ $user->firstname }} {{ $user->lastname }}</td>
            </tr>
            <tr>
                <th>Username</th>
                <td><strong>{{ $user->username }}</strong></td>
            </tr>
            <tr>
                <th>Email Address</th>
                <td>{{ $user->email }}</td>
            </tr>
            <tr>
                <th>Phone Number</th>
                <td>{{ $user->phone ?? 'Not provided' }}</td>
            </tr>
            <tr>
                <th>User ID</th>
                <td>#{{ $user->id ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Registration Date</th>
                <td>{{ $user->created_at->format('F j, Y \a\t g:i A') }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td><span class="badge badge-success">{{ ucfirst($user->status ?? 'active') }}</span></td>
            </tr>
            <tr>
                <th>Role</th>
                <td><span class="badge">{{ ucfirst($user->role ?? 'customer') }}</span></td>
            </tr>
            <tr>
                <th>Referral Code</th>
                <td><strong>{{ $user->referral_code ?? 'N/A' }}</strong></td>
            </tr>
        </table>

        @if($sponsor)
        <h3>🤝 Sponsor Information</h3>
        <table class="info-table">
            <tr>
                <th>Sponsor Name</th>
                <td>{{ $sponsor->firstname }} {{ $sponsor->lastname }}</td>
            </tr>
            <tr>
                <th>Sponsor Username</th>
                <td><strong>{{ $sponsor->username }}</strong></td>
            </tr>
            <tr>
                <th>Sponsor Email</th>
                <td>{{ $sponsor->email }}</td>
            </tr>
            <tr>
                <th>Position</th>
                <td><span class="badge badge-warning">{{ ucfirst($user->position ?? 'N/A') }}</span></td>
            </tr>
            <tr>
                <th>Placement Type</th>
                <td>{{ ucfirst($user->placement_type ?? 'Auto') }}</td>
            </tr>
        </table>
        @else
        <div class="alert alert-info">
            <strong>ℹ️ Note:</strong> This user registered without a sponsor (direct registration).
        </div>
        @endif

        @if($user->address ?? false)
        <h3>📍 Address Information</h3>
        <table class="info-table">
            <tr>
                <th>Address</th>
                <td>{{ $user->address }}</td>
            </tr>
            @if($user->country ?? false)
            <tr>
                <th>Country</th>
                <td>{{ $user->country }}</td>
            </tr>
            @endif
        </table>
        @endif

        <h3>⚡ Quick Actions</h3>
        <div style="text-align: center; margin: 20px 0;">
            <a href="{{ $user_details_url ?? '#' }}" class="btn btn-success">View User Details</a>
            <a href="{{ $admin_dashboard_url }}" class="btn">Go to Admin Dashboard</a>
        </div>

        <div class="alert alert-info">
            <h4>📊 System Statistics</h4>
            <p>This registration brings your total user count to a new milestone. You can view detailed analytics and user management tools in your admin dashboard.</p>
        </div>

        <h3>🔧 Recommended Actions</h3>
        <ul>
            <li>Review the new user's profile for completeness</li>
            <li>Send a welcome message if needed</li>
            <li>Monitor user activity for engagement</li>
            <li>Check if any manual verification is required</li>
            @if($sponsor)
            <li>Verify the sponsor-referral relationship is correct</li>
            <li>Update MLM tree structure if needed</li>
            @endif
        </ul>

        <p><strong>Platform:</strong> {{ $site_name }}<br>
        <strong>Time:</strong> {{ now()->format('F j, Y \a\t g:i A T') }}</p>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ $site_name }} Admin System. All rights reserved.</p>
        <p>This is an automated administrative notification.</p>
    </div>
</body>
</html>
