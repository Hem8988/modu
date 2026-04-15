<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Inter', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 12px; }
        .header { text-align: center; padding-bottom: 20px; }
        .content { padding: 20px; background: #f8fafc; border-radius: 8px; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #94a3b8; }
        .button { display: inline-block; padding: 12px 24px; background: #2563eb; color: #fff; text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="color: #0f172a; margin:0;">Thank You, {{ $lead->name }}!</h1>
        </div>
        <div class="content">
            <p>Hi {{ $lead->name }},</p>
            <p>Thank you for reaching out to **{{ \App\Models\Setting::get('site_name', 'Modu Shade') }}**. We have received your request for **{{ $lead->shades_needed }}** and our team is already reviewing it.</p>
            <p>One of our specialists will contact you at **{{ $lead->phone }}** shortly to discuss your project and schedule a free consultation.</p>
            <p><strong>Your Project Details:</strong></p>
            <ul>
                <li>Windows: {{ $lead->windows_count }}</li>
                <li>Timeline: {{ $lead->timeline }}</li>
                <li>Zip Code: {{ $lead->zip_code }}</li>
            </ul>
        </div>
        <div class="footer">
            © {{ date('Y') }} {{ \App\Models\Setting::get('site_name', 'Modu Shade') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
