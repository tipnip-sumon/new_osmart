<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vendor Application Received - {{ $site_name }}</title>
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
            background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%);
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
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .success-box {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .application-details {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 20px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f8f9fa;
        }
        .detail-label {
            font-weight: bold;
            color: #495057;
        }
        .highlight {
            color: #6f42c1;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📋 Application Received!</h1>
        <p>Thank you for your vendor application</p>
    </div>

    <div class="content">
        <h2>Hello {{ $application->contact_person }}!</h2>
        
        <div class="success-box">
            <strong>✅ Application Successfully Submitted!</strong><br>
            We have received your vendor application for <strong>{{ $application->business_name }}</strong> and are excited to review your submission.
        </div>

        <p>Thank you for your interest in becoming a vendor partner with <strong>{{ $site_name }}</strong>. Your application is now under review by our team.</p>

        <div class="application-details">
            <h3>📊 Application Summary</h3>
            <div class="detail-row">
                <span class="detail-label">Application ID:</span>
                <span class="highlight">#{{ $application->id }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Business Name:</span>
                <span>{{ $application->business_name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Contact Person:</span>
                <span>{{ $application->contact_person }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Email:</span>
                <span>{{ $application->email }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Phone:</span>
                <span>{{ $application->phone }}</span>
            </div>
            @if($application->website)
            <div class="detail-row">
                <span class="detail-label">Website:</span>
                <span>{{ $application->website }}</span>
            </div>
            @endif
            <div class="detail-row">
                <span class="detail-label">Submission Date:</span>
                <span>{{ $application->created_at->format('F j, Y \a\t g:i A') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status:</span>
                <span class="highlight">{{ ucfirst($application->status) }}</span>
            </div>
        </div>

        <div class="info-box">
            <h3>⏰ What Happens Next?</h3>
            <ul>
                <li><strong>Review Process:</strong> Our team will carefully review your application within 24-48 hours</li>
                <li><strong>Verification:</strong> We may contact you for additional information if needed</li>
                <li><strong>Decision:</strong> You'll receive an email notification with our decision</li>
                <li><strong>Onboarding:</strong> If approved, we'll guide you through the vendor setup process</li>
            </ul>
        </div>

        <div class="info-box">
            <h3>📝 Application Details Submitted</h3>
            <p><strong>Business Description:</strong></p>
            <p style="background: white; padding: 10px; border-radius: 3px; border-left: 4px solid #6f42c1;">
                {{ $application->business_description }}
            </p>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $vendor_login_url }}" class="btn">Login to Your Account</a>
        </div>

        <div class="info-box">
            <h3>📞 Need Help?</h3>
            <p>If you have any questions about your application or need to make changes, please contact our support team:</p>
            <ul>
                <li><strong>Email:</strong> {{ $admin_email }}</li>
                <li><strong>Response Time:</strong> We typically respond within 24 hours</li>
                <li><strong>Business Hours:</strong> Monday - Friday, 9:00 AM - 6:00 PM</li>
            </ul>
        </div>

        <p><strong>Important:</strong> Please keep this email for your records. You can reference your application ID (#{{ $application->id }}) in any future communications.</p>

        <p>Thank you for choosing {{ $site_name }} as your business partner. We look forward to potentially welcoming you to our vendor community!</p>

        <p>Best regards,<br>
        <strong>The {{ $site_name }} Vendor Team</strong></p>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ $site_name }}. All rights reserved.</p>
        <p>This is an automated confirmation email. Please do not reply to this message.</p>
    </div>
</body>
</html>
