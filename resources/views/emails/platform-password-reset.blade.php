<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="font-family: system-ui, -apple-system, sans-serif; background: #f3f4f6; margin: 0; padding: 40px 20px;">
    <div style="max-width: 480px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">

        <!-- Header -->
        <div style="background: linear-gradient(135deg, #1e40af, #3b82f6); padding: 30px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0; font-size: 22px;">Platform Admin</h1>
            <p style="color: #bfdbfe; margin: 8px 0 0; font-size: 14px;">Password Reset Request</p>
        </div>

        <!-- Body -->
        <div style="padding: 30px;">
            <p style="color: #374151; font-size: 15px; line-height: 1.6; margin: 0 0 20px;">
                Hello <strong>{{ $name }}</strong>,
            </p>
            <p style="color: #374151; font-size: 15px; line-height: 1.6; margin: 0 0 20px;">
                We received a request to reset your password. Click the button below to set a new password:
            </p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $resetUrl }}"
                   style="display: inline-block; background: #2563eb; color: #ffffff; padding: 12px 32px; border-radius: 8px; text-decoration: none; font-size: 15px; font-weight: 600;">
                    Reset Password
                </a>
            </div>

            <p style="color: #6b7280; font-size: 13px; line-height: 1.5; margin: 20px 0 0;">
                This link will expire in 60 minutes. If you didn't request this, you can safely ignore this email.
            </p>
        </div>

        <!-- Footer -->
        <div style="background: #f9fafb; padding: 20px 30px; border-top: 1px solid #e5e7eb; text-align: center;">
            <p style="color: #9ca3af; font-size: 12px; margin: 0;">
                If the button doesn't work, copy and paste this URL into your browser:<br>
                <span style="word-break: break-all; color: #6b7280;">{{ $resetUrl }}</span>
            </p>
        </div>
    </div>
</body>
</html>