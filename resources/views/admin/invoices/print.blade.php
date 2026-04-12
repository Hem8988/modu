<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        :root { --accent: #b89b5e; }
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; color: #1a1a1a; margin: 0; padding: 0; background: #f9fafb; line-height: 1.4; }
        .page { max-width: 850px; margin: 40px auto; background: #fff; padding: 60px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        
        /* Branding */
        .header-grid { display: grid; grid-template-columns: 1fr 1fr; margin-bottom: 60px; }
        .logo-text { font-size: 28px; font-weight: 800; color: #b89b5e; letter-spacing: 2px; margin: 0; text-transform: uppercase; }
        .tagline { font-size: 11px; font-weight: 600; color: #999; text-transform: uppercase; letter-spacing: 1px; margin-top: 4px; }
        
        .company-meta { font-size: 11px; color: #666; margin-top: 15px; line-height: 1.6; }
        
        .doc-title { text-align: right; }
        .doc-title h1 { font-size: 24px; font-weight: 600; margin: 0; color: #000; }
        .doc-id { font-size: 14px; font-weight: 700; color: var(--accent); margin-top: 4px; }
        
        /* Contact Info */
        .billing-grid { display: grid; grid-template-columns: 1fr 1fr; margin-bottom: 50px; font-size: 13px; }
        .bill-to-title { font-size: 10px; font-weight: 800; color: #999; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
        .bill-to-name { font-size: 16px; font-weight: 800; color: #000; margin-bottom: 4px; }
        
        .dates-box { text-align: right; }
        .date-row { display: flex; justify-content: flex-end; gap: 32px; margin-bottom: 8px; }
        .date-label { color: #666; font-weight: 500; min-width: 100px; text-align: left; }
        
        /* Table Layout (Standard Industrial) */
        table { width: 100%; border-collapse: collapse; margin-top: 40px; }
        th { background: #333; color: #fff; text-align: left; padding: 10px 16px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }
        td { padding: 16px; border-bottom: 1px solid #f0f0f0; font-size: 13px; vertical-align: top; }
        .cell-right { text-align: right; }
        
        /* Financial Totals */
        .sum-container { display: flex; justify-content: flex-end; margin-top: 40px; }
        .sum-box { width: 280px; }
        .sum-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 13px; }
        .total-row { background: #f8f8f8; padding: 12px; border-radius: 8px; font-size: 18px; font-weight: 800; margin-top: 15px; }
        
        /* Footer */
        .footer-grid { margin-top: 80px; display: grid; grid-template-columns: 1fr 1fr; gap: 40px; }
        .section-header { font-size: 10px; font-weight: 800; color: #333; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; }
        .section-text { font-size: 11px; color: #666; line-height: 1.6; }
        
        .watermark { text-align: center; margin-top: 60px; font-size: 10px; color: #ccc; letter-spacing: 1px; text-transform: uppercase; font-weight: 700; }
        
        @media print { 
            .print-btn { display: none; } 
            body { background: #fff; padding: 0; } 
            .page { margin: 0; box-shadow: none; width: 100%; max-width: 100%; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="page">
        <button class="print-btn" onclick="window.print()" style="position:fixed; top:20px; right:20px; background:#1a1a1a; color:#fff; border:none; padding:10px 20px; cursor:pointer; border-radius:4px; font-weight:700;">⎙ Print Document</button>

        <header class="brand-header">
            <div>
                <h1 class="logo-text">VELLORA</h1>
                <div style="font-size:14px; font-weight:600; color:#b89b5e; letter-spacing:1px; margin-top:-5px;">SHADES</div>
                <div class="tagline">Where Light Meets Control</div>
            </div>
            <div class="doc-type">
                <h1>Invoice</h1>
                <div class="doc-id">#{{ $invoice->invoice_number }}</div>
            </div>
        </header>

        <section class="contact-grid">
            <div class="company-info">
                <strong>Vellora Shades</strong><br>
                Company ID: 41-4548470<br>
                EIN: 0451421746<br>
                info@vellorashades.com<br>
                7002 Boulevard East #20H<br>
                Guttenberg 07093<br>
                U.S.A
            </div>
            <div class="bill-to">
                <div class="section-title">Bill To</div>
                <strong>{{ $invoice->customer?->name }}</strong><br>
                {{ $invoice->customer?->address ?: 'No address provided' }}<br>
                {{ $invoice->customer?->phone ?: 'No phone provided' }}<br>
                U.S.A
            </div>
            <div class="dates-grid">
                <div class="date-row">
                    <span class="date-label">Invoice Date:</span>
                    <span>{{ \Carbon\Carbon::parse($invoice->created_at ?? now())->format('d M Y') }}</span>
                </div>
                <div class="date-row">
                    <span class="date-label">Due Date:</span>
                    <span>{{ \Carbon\Carbon::parse($invoice->due_date ?? now())->format('d M Y') }}</span>
                </div>
            </div>
        </section>

        <table>
            <thead>
                <tr>
                    <th width="30">#</th>
                    <th>Item & Description</th>
                    <th width="60" class="cell-right">Qty</th>
                    <th width="100" class="cell-right">Rate</th>
                    <th width="100" class="cell-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                {{-- If invoice items exist, loop them; otherwise show a general line --}}
                @php $subtotal = $invoice->total / 1.08875; $tax = $invoice->total - $subtotal; @endphp
                <tr>
                    <td>1</td>
                    <td>
                        <strong>Shade Package</strong><br>
                        <span style="color:#666; font-size:11px;">Custom shade installation and motorized hardware for project ref: {{ $invoice->invoice_number }}</span>
                    </td>
                    <td class="cell-right">1.00</td>
                    <td class="cell-right">${{ number_format($subtotal, 2) }}</td>
                    <td class="cell-right">${{ number_format($subtotal, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="financial-summary">
            <div class="summary-box">
                <div class="summary-row">
                    <span class="date-label">Sub Total</span>
                    <span>${{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span class="date-label">N.Y (8.875%)</span>
                    <span>${{ number_format($tax, 2) }}</span>
                </div>
                <div class="summary-row total-row">
                    <span>Total</span>
                    <span class="total-val">${{ number_format($invoice->total, 2) }}</span>
                </div>
            </div>
        </div>

        <footer class="footer-sections">
            <div style="margin-bottom:24px;">
                <div class="section-title">Notes</div>
                <div style="color:#666">Thank you for choosing ModuShade. We appreciate your business!</div>
            </div>
            
            <div>
                <div class="section-title">Terms & Conditions</div>
                <div class="terms-text">
                    Contract Agreement & Terms:
                    • A 50% deposit is required to begin the order. Remaining balance due upon installation.
                    • All custom orders are final and non-refundable.
                    • Warranty covers mechanical parts for 5 years from date of installation.
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
