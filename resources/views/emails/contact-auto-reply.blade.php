<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank you for contacting OSmart</title>
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
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .email-body {
            padding: 40px;
        }
        .greeting {
            font-size: 20px;
            color: #28a745;
            margin-bottom: 20px;
        }
        .info-box {
            background: #e8f5e8;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #28a745;
            margin: 20px 0;
        }
        .contact-info {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            margin: 25px 0;
        }
        .contact-method {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .contact-method:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        .contact-icon {
            width: 40px;
            height: 40px;
            background: #28a745;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-weight: bold;
        }
        .footer {
            background: #f8f9fa;
            padding: 30px 40px;
            border-top: 1px solid #e9ecef;
            font-size: 14px;
            color: #6c757d;
            text-align: center;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            margin: 10px 0;
        }
        .social-links {
            margin-top: 20px;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #6c757d;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <div class="logo">OSmart</div>
            <h1 style="margin: 0; font-size: 24px;">Thank You for Contacting Us!</h1>
            <p style="margin: 10px 0 0; opacity: 0.9;">We've received your message and will respond soon</p>
        </div>
        
        <div class="email-body">
            <div class="greeting">Hello {{ $name }}!</div>
            
            <p>Thank you for reaching out to us. We have successfully received your inquiry regarding <strong>{{ ucfirst(str_replace('_', ' ', $subject)) }}</strong> and appreciate you taking the time to contact us.</p>
            
            <div class="info-box">
                <h4 style="margin-top: 0; color: #28a745;">📋 Your Message Summary</h4>
                <p><strong>Submitted:</strong> {{ $submitted_at }}</p>
                <p><strong>Subject:</strong> {{ ucfirst(str_replace('_', ' ', $subject)) }}</p>
                <p><strong>Contact Email:</strong> {{ $email }}</p>
                @if($phone)
                <p><strong>Phone:</strong> {{ $phone }}</p>
                @endif
            </div>
            
            <h4>What Happens Next?</h4>
            <ul style="color: #495057; padding-left: 20px;">
                <li>Our team will review your message within <strong>2-4 hours</strong></li>
                <li>You'll receive a detailed response within <strong>24 hours</strong> on business days</li>
                <li>For urgent matters, feel free to call us directly</li>
                @if($subscribe_newsletter)
                <li>You'll receive our newsletter with updates and special offers</li>
                @endif
            </ul>
            
            <div class="contact-info">
                <h4 style="margin-top: 0; margin-bottom: 20px;">Need Immediate Assistance?</h4>
                
                <div class="contact-method">
                    <div class="contact-icon">📞</div>
                    <div>
                        <strong>Call Us</strong><br>
                        <a href="tel:+8801816396271" style="color: #28a745; text-decoration: none;">+88 01816-396271</a> (24/7 Support)
                    </div>
                </div>
                
                <div class="contact-method">
                    <div class="contact-icon">✉️</div>
                    <div>
                        <strong>Email Us</strong><br>
                        <a href="mailto:info@osmartbd.com" style="color: #28a745; text-decoration: none;">info@osmartbd.com</a>
                    </div>
                </div>
                
                <div class="contact-method">
                    <div class="contact-icon">🕐</div>
                    <div>
                        <strong>Business Hours</strong><br>
                        Saturday - Thursday: 9:00 AM - 7:00 PM<br>
                        Friday: 2:00 PM - 7:00 PM
                    </div>
                </div>
            </div>
            
            <p>While you wait, feel free to explore our website and check out our latest products and offers.</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/') }}" class="btn">Visit Our Website</a>
                <a href="{{ url('/shop') }}" class="btn" style="background: #17a2b8;">Browse Products</a>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>OSmart Bangladesh</strong></p>
            <p>Your trusted partner for quality products and exceptional service</p>
            
            <div style="margin: 20px 0;">
                <p><strong>Our Locations:</strong></p>
                <p>🏢 Dhaka | 🏢 Cumilla | 🏢 Rajshahi</p>
            </div>
            
            <div class="social-links">
                <a href="#" style="margin: 0 10px;">Facebook</a>
                <a href="#" style="margin: 0 10px;">Instagram</a>
                <a href="#" style="margin: 0 10px;">WhatsApp</a>
                <a href="#" style="margin: 0 10px;">LinkedIn</a>
            </div>
            
            <hr style="border: none; border-top: 1px solid #e9ecef; margin: 20px 0;">
            <p style="margin: 0; font-size: 12px;">This is an automated message. Please do not reply to this email.</p>
            <p style="margin: 5px 0 0; font-size: 12px;">If you have any questions, please contact us at info@osmart.com.bd</p>
        </div>
    </div>
</body>
</html>