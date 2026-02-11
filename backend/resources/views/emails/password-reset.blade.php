<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <style>
        body { margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f5f5f5; }
        .email-container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background-color: #111; color: #ffffff; padding: 30px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 32px; letter-spacing: 0.15em; font-family: Georgia, serif; }
        .header p { margin: 8px 0 0; font-size: 11px; letter-spacing: 0.3em; color: #999; }
        .content { padding: 30px 20px; }
        .warning-box { background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 16px; margin-bottom: 24px; }
        .warning-box p { margin: 0; color: #92400e; font-size: 13px; }
        .button { display: inline-block; background-color: #111; color: #ffffff !important; text-decoration: none; padding: 16px 32px; font-size: 13px; letter-spacing: 0.1em; border-radius: 2px; margin: 20px 0; }
        .security-notice { background-color: #f9fafb; padding: 16px; border-radius: 4px; margin-top: 24px; border-left: 3px solid #e5e7eb; }
        .footer { background-color: #f9fafb; padding: 20px; text-align: center; font-size: 11px; color: #666; }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>QUICKDRAW</h1>
            <p>PRESSING CO.</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Greeting -->
            <p style="font-size: 15px; margin-bottom: 8px;">
                Hi {{ $name }},
            </p>

            <p style="font-size: 14px; color: #666; line-height: 1.6; margin-bottom: 24px;">
                You recently requested to reset your password for your Quickdraw Pressing Co. account. Click the button below to reset it.
            </p>

            <!-- Reset Button -->
            <div style="text-align: center; margin: 32px 0;">
                <a href="{{ $resetUrl }}" class="button">RESET PASSWORD</a>
            </div>

            <!-- Token Expiration Notice -->
            <div class="warning-box">
                <p><strong>⏱ This link expires in 60 minutes</strong></p>
            </div>

            <!-- Alternative Link -->
            <p style="font-size: 12px; color: #999; margin-top: 20px;">
                If the button doesn't work, copy and paste this link into your browser:<br>
                <a href="{{ $resetUrl }}" style="color: #111; word-break: break-all;">{{ $resetUrl }}</a>
            </p>

            <!-- Security Notice -->
            <div class="security-notice">
                <p style="margin: 0 0 8px; font-size: 12px; font-weight: bold;">🔒 SECURITY NOTICE</p>
                <p style="margin: 0; font-size: 12px; color: #666; line-height: 1.5;">
                    If you didn't request a password reset, please ignore this email or contact our support team if you have concerns. Your password will not be changed unless you click the link above and complete the reset process.
                </p>
            </div>

            <!-- Support Info -->
            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                <p style="margin: 0; font-size: 12px; color: #666;">
                    Need help? Contact us at
                    <a href="mailto:support@quickdraw.com" style="color: #111;">support@quickdraw.com</a>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0 0 12px; font-weight: bold; letter-spacing: 0.1em;">QUICKDRAW PRESSING CO.</p>
            <p style="margin: 0 0 12px;">Premium Selvedge Denim & Americana Wear</p>
            <p style="margin: 12px 0 0; font-size: 10px; color: #999;">
                © {{ date('Y') }} Quickdraw Pressing Co. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
