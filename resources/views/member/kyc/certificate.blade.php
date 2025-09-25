<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KYC Verification Certificate</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 40px;
            background: #f8f9fa;
        }
        .certificate {
            background: white;
            padding: 60px;
            border: 8px solid #007bff;
            border-radius: 10px;
            text-align: center;
            position: relative;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .certificate::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;
            border: 2px solid #28a745;
            border-radius: 5px;
        }
        .header {
            margin-bottom: 40px;
        }
        .logo {
            font-size: 36px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        .title {
            font-size: 32px;
            font-weight: bold;
            color: #333;
            margin: 30px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .subtitle {
            font-size: 18px;
            color: #666;
            margin-bottom: 40px;
        }
        .content {
            margin: 40px 0;
            line-height: 1.8;
        }
        .user-info {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
            margin: 30px 0;
            border-left: 5px solid #28a745;
        }
        .user-name {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
        }
        .user-details {
            font-size: 14px;
            color: #666;
            text-align: left;
        }
        .user-details div {
            margin: 8px 0;
        }
        .verification-info {
            margin: 30px 0;
            padding: 20px;
            background: #e8f5e8;
            border-radius: 8px;
        }
        .verification-id {
            font-size: 14px;
            color: #666;
            margin: 10px 0;
        }
        .footer {
            margin-top: 60px;
            font-size: 12px;
            color: #888;
        }
        .stamp {
            position: absolute;
            top: 50px;
            right: 50px;
            background: #28a745;
            color: white;
            padding: 15px;
            border-radius: 50%;
            font-weight: bold;
            font-size: 12px;
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            line-height: 1.2;
        }
        .date {
            font-weight: bold;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="stamp">
            VERIFIED<br>
            {{ $kyc->verified_at ? $kyc->verified_at->format('Y') : date('Y') }}
        </div>
        
        <div class="header">
            <div class="logo">{{ config('app.name', 'OSmart BD') }}</div>
            <div class="title">Certificate of KYC Verification</div>
            <div class="subtitle">Know Your Customer Identity Verification</div>
        </div>
        
        <div class="content">
            <p style="font-size: 16px; margin-bottom: 30px;">
                This is to certify that the following individual has successfully completed 
                our comprehensive Know Your Customer (KYC) verification process and their 
                identity has been verified according to our security standards.
            </p>
            
            <div class="user-info">
                <div class="user-name">{{ $user->name }}</div>
                <div class="user-details">
                    <div><strong>User ID:</strong> {{ $user->id }}</div>
                    <div><strong>Email:</strong> {{ $user->email }}</div>
                    <div><strong>Phone:</strong> {{ $user->phone ?? 'N/A' }}</div>
                    @if($kyc->document_type && $kyc->document_number)
                    <div><strong>Document Type:</strong> {{ ucfirst(str_replace('_', ' ', $kyc->document_type)) }}</div>
                    <div><strong>Document Number:</strong> {{ $kyc->document_number }}</div>
                    @endif
                    @if($kyc->address)
                    <div><strong>Address:</strong> {{ $kyc->address }}</div>
                    @endif
                </div>
            </div>
            
            <div class="verification-info">
                <div style="font-size: 16px; font-weight: bold; color: #28a745; margin-bottom: 10px;">
                    ✓ Identity Verification Completed
                </div>
                <div class="verification-id">
                    Verification ID: KYC-{{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}-{{ $kyc->created_at->format('Ymd') }}
                </div>
                <div class="verification-id">
                    Verified On: <span class="date">{{ $kyc->verified_at ? $kyc->verified_at->format('F d, Y \a\t g:i A') : 'Processing' }}</span>
                </div>
            </div>
            
            <p style="font-size: 14px; margin-top: 40px; color: #666;">
                This certificate validates that the holder has provided authentic documentation 
                and their identity has been verified through our secure verification process. 
                This certificate is digitally generated and is valid for official purposes.
            </p>
        </div>
        
        <div class="footer">
            <p>Generated on {{ now()->format('F d, Y \a\t g:i A') }}</p>
            <p>{{ config('app.name', 'OSmart BD') }} - Digital Identity Verification System</p>
            <p style="font-size: 10px; margin-top: 20px;">
                This is a computer-generated certificate and does not require a physical signature.
            </p>
        </div>
    </div>
</body>
</html>