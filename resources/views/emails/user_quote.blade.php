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
            <h1 style="color: #0f172a; margin:0;">Your Quotation is Ready!</h1>
        </div>
        <div class="content">
            <p>Hi {{ $quote->lead->name }},</p>
            <p>We are pleased to provide you with a quotation for your project. You can review the details below:</p>
            <ul>
                <li>Quote Number: {{ $quote->quote_number }}</li>
                <li>Total Amount: ${{ number_format($quote->total_amount, 2) }}</li>
                <li>Valid Until: {{ $quote->expiry_date ? $quote->expiry_date->format('M d, Y') : 'N/A' }}</li>
            </ul>
            <p>To view the full quotation and sign it electronically, please click the button below:</p>
            <p style="text-align: center;">
                <a href="{{ url('/quote/view/' . $quote->client_token) }}" class="button">View & Sign Quotation</a>
            </p>
            <p>If you have any questions, feel free to reply to this email or call us.</p>
        </div>
        <div class="footer">
            © {{ date('Y') }} {{ \App\Models\Setting::get('site_name', 'Modu Shade') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
