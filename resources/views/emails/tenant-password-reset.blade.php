<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f4f5; padding: 40px 20px; }
        .container { max-width: 520px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #2563eb, #1d4ed8); padding: 32px; text-align: center; }
        .header h1 { color: #ffffff; font-size: 20px; font-weight: 700; }
        .header p { color: #bfdbfe; font-size: 14px; margin-top: 4px; }
        .body { padding: 32px; }
        .greeting { font-size: 16px; color: #1e293b; margin-bottom: 16px; }
        .greeting strong { color: #2563eb; }
        .message { font-size: 14px; color: #475569; line-height: 1.7; margin-bottom: 28px; }
        .reset-btn { display: block; width: 100%; padding: 14px; background: #2563eb; color: #ffffff; text-align: center; text-decoration: none; border-radius: 8px; font-size: 15px; font-weight: 600; }
        .reset-btn:hover { background: #1d4ed8; }
        .warning { margin-top: 24px; padding: 16px; background: #fef3c7; border: 1px solid #fde68a; border-radius: 8px; }
        .warning p { font-size: 13px; color: #92400e; line-height: 1.5; }
        .warning strong { color: #78350f; }
        .footer { padding: 24px 32px; background: #f8fafc; border-top: 1px solid #e2e8f0; text-align: center; }
        .footer p { font-size: 12px; color: #94a3b8; line-height: 1.6; }
        .footer a { color: #2563eb; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚡ SwiftPOS</h1>
            <p>Password Reset Request</p>
        </div>
        <div class="body">
            <p class="greeting">Hello <strong>{{ $name }}</strong>,</p>
            <p class="message">
                We received a request to reset your password. Click the button below to set a new password. 
                This link will expire in {{ config('auth.passwords.users.expire') }} minutes.
            </p>
            <a href="{{ $resetUrl }}" class="reset-btn">Reset My Password →</a>
            <div class="warning">
                <p><strong>⚠️ Important:</strong> If you did not request a password reset, no further action is needed. Your account is still secure.</p>
            </div>
        </div>
        <div class="footer">
            <p>If the button doesn't work, copy this link:<br>
            <a href="{{ $resetUrl }}">{{ $resetUrl }}</a></p>
        </div>
    </div>
</body>
</html>