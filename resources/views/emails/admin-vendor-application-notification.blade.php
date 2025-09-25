<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Vendor Application - {{ $site_name }}</title>
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
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
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
        .btn-danger {
            background: #dc3545;
        }
        .btn-danger:hover {
            background: #c82333;
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
        .alert-warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
        }
        .alert-info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            background: #ffc107;
            color: #212529;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        .business-description {
            background: #e7f3ff;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin: 15px 0;
            border-radius: 0 5px 5px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏪 New Vendor Application</h1>
        <p>A new vendor application requires review</p>
    </div>

    <div class="content">
        <div class="alert alert-warning">
            <strong>📢 Action Required!</strong><br>
            A new vendor application has been submitted and is awaiting your review and approval.
        </div>

        <h3>🏢 Business Information</h3>
        <table class="info-table">
            <tr>
                <th>Application ID</th>
                <td><strong>#{{ $application->id }}</strong></td>
            </tr>
            <tr>
                <th>Business Name</th>
                <td><strong>{{ $application->business_name }}</strong></td>
            </tr>
            <tr>
                <th>Contact Person</th>
                <td>{{ $application->contact_person }}</td>
            </tr>
            <tr>
                <th>Email Address</th>
                <td>{{ $application->email }}</td>
            </tr>
            <tr>
                <th>Phone Number</th>
                <td>{{ $application->phone }}</td>
            </tr>
            @if($application->website)
            <tr>
                <th>Website</th>
                <td><a href="{{ $application->website }}" target="_blank">{{ $application->website }}</a></td>
            </tr>
            @endif
            <tr>
                <th>Application Date</th>
                <td>{{ $application->created_at->format('F j, Y \a\t g:i A') }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td><span class="badge">{{ ucfirst($application->status) }}</span></td>
            </tr>
        </table>

        <h3>👤 Applicant Information</h3>
        <table class="info-table">
            <tr>
                <th>User ID</th>
                <td>#{{ $application->user->id }}</td>
            </tr>
            <tr>
                <th>Full Name</th>
                <td>{{ $application->user->firstname }} {{ $application->user->lastname }}</td>
            </tr>
            <tr>
                <th>Username</th>
                <td>{{ $application->user->username }}</td>
            </tr>
            <tr>
                <th>User Email</th>
                <td>{{ $application->user->email }}</td>
            </tr>
            <tr>
                <th>User Phone</th>
                <td>{{ $application->user->phone ?? 'Not provided' }}</td>
            </tr>
            <tr>
                <th>Registration Date</th>
                <td>{{ $application->user->created_at->format('F j, Y') }}</td>
            </tr>
            <tr>
                <th>Current Role</th>
                <td>{{ ucfirst($application->user->role) }}</td>
            </tr>
        </table>

        <h3>📝 Business Description</h3>
        <div class="business-description">
            <strong>What they plan to sell and their business model:</strong>
            <p>{{ $application->business_description }}</p>
        </div>

        <h3>⚡ Quick Actions</h3>
        <div style="text-align: center; margin: 20px 0;">
            <a href="{{ $application_details_url ?? '#' }}" class="btn btn-success">Review Application</a>
            <a href="{{ $admin_dashboard_url }}" class="btn">Go to Admin Dashboard</a>
        </div>

        <div class="alert alert-info">
            <h4>📋 Review Checklist</h4>
            <ul>
                <li>Verify business information is complete and accurate</li>
                <li>Check if the business model aligns with platform policies</li>
                <li>Review the business description for compliance</li>
                <li>Validate contact information and website (if provided)</li>
                <li>Check the user's account history and status</li>
                <li>Ensure no duplicate applications exist</li>
            </ul>
        </div>

        <div class="alert alert-warning">
            <h4>⏰ Action Timeline</h4>
            <p><strong>Target Response Time:</strong> 24-48 hours</p>
            <p>The applicant expects to hear back within this timeframe. Please review and respond promptly to maintain good vendor relations.</p>
        </div>

        <h3>📊 System Information</h3>
        <ul>
            <li><strong>Platform:</strong> {{ $site_name }} Admin Panel</li>
            <li><strong>Notification Time:</strong> {{ now()->format('F j, Y \a\t g:i A T') }}</li>
            <li><strong>Application IP:</strong> {{ request()->ip() ?? 'Not available' }}</li>
            <li><strong>User Agent:</strong> {{ request()->userAgent() ?? 'Not available' }}</li>
        </ul>

        <p><strong>Next Steps:</strong></p>
        <ol>
            <li>Review the application details thoroughly</li>
            <li>Approve or reject the application with appropriate notes</li>
            <li>The applicant will automatically receive an email notification</li>
            <li>If approved, the user's role will be updated to 'vendor'</li>
        </ol>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ $site_name }} Admin System. All rights reserved.</p>
        <p>This is an automated administrative notification.</p>
    </div>
</body>
</html>
