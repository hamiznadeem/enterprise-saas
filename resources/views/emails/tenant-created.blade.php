<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f4f5; padding: 40px 20px; }
        .container { max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #2563eb, #1d4ed8); padding: 32px; text-align: center; }
        .header h1 { color: #ffffff; font-size: 22px; font-weight: 700; }
        .header p { color: #bfdbfe; font-size: 14px; margin-top: 6px; }
        .body { padding: 32px; }
        .greeting { font-size: 16px; color: #1e293b; margin-bottom: 20px; }
        .greeting strong { color: #2563eb; }
        .info-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
        .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #64748b; }
        .info-value { color: #1e293b; font-weight: 600; }
        .creds-box { background: #fffbeb; border: 2px solid #fde68a; border-radius: 8px; padding: 20px; margin-bottom: 24px; }
        .creds-box h3 { color: #92400e; font-size: 14px; font-weight: 700; margin-bottom: 12px; display: flex; align-items: center; gap: 6px; }
        .cred-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 12px; background: #ffffff; border-radius: 6px; margin-bottom: 8px; border: 1px solid #fde68a; }
        .cred-row:last-child { margin-bottom: 0; }
        .cred-label { font-size: 13px; color: #78716c; font-weight: 500; }
        .cred-value { font-size: 14px; color: #1e293b; font-family: 'Consolas', monospace; font-weight: 600; }
        .login-btn { display: block; width: 100%; padding: 14px; background: #2563eb; color: #ffffff; text-align: center; text-decoration: none; border-radius: 8px; font-size: 15px; font-weight: 600; }
        .login-btn:hover { background: #1d4ed8; }
        .footer { padding: 24px 32px; background: #f8fafc; border-top: 1px solid #e2e8f0; text-align: center; }
        .footer p { font-size: 12px; color: #94a3b8; line-height: 1.6; }
        .footer a { color: #2563eb; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚡ SwiftPOS</h1>
            <p>Your Business Management System</p>
        </div>
        <div class="body">
            <p class="greeting">Hello <strong>{{ $name }}</strong>,</p>
            <p style="font-size: 14px; color: #475569; margin-bottom: 20px; line-height: 1.6;">
                Your business <strong>{{ $business }}</strong> has been successfully registered on SwiftPOS. 
                Your {{ $trialDays }}-day free trial has started.
            </p>

            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">Business Name</span>
                    <span class="info-value">{{ $business }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Access URL</span>
                    <span class="info-value">{{ $domain }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Trial Period</span>
                    <span class="info-value">{{ $trialDays }} Days</span>
                </div>
            </div>

            <div class="creds-box">
                <h3>🔐 Your Login Credentials</h3>
                <div class="cred-row">
                    <span class="cred-label">Email</span>
                    <span class="cred-value">{{ $email }}</span>
                </div>
                <div class="cred-row">
                    <span class="cred-label">Password</span>
                    <span class="cred-value">{{ $password }}</span>
                </div>
            </div>

            <a href="http://{{ $domain }}/login" class="login-btn">Login to Your Dashboard →</a>
        </div>
        <div class="footer">
            <p>This password is temporary. Please change it after first login.<br>
            If you didn't create this account, please <a href="#">contact support</a>.</p>
        </div>
    </div>
</body>
</html>