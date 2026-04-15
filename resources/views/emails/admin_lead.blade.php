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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin:0; font-size: 20px;">New Lead Submission</h1>
        </div>
        <div class="content">
            <div class="field">
                <div class="label">Name</div>
                <div class="value">{{ $lead->name }}</div>
            </div>
            <div class="field">
                <div class="label">Email</div>
                <div class="value">{{ $lead->email }}</div>
            </div>
            <div class="field">
                <div class="label">Phone</div>
                <div class="value">{{ $lead->phone }}</div>
            </div>
            <div class="field">
                <div class="label">Location</div>
                <div class="value">{{ $lead->zip_code }}</div>
            </div>
            <div class="field">
                <div class="label">Shades Needed</div>
                <div class="value">{{ $lead->shades_needed }}</div>
            </div>
            <div class="field">
                <div class="label">Windows Count</div>
                <div class="value">{{ $lead->windows_count }}</div>
            </div>
            <div class="field">
                <div class="label">Timeline</div>
                <div class="value">{{ $lead->timeline }}</div>
            </div>
            <div class="field">
                <div class="label">Budget</div>
                <div class="value">{{ $lead->budget }}</div>
            </div>
            <div class="field">
                <div class="label">Message</div>
                <div class="value">{{ $lead->feedback }}</div>
            </div>
        </div>
        <div class="footer">
            Sent from {{ config('app.name') }} CRM
        </div>
    </div>
</body>
</html>
