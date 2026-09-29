<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Court Reserve - Verification Code</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
            color: #333333;
        }
        .email-wrapper {
            width: 100%;
            background-color: #f4f7f6;
            padding: 40px 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }
        .header {
            background-color: #0d6efd;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            letter-spacing: 1px;
        }
        .body {
            padding: 40px 30px;
            text-align: center;
        }
        .body h2 {
            font-size: 20px;
            margin-top: 0;
            color: #444444;
        }
        .body p {
            font-size: 16px;
            line-height: 1.6;
            color: #666666;
            margin-bottom: 25px;
        }
        .code-box {
            display: inline-block;
            background-color: #f8f9fa;
            border: 2px dashed #0d6efd;
            border-radius: 8px;
            padding: 15px 30px;
            margin: 10px 0 25px;
        }
        .code-box span {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 8px;
            color: #0d6efd;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #999999;
            border-top: 1px solid #eeeeee;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <div class="header">
                <h1>Court Reserve</h1>
            </div>
            <div class="body">
                <h2>Verify Your Email Address</h2>
                <p>Hello,</p>
                <p>Thank you for signing up or requesting a verification code. Please use the following One-Time Password (OTP) to complete your process. This code is valid for 3 minutes.</p>
                
                <div class="code-box">
                    <span>{{ $code }}</span>
                </div>
                
                <p>If you did not request this, you can safely ignore this email.</p>
            </div>
            <div class="footer">
                &copy; {{ date('Y') }} Court Reserve. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
