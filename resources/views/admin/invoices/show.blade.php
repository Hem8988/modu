<!DOCTYPE html>
<html lang="en">
<head>
    <title>INVOICE #{{ $invoice->invoice_number }} | {{ $globalSettings['company_name'] ?? 'ModuShade' }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');
        :root { --accent: #2563eb; --gold: #b89b5e; --dark: #1e293b; --muted: #64748b; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Outfit', sans-serif; color: var(--dark); background: #f8fafc; line-height: 1.4; padding: 40px; }
        .invoice-card { max-width: 900px; margin: 0 auto; background: #fff; padding: 60px; box-shadow: 0 10px 40px rgba(0,0,0,0.05); border-radius: 4px; position: relative; }
        
        /* Header Logic */
        .invoice-header { display: flex; justify-content: space-between; margin-bottom: 60px; }
        .doc-meta { text-align: right; }
        .doc-meta h1 { font-size: 28px; font-weight: 800; margin-bottom: 10px; letter-spacing: -1px; }
        .doc-meta .id-badge { display: inline-block; background: var(--dark); color: #fff; padding: 6px 12px; font-size: 14px; font-weight: 700; border-radius: 4px; margin-bottom: 20px; }
        
        .brand-meta { text-align: left; }
        .brand-meta img { height: 40px; margin-bottom: 10px; }
        .brand-name { font-size: 24px; font-weight: 800; color: #000; letter-spacing: -1px; }
        .brand-name span { color: var(--gold); }
        .brand-sub { font-size: 10px; color: var(--muted); letter-spacing: 2px; text-transform: uppercase; font-weight: 700; margin-bottom: 15px; }
        .company-details { font-size: 12px; color: var(--muted); line-height: 1.6; }

        /* Addressing */
        .address-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 50px; font-size: 13px; }
        .address-box h3 { font-size: 11px; font-weight: 800; color: var(--muted); text-transform: uppercase; margin-bottom: 10px; border-bottom: 1px solid #f1f5f9; padding-bottom: 4px; }
        .address-box p { font-weight: 600; line-height: 1.6; }

        /* Document Dates */
        .date-registry { display: flex; gap: 40px; margin-bottom: 40px; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; padding: 15px 0; }
        .date-item label { display: block; font-size: 10px; font-weight: 800; color: var(--muted); text-transform: uppercase; margin-bottom: 4px; }
        .date-item span { font-weight: 700; font-size: 13px; }

        /* Item Table */
        table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        th { text-align: left; background: #fff; padding: 12px 10px; font-size: 10px; font-weight: 800; color: var(--muted); text-transform: uppercase; border-bottom: 1px solid #f1f5f9; }
        td { padding: 18px 10px; border-bottom: 1px solid #f1f5f9; font-size: 13px; font-weight: 600; }
        .col-num { color: var(--muted); width: 40px; }
        .col-qty, .col-rate, .col-amt { text-align: right; width: 100px; }

        /* Totals */
        .totals-section { display: flex; justify-content: flex-end; }
        .totals-box { width: 280px; }
        .total-row { display: flex; justify-content: space-between; padding: 8px 0; font-size: 13px; font-weight: 600; color: var(--muted); }
        .total-row.grand-total { border-top: 2px solid var(--dark); margin-top: 10px; padding-top: 15px; color: #000; font-size: 18px; font-weight: 800; }

        /* Notes & Footer */
        .notes-section { margin-top: 60px; font-size: 12px; }
        .notes-section h3 { font-size: 11px; font-weight: 800; text-transform: uppercase; margin-bottom: 10px; }
        .notes-section p { color: var(--muted); line-height: 1.6; }

        .terms-registry { margin-top: 40px; font-size: 10px; color: var(--muted); line-height: 1.6; border-top: 1px solid #f1f5f9; padding-top: 20px; }

        .print-fab { position: fixed; bottom: 30px; right: 30px; background: var(--dark); color: #fff; border: none; padding: 14px 24px; border-radius: 8px; font-weight: 800; cursor: pointer; box-shadow: 0 10px 30px rgba(0,0,0,0.1); transition: transform 0.2s; z-index: 1000; }
        .print-fab:hover { transform: translateY(-2px); }

        @media print {
            body { background: #fff; padding: 0; }
            .invoice-card { box-shadow: none; padding: 0; margin: 0; max-width: 100%; }
            .print-fab { display: none; }
            .invoice-header { display: flex !important; flex-direction: row !important; justify-content: space-between !important; }
            .address-grid { display: grid !important; grid-template-columns: 1fr 1fr !important; gap: 40px !important; }
        }
        @media (max-width: 768px) {
            body { padding: 16px; }
            .invoice-card { padding: 24px; }
            .invoice-header { flex-direction: column; margin-bottom: 30px; }
            .brand-meta { text-align: left; margin-top: 20px; }
            .address-grid { grid-template-columns: 1fr !important; gap: 20px; margin-bottom: 30px; }
            .address-box { text-align: left !important; }
            .date-registry { flex-wrap: wrap; gap: 20px; }
            .table-responsive { width: 100%; overflow-x: auto; }
            .totals-section { justify-content: flex-start; }
            .totals-box { width: 100%; margin-top: 20px; }
        }
    </style>
</head>
<body>
    <button class="print-fab" onclick="window.print()">PRINT INVOICE</button>

    <div class="invoice-card">
        <div class="invoice-header">
            <div class="brand-meta">
                <div class="brand-name">MODU<span>SHADE</span></div>
                <div class="company-details">
                    {{ $globalSettings['company_name'] ?? 'ModuShade Industrial' }}<br>
                    {{ $globalSettings['company_address_1'] ?? '24 Poplar Street' }}, {{ $globalSettings['company_address_2'] ?? 'Creskill, NJ 07626' }}<br>
                    Phone: {{ $globalSettings['company_phone'] ?? '+1 201 660 5298' }}<br>
                    Email: {{ $globalSettings['company_email'] ?? 'info@modu-shade.com' }}<br>
                    Website: {{ $globalSettings['company_website'] ?? 'info.modu-shade.com' }}
                </div>
            </div>
            <div class="doc-meta">
                <div class="id-badge">OFFICIAL INVOICE</div>
                <h1>#INV-{{ strtoupper(substr($invoice->invoice_number, 0, 8)) }}</h1>
                <div style="font-size: 13px; font-weight: 700;">
                   Status: <span style="color: {{ $invoice->status === 'paid' ? '#10b981' : '#f59e0b' }}">{{ strtoupper($invoice->status) }}</span>
                </div>
            </div>
        </div>

        <div class="address-grid" style="grid-template-columns: 1fr 1fr;">
            <div></div> <!-- Spacer -->
            <div class="address-box" style="text-align: right;">
                <h3 style="border-bottom: 1px solid #f1f5f9; padding-bottom: 4px; margin-bottom: 10px;">Bill To</h3>
                <p>
                    {{ $invoice->customer?->name ?? 'Project Client' }}<br>
                    {{ $invoice->customer?->address ?? 'No address provided' }}<br>
                    {{ $invoice->customer?->phone }}<br>
                    {{ $invoice->customer?->email }}
                </p>
            </div>
        </div>

        <div class="date-registry">
            <div class="date-item">
                <label>Invoice Date</label>
                <span>{{ $invoice->created_at?->format('d M Y') }}</span>
            </div>
            <div class="date-item">
                <label>Due Date</label>
                <span>{{ $invoice->due_date?->format('d M Y') }}</span>
            </div>
            <div class="date-item">
                <label>Project Ref</label>
                <span>#MS-{{ $invoice->id }}</span>
            </div>
        </div>

        <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th class="col-num">#</th>
                    <th>ITEM & DESCRIPTION</th>
                    <th class="col-qty">QTY</th>
                    <th class="col-rate">RATE</th>
                    <th class="col-amt">AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $items = [
                        ['name' => 'Industrial Solar Installation', 'desc' => 'Full site deployment and commissioning', 'qty' => 1, 'price' => $invoice->total]
                    ];
                @endphp
                @foreach($items as $index => $item)
                <tr>
                    <td class="col-num">{{ $index + 1 }}</td>
                    <td>
                        <div style="font-weight: 800;">{{ $item['name'] }}</div>
                        <div style="font-size: 11px; color: var(--muted); margin-top: 2px;">{{ $item['desc'] }}</div>
                    </td>
                    <td class="col-qty">{{ number_format($item['qty'], 2) }}</td>
                    <td class="col-rate">${{ number_format($item['price'], 2) }}</td>
                    <td class="col-amt">${{ number_format($item['qty'] * $item['price'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>

        <div class="totals-section">
            <div class="totals-box">
                <div class="total-row">
                    <span>Sub Total</span>
                    <span>${{ number_format($invoice->total, 2) }}</span>
                </div>
                <div class="total-row">
                    <span>Tax (0%)</span>
                    <span>$0.00</span>
                </div>
                <div class="total-row grand-total">
                    <span>Total</span>
                    <span>${{ number_format($invoice->total, 2) }}</span>
                </div>
                @if($invoice->due < $invoice->total)
                <div class="total-row" style="margin-top: 10px; color: #10b981;">
                    <span>Payment Made</span>
                    <span>-${{ number_format($invoice->total - $invoice->due, 2) }}</span>
                </div>
                <div class="total-row" style="font-weight: 800; color: #ef4444;">
                    <span>Balance Due</span>
                    <span>${{ number_format($invoice->due, 2) }}</span>
                </div>
                @endif
            </div>
        </div>

        <div class="notes-section">
            <h3>Notes</h3>
            <p>Thank you for choosing {{ $globalSettings['company_name'] ?? 'ModuShade' }}. We look forward to working with you!</p>
        </div>

        <div class="terms-registry">
            <strong>Terms & Conditions:</strong><br>
            A 50% deposit is required to begin the order. Remaining balance due upon Installation. 
            All industrial components remain property of {{ $globalSettings['company_name'] ?? 'ModuShade' }} until account is $0.00. 
            Late payments may result in site commissioning delays.
        </div>
    </div>
</body>
</html>
</html>
