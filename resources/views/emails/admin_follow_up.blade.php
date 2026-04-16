<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Inter', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 12px; }
        .header { background: #f59e0b; color: #fff; padding: 20px; border-radius: 8px 8px 0 0; text-align: center; }
        .content { padding: 20px; }
        .field { margin-bottom: 12px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px; }
        .label { font-weight: bold; color: #64748b; font-size: 12px; text-transform: uppercase; }
        .value { font-size: 16px; color: #0f172a; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #94a3b8; }
        .btn { display: inline-block; padding: 10px 20px; background: #f59e0b; color: #fff; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin:0; font-size: 20px;">⏳ Action Required: Lead Follow-up</h1>
        </div>
        <div class="content">
            <p>A new follow-up activity has been logged for <strong>{{ $followUp->lead->name }}</strong>.</p>
            
            <div class="field">
                <div class="label">Next Action Date</div>
                <div class="value">{{ date('M d, Y h:i A', strtotime($followUp->date)) }}</div>
            </div>
            <div class="field">
                <div class="label">Channel</div>
                <div class="value">{{ ucfirst($followUp->type) }}</div>
            </div>
            <div class="field">
                <div class="label">Task Notes</div>
                <div class="value">{{ $followUp->notes ?: 'No specific notes' }}</div>
            </div>

            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ url('/admin/leads/'.$followUp->lead_id) }}" class="btn">Process Lead Now</a>
            </div>
        </div>
        <div class="footer">
            ModuShade CRM Automation
        </div>
    </div>
</body>
</html>
