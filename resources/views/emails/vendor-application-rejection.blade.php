<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>❌ Vendor Application Status - {{ $site_name }}</title>
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
            margin: 10px 0;
            font-weight: bold;
        }
        .btn:hover {
            background: #0056b3;
        }
        .btn-warning {
            background: #fd7e14;
        }
        .btn-warning:hover {
            background: #e85d04;
        }
        .rejection-box {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
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
        .warning-box {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .improvement-steps {
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
            background: #007bff;
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
            color: #dc3545;
            font-weight: bold;
        }
        .requirements-list {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 20px;
            margin: 20px 0;
        }
        .requirement-item {
            padding: 10px 0;
            border-bottom: 1px solid #f8f9fa;
        }
        .requirement-item:last-child {
            border-bottom: none;
        }
        .icon-rejection {
            font-size: 48px;
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="icon-rejection">❌</div>
        <h1>Application Update</h1>
        <p>Vendor application status notification</p>
    </div>

    <div class="content">
        <h2>Hello {{ $application->contact_person }},</h2>
        
        <div class="rejection-box">
            <h3 style="margin-top: 0; color: #dc3545;">Application Not Approved</h3>
            <p style="margin-bottom: 0; font-size: 18px;">
                Thank you for your interest in becoming a vendor on <strong>{{ $site_name }}</strong>. Unfortunately, your application for <strong>{{ $application->business_name }}</strong> does not meet our current requirements.
            </p>
        </div>

        <p>We appreciate the time and effort you put into your application. This decision allows us to maintain the high quality standards that our customers expect from {{ $site_name }} vendors.</p>

        <div class="application-details">
            <h3>📋 Application Review Details</h3>
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
                <span class="detail-label">Review Date:</span>
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
        <div class="warning-box">
            <h4>📝 Review Notes</h4>
            <p><strong>Feedback from our review team:</strong></p>
            <p>{{ $application->admin_notes }}</p>
        </div>
        @endif

        <div class="info-box">
            <h3>🔄 You Can Reapply!</h3>
            <p>This decision doesn't prevent you from reapplying in the future. We encourage you to review our vendor requirements and submit a new application when you're ready.</p>
        </div>

        <div class="improvement-steps">
            <h3>💡 How to Improve Your Next Application</h3>
            
            <div class="step">
                <div class="step-number">1</div>
                <div>
                    <strong>Review Vendor Requirements</strong><br>
                    Carefully read through our updated vendor guidelines and ensure you meet all criteria.
                </div>
            </div>
            
            <div class="step">
                <div class="step-number">2</div>
                <div>
                    <strong>Improve Business Documentation</strong><br>
                    Ensure all business licenses, certifications, and legal documents are current and complete.
                </div>
            </div>
            
            <div class="step">
                <div class="step-number">3</div>
                <div>
                    <strong>Enhance Product Quality</strong><br>
                    Focus on product quality, descriptions, and professional presentation of your offerings.
                </div>
            </div>
            
            <div class="step">
                <div class="step-number">4</div>
                <div>
                    <strong>Build Your Online Presence</strong><br>
                    Develop a professional website and social media presence to demonstrate your business credibility.
                </div>
            </div>
            
            <div class="step">
                <div class="step-number">5</div>
                <div>
                    <strong>Address Specific Feedback</strong><br>
                    If provided, carefully address the specific concerns mentioned in the review notes above.
                </div>
            </div>
        </div>

        <div class="requirements-list">
            <h3>📋 Current Vendor Requirements</h3>
            <div class="requirement-item">
                <strong>✓ Business Registration:</strong> Valid business license and registration documents
            </div>
            <div class="requirement-item">
                <strong>✓ Product Quality:</strong> High-quality products with detailed descriptions and images
            </div>
            <div class="requirement-item">
                <strong>✓ Professional Presentation:</strong> Professional business profile and product catalog
            </div>
            <div class="requirement-item">
                <strong>✓ Compliance:</strong> Adherence to all platform policies and legal requirements
            </div>
            <div class="requirement-item">
                <strong>✓ Customer Service:</strong> Demonstrated ability to provide excellent customer support
            </div>
            <div class="requirement-item">
                <strong>✓ Reliability:</strong> Proven track record of business operations and reliability
            </div>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $vendor_requirements_url }}" class="btn">View Vendor Requirements</a>
            <br><br>
            <a href="{{ $reapply_url }}" class="btn btn-warning">Submit New Application</a>
        </div>

        <div class="info-box">
            <h3>📞 Need Clarification?</h3>
            <p>If you have questions about this decision or need guidance for your next application:</p>
            <ul>
                <li><strong>Email:</strong> {{ $admin_email }}</li>
                <li><strong>Subject:</strong> Vendor Application Inquiry - #{{ $application->id }}</li>
                <li><strong>Response Time:</strong> Within 48 hours</li>
                <li><strong>Business Hours:</strong> Monday - Friday, 9:00 AM - 6:00 PM</li>
            </ul>
        </div>

        <div class="warning-box">
            <h4>⏰ Reapplication Timeline</h4>
            <p>You may submit a new application immediately. However, we recommend taking time to address the areas for improvement mentioned above to increase your chances of approval.</p>
        </div>

        <p>We genuinely appreciate your interest in partnering with {{ $site_name }}. While your current application wasn't approved, we hope to see an improved application from you in the future.</p>

        <p>Thank you for understanding our decision.</p>

        <p>Best regards,<br>
        <strong>The {{ $site_name }} Vendor Review Team</strong></p>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ $site_name }}. All rights reserved.</p>
        <p>Maintaining quality standards for our vendor community</p>
    </div>
</body>
</html>
