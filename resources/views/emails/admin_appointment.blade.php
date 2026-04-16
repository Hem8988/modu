<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Inter', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 12px; }
        .header { background: #0f172a; color: #fff; padding: 20px; border-radius: 8px 8px 0 0; text-align: center; }
        .content { padding: 20px; }
        .field { margin-bottom: 12px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px; }
        .label { font-weight: bold; color: #64748b; font-size: 12px; text-transform: uppercase; }
        .value { font-size: 16px; color: #0f172a; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #94a3b8; }
        .btn { display: inline-block; padding: 10px 20px; background: #0d6efd; color: #fff; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin:0; font-size: 20px;">📅 New Site Visit Scheduled</h1>
        </div>
        <div class="content">
            <div class="field">
                <div class="label">Customer Name</div>
                <div class="value">{{ $appointment->lead->name }}</div>
            </div>
            <div class="field">
                <div class="label">Date & Time</div>
                <div class="value">{{ $appointment->date->format('M d, Y') }} at {{ $appointment->time }}</div>
            </div>
            <div class="field">
                <div class="label">Type</div>
                <div class="value">{{ ucfirst($appointment->type) }}</div>
            </div>
            <div class="field">
                <div class="label">Notes</div>
                <div class="value">{{ $appointment->notes ?: 'No specific notes' }}</div>
            </div>
            <div class="field">
                <div class="label">Phone</div>
                <div class="value">{{ $appointment->lead->phone }}</div>
            </div>

            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ url('/admin/leads/'.$appointment->lead_id) }}" class="btn">View Lead Profile</a>
            </div>
        </div>
        <div class="footer">
            ModuShade CRM Automation
        </div>
    </div>
</body>
</html>
