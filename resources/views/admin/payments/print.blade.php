<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Receipt #{{ $payment->id }}</title>
    <style>
        body { font-family: 'Inter', sans-serif; color: #333; margin: 40px; }
        .receipt-box { border: 2px solid #eee; padding: 40px; max-width: 600px; margin: auto; border-radius: 12px; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 30px; }
        .logo { font-size: 20px; font-weight: 800; color: #000; }
        .amount-section { background: #f8f8f8; padding: 30px; border-radius: 8px; text-align: center; margin: 30px 0; }
        .amount { font-size: 32px; font-weight: bold; color: #238636; }
        .info-row { display: flex; justify-content: space-between; margin: 12px 0; font-size: 15px; }
        .info-label { color: #888; }
        .footer { margin-top: 40px; font-size: 13px; color: #999; text-align: center; border-top: 1px solid #eee; padding-top: 20px; }
        @media print { .print-btn { display: none; } }
    </style>
</head>
<body onload="window.print()">
    <div style="text-align:center; margin-bottom: 20px;" class="print-btn">
        <button onclick="window.print()" style="padding:10px 20px; cursor:pointer">Print Receipt</button>
    </div>

    <div class="receipt-box">
        <div class="header">
            <div class="logo">MODU SHADE <span style="font-weight:400;color:#666">LLC</span></div>
            <div style="text-align:right">
                <h2 style="margin:0; color: #238636;">RECEIPT</h2>
                <small style="color:#888">No: RCPT-{{ str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</small>
            </div>
        </div>

        <div class="info-row">
            <span class="info-label">Payment Date</span>
            <span>{{ $payment->date?->format('F d, Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Received From</span>
            <span style="font-weight:600">{{ $payment->customer?->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Invoice Reference</span>
            <span>{{ $payment->invoice?->invoice_number }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Payment Mode</span>
            <span>{{ ucfirst($payment->mode) }}</span>
        </div>

        <div class="amount-section">
            <div style="color: #888; margin-bottom: 10px; font-size: 14px;">AMOUNT RECEIVED</div>
            <div class="amount">${{ number_format($payment->amount, 2) }}</div>
        </div>

        @if($payment->notes)
        <div style="margin-top: 20px; font-size: 14px; background: #fffbe6; padding: 15px; border-radius: 6px; border: 1px solid #ffe58f;">
            <strong style="display:block;margin-bottom:5px">Note:</strong>
            {{ $payment->notes }}
        </div>
        @endif

        <div class="footer">
            <p>This is a computer-generated receipt.</p>
            <p>MODU SHADE LLC • 123 Shade Ave, NY • support@modushade.com</p>
        </div>
    </div>
</body>
</html>
