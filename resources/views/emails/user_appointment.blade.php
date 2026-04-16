<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Inter', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 12px; }
        .header { background: #b89b5e; color: #fff; padding: 20px; border-radius: 8px 8px 0 0; text-align: center; }
        .content { padding: 20px; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #94a3b8; }
        .brand { color: #b89b5e; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin:0; font-size: 20px;">✅ Site Visit Confirmed</h1>
        </div>
        <div class="content">
            <p>Hi {{ $appointment->lead->name }},</p>
            <p>Your site visit with <span class="brand">ModuShade</span> has been successfully scheduled. Our team will visit your location to discuss your project and take necessary measurements.</p>
            
            <div style="background: #f8fafc; padding: 15px; border-radius: 8px; margin: 20px 0;">
                <div style="font-weight: bold; margin-bottom: 5px;">Appointment Details:</div>
                <div>📅 <strong>Date:</strong> {{ $appointment->date->format('M d, Y') }}</div>
                <div>⏰ <strong>Time:</strong> {{ $appointment->time }}</div>
                <div>📍 <strong>Location:</strong> {{ $appointment->lead->city }}</div>
            </div>

            <p>If you need to reschedule or have any questions, please reply to this email or call us at our office.</p>
            
            <p>Best Regards,<br><strong>Team ModuShade</strong></p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} ModuShade. All rights reserved.
        </div>
    </div>
</body>
</html>
