<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reply from {{ $company_name }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .email-body {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            color: #667eea;
            margin-bottom: 20px;
        }
        .original-inquiry {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #dee2e6;
            margin: 20px 0;
        }
        .original-inquiry h4 {
            margin-top: 0;
            color: #495057;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .reply-content {
            background: #e8f4fd;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
            margin: 20px 0;
        }
        .reply-content h4 {
            margin-top: 0;
            color: #667eea;
            font-size: 16px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 10px 20px;
            margin-bottom: 15px;
            font-size: 14px;
        }
        .info-label {
            font-weight: bold;
            color: #666;
            white-space: nowrap;
        }
        .info-value {
            color: #333;
        }
        .reference-id {
            font-family: monospace;
            background: #f1f3f4;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
        }
        .contact-info {
            background: #fff3cd;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #ffc107;
            margin: 20px 0;
        }
        .contact-method {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .contact-icon {
            font-size: 20px;
            margin-right: 15px;
            width: 30px;
        }
        .email-footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
            border-top: 1px solid #dee2e6;
        }
        .signature {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #666;
        }
        @media (max-width: 600px) {
            .email-container {
                margin: 10px;
                border-radius: 5px;
            }
            .email-header, .email-body, .email-footer {
                padding: 20px;
            }
            .info-grid {
                grid-template-columns: 1fr;
                gap: 5px;
            }
            .contact-method {
                flex-direction: column;
                align-items: flex-start;
                text-align: left;
            }
            .contact-icon {
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <div class="logo">{{ $company_name }}</div>
            <p>Thank you for contacting us</p>
        </div>
        
        <div class="email-body">
            <div class="greeting">
                Hello {{ $customer_name }},
            </div>
            
            <p>Thank you for reaching out to us. We have reviewed your inquiry and are pleased to provide you with the following response:</p>
            
            <!-- Original Inquiry Reference -->
            <div class="original-inquiry">
                <h4>Your Original Inquiry</h4>
                <div class="info-grid">
                    <div class="info-label">Subject:</div>
                    <div class="info-value">{{ ucfirst($original_subject) }} Inquiry</div>
                    
                    <div class="info-label">Date:</div>
                    <div class="info-value">{{ $original_date }}</div>
                    
                    <div class="info-label">Reference:</div>
                    <div class="info-value"><span class="reference-id">{{ $reference_id }}</span></div>
                </div>
                <p><strong>Your Message:</strong></p>
                <p style="font-style: italic; color: #666;">"{{ $original_message }}"</p>
            </div>
            
            <!-- Admin Reply -->
            <div class="reply-content">
                <h4>Our Response</h4>
                <div style="white-space: pre-line;">{{ $reply_message }}</div>
            </div>
            
            <!-- Contact Information for Follow-up -->
            <div class="contact-info">
                <h4 style="margin-top: 0; color: #856404;">Need Further Assistance?</h4>
                <p>If you have any additional questions or need further clarification, please don't hesitate to reach out to us:</p>
                
                <div class="contact-method">
                    <div class="contact-icon">📧</div>
                    <div>
                        <strong>Email Us</strong><br>
                        <a href="mailto:{{ config('mail.from.address') }}" style="color: #667eea; text-decoration: none;">{{ config('mail.from.address') }}</a>
                    </div>
                </div>
                
                <div class="contact-method">
                    <div class="contact-icon">📞</div>
                    <div>
                        <strong>Call Us</strong><br>
                        <a href="tel:+8801816396271" style="color: #667eea; text-decoration: none;">+88 01816-396271</a> (Business Hours)
                    </div>
                </div>
                
                <div class="contact-method">
                    <div class="contact-icon">🌐</div>
                    <div>
                        <strong>Visit Our Website</strong><br>
                        <a href="{{ config('app.url') }}" style="color: #667eea; text-decoration: none;">{{ config('app.url') }}</a>
                    </div>
                </div>
                
                <p><strong>Reference ID:</strong> <span class="reference-id">{{ $reference_id }}</span><br>
                <small>Please include this reference ID in any future correspondence.</small></p>
            </div>
            
            <!-- Signature -->
            <div class="signature">
                <p>Best regards,<br>
                <strong>{{ $admin_name }}</strong><br>
                Customer Support Team<br>
                {{ $company_name }}</p>
            </div>
        </div>
        
        <div class="email-footer">
            <p>This email was sent in response to your inquiry submitted through our website.</p>
            <p>{{ $company_name }} | Professional Customer Support</p>
            <p style="font-size: 12px; color: #999;">
                You received this email because you contacted us through our website. 
                If you believe this email was sent in error, please contact us immediately.
            </p>
        </div>
    </div>
</body>
</html>