<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>🎉 Vendor Application Approved - {{ $site_name }}</title>
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
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
            font-weight: bold;
        }
        .btn:hover {
            background: #1e7e34;
        }
        .btn-secondary {
            background: #007bff;
        }
        .btn-secondary:hover {
            background: #0056b3;
        }
        .success-box {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
        }
        .info-box {
            background: #e7f3ff;
            border: 1px solid #b3d7ff;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .welcome-steps {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 20px;
            margin: 20px 0;
        }
        .step {
            display: flex;
            align-items: center;
            margin: 15px 0;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .step-number {
            background: #28a745;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 15px;
            flex-shrink: 0;
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
            color: #28a745;
            font-weight: bold;
        }
        .celebration {
            font-size: 48px;
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="celebration">🎉</div>
        <h1>Congratulations!</h1>
        <p>Your vendor application has been approved</p>
    </div>

    <div class="content">
        <h2>Hello {{ $application->contact_person }}!</h2>
        
        <div class="success-box">
            <h3 style="margin-top: 0; color: #28a745;">✅ Application Approved!</h3>
            <p style="margin-bottom: 0; font-size: 18px;">
                Welcome to the <strong>{{ $site_name }}</strong> vendor community! Your application for <strong>{{ $application->business_name }}</strong> has been approved.
            </p>
        </div>

        <p>We're excited to have you join our platform as an official vendor partner. You can now access your vendor dashboard and start setting up your store.</p>

        <div class="application-details">
            <h3>📋 Approved Application Details</h3>
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
                <span class="detail-label">Approval Date:</span>
                <span>{{ $application->reviewed_at->format('F j, Y \a\t g:i A') }}</span>
            </div>
            @if($application->reviewer)
            <div class="detail-row">
                <span class="detail-label">Reviewed By:</span>
                <span>{{ $application->reviewer->name ?? 'Admin Team' }}</span>
            </div>
            @endif
        </div>

        @if($application->admin_notes)
        <div class="info-box">
            <h4>📝 Admin Notes</h4>
            <p>{{ $application->admin_notes }}</p>
        </div>
        @endif

        <div class="welcome-steps">
            <h3>🚀 Getting Started - Next Steps</h3>
            
            <div class="step">
                <div class="step-number">1</div>
                <div>
                    <strong>Access Your Vendor Dashboard</strong><br>
                    Login to your vendor account to access your dedicated dashboard and management tools.
                </div>
            </div>
            
            <div class="step">
                <div class="step-number">2</div>
                <div>
                    <strong>Complete Your Store Profile</strong><br>
                    Add your store information, logo, banner, and business details to create an attractive storefront.
                </div>
            </div>
            
            <div class="step">
                <div class="step-number">3</div>
                <div>
                    <strong>Add Your First Products</strong><br>
                    Start uploading your products with detailed descriptions, images, and pricing information.
                </div>
            </div>
            
            <div class="step">
                <div class="step-number">4</div>
                <div>
                    <strong>Configure Payment & Shipping</strong><br>
                    Set up your payment methods and shipping options to start receiving orders.
                </div>
            </div>
            
            <div class="step">
                <div class="step-number">5</div>
                <div>
                    <strong>Go Live!</strong><br>
                    Once everything is set up, your store will be live and ready to receive customers.
                </div>
            </div>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $vendor_dashboard_url }}" class="btn">Access Vendor Dashboard</a>
            <br><br>
            <a href="{{ $vendor_login_url }}" class="btn btn-secondary">Login to Your Account</a>
        </div>

        <div class="info-box">
            <h3>📚 Vendor Resources</h3>
            <ul>
                <li><strong>Vendor Guide:</strong> Complete setup and management guide</li>
                <li><strong>Product Guidelines:</strong> Best practices for product listings</li>
                <li><strong>Commission Structure:</strong> Understanding your earnings</li>
                <li><strong>Support Center:</strong> 24/7 vendor support available</li>
                <li><strong>Marketing Tools:</strong> Promote your products effectively</li>
            </ul>
        </div>

        <div class="info-box">
            <h3>📞 Vendor Support</h3>
            <p>Our dedicated vendor support team is here to help you succeed:</p>
            <ul>
                <li><strong>Email:</strong> {{ $admin_email }}</li>
                <li><strong>Response Time:</strong> Within 24 hours</li>
                <li><strong>Business Hours:</strong> Monday - Friday, 9:00 AM - 6:00 PM</li>
                <li><strong>Emergency Support:</strong> Available for critical issues</li>
            </ul>
        </div>

        <div class="success-box">
            <h4>🎯 Your Success is Our Success!</h4>
            <p>We're committed to helping you build a thriving business on our platform. Take advantage of all our tools and resources to maximize your sales potential.</p>
        </div>

        <p>Once again, congratulations on becoming a {{ $site_name }} vendor! We look forward to a successful partnership.</p>

        <p>Best regards,<br>
        <strong>The {{ $site_name }} Vendor Team</strong></p>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ $site_name }}. All rights reserved.</p>
        <p>Welcome to the {{ $site_name }} vendor family!</p>
    </div>
</body>
</html>
