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
            <h1 style="color: #0f172a; margin:0;">New Quotation Alert</h1>
        </div>
        <div class="content">
            <p>A new quotation has been generated for <strong>{{ $quote->lead->name }}</strong>.</p>
            <p><strong>Quotation Details:</strong></p>
            <ul>
                <li>Quote Number: {{ $quote->quote_number }}</li>
                <li>Total Amount: ${{ number_format($quote->total_amount, 2) }}</li>
                <li>Expiry Date: {{ $quote->expiry_date ? $quote->expiry_date->format('M d, Y') : 'N/A' }}</li>
            </ul>
            <p>Please log in to the admin panel to review and manage this quotation.</p>
        </div>
        <div class="footer">
            © {{ date('Y') }} {{ \App\Models\Setting::get('site_name', 'Modu Shade') }}. Admin Notification.
        </div>
    </div>
</body>
</html>
