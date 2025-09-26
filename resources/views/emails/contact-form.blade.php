<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .email-body {
            padding: 30px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 10px;
            margin-bottom: 20px;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
        }
        .info-label {
            font-weight: bold;
            color: #666;
        }
        .info-value {
            color: #333;
        }
        .message-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .message-section h3 {
            margin-top: 0;
            color: #333;
        }
        .message-content {
            background: white;
            padding: 15px;
            border-radius: 3px;
            border-left: 4px solid #667eea;
        }
        .email-footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
        .reply-info {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            border-left: 4px solid #2196f3;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>🔔 New Contact Form Submission</h1>
            <p>Someone has contacted you through your website</p>
        </div>
        
        <div class="email-body">
            <div class="info-grid">
                <div class="info-label">Name:</div>
                <div class="info-value">{{ $name }}</div>
                
                <div class="info-label">Email:</div>
                <div class="info-value">{{ $email }}</div>
                
                @if($phone)
                <div class="info-label">Phone:</div>
                <div class="info-value">{{ $phone }}</div>
                @endif
                
                <div class="info-label">Subject:</div>
                <div class="info-value">{{ ucfirst($subject) }} Inquiry</div>
                
                <div class="info-label">Newsletter Subscription:</div>
                <div class="info-value">
                    @if($subscribe_newsletter)
                        <span style="color: #28a745; font-weight: bold;">✓ Yes - Wants to subscribe</span>
                    @else
                        <span style="color: #6c757d;">✗ No</span>
                    @endif
                </div>
                
                <div class="info-label">Reference ID:</div>
                <div class="info-value" style="font-family: monospace; background: #f8f9fa; padding: 2px 6px; border-radius: 3px;">{{ $reference_id }}</div>
                
                <div class="info-label">Submitted:</div>
                <div class="info-value">{{ $submitted_at }}</div>
            </div>
            
            <div class="message-section">
                <h3>💬 Message</h3>
                <div class="message-content">
                    {!! nl2br(e($message)) !!}
                </div>
            </div>
            
            <div class="reply-info">
                <strong>💡 Quick Reply:</strong> You can reply directly to this email to respond to {{ $name }} at {{ $email }}
            </div>
        </div>
        
        <div class="email-footer">
            <p>This message was sent from your website contact form.</p>
            <p>{{ config('app.name') }} | Contact Form System</p>
        </div>
    </div>
</body>
</html>
