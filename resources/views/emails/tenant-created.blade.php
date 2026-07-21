<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="font-family: system-ui, -apple-system, sans-serif; background: #f3f4f6; margin: 0; padding: 40px 20px;">
    <div style="max-width: 480px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">

        <div style="background: linear-gradient(135deg, #065f46, #10b981); padding: 30px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0; font-size: 22px;">saasPOS</h1>
            <p style="color: #a7f3d0; margin: 8px 0 0; font-size: 14px;">Your Account is Ready!</p>
        </div>

        <div style="padding: 30px;">
            <p style="color: #374151; font-size: 15px; line-height: 1.6; margin: 0 0 8px;">
                Hello,
            </p>
            <p style="color: #374151; font-size: 15px; line-height: 1.6; margin: 0 0 20px;">
                Your business <strong>{{ $tenantName }}</strong> has been set up on saasPOS. Here are your login credentials:
            </p>

            <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; margin: 20px 0;">
                <p style="margin: 0 0 8px; color: #6b7280; font-size: 13px;">Login URL</p>
                <p style="margin: 0 0 16px; color: #059669; font-size: 14px; word-break: break-all;">{{ $loginUrl }}</p>

                <p style="margin: 0 0 8px; color: #6b7280; font-size: 13px;">Email</p>
                <p style="margin: 0 0 16px; color: #111827; font-size: 14px;">{{ $email }}</p>

                <p style="margin: 0 0 8px; color: #6b7280; font-size: 13px;">Password</p>
                <p style="margin: 0; color: #111827; font-size: 14px; font-family: monospace;">{{ $password }}</p>
            </div>

            <div style="text-align: center; margin: 24px 0;">
                <a href="{{ $loginUrl }}"
                   style="display: inline-block; background: #059669; color: #ffffff; padding: 12px 32px; border-radius: 8px; text-decoration: none; font-size: 15px; font-weight: 600;">
                    Login to Your Account
                </a>
            </div>

            <p style="color: #dc2626; font-size: 13px; line-height: 1.5; margin: 16px 0 0; padding: 12px; background: #fef2f2; border-radius: 6px;">
                ⚠️ <strong>Important:</strong> Please change your password immediately after first login. Do not share these credentials.
            </p>
        </div>

        <div style="background: #f9fafb; padding: 20px 30px; border-top: 1px solid #e5e7eb; text-align: center;">
            <p style="color: #9ca3af; font-size: 12px; margin: 0;">
                If you didn't expect this email, please contact support immediately.
            </p>
        </div>
    </div>
</body>
</html>