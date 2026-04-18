<!DOCTYPE html>
<html lang="en">
<head>
    <title>Proposal #{{ $quote->quote_number }} | {{ $globalSettings['company_name'] ?? 'ModuShade' }}</title>
    <style>
        :root {
            --slate-900: #0f172a;
            --slate-800: #1e293b;
            --slate-600: #475569;
            --slate-400: #94a3b8;
            --gold: #b89b5e;
            --accent: #2563eb;
            --border: #e2e8f0;
            --bg-light: #f8fafc;
        }
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            color: var(--slate-800);
            margin: 0;
            padding: 0;
            background: #fff;
            line-height: 1.6;
            -webkit-print-color-adjust: exact;
        }

        .page {
            max-width: 850px;
            margin: 0 auto;
            padding: 60px 40px;
            position: relative;
            background: white;
        }

        /* Subtle Document Grid Pattern */
        .page::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: radial-gradient(var(--border) 0.5px, transparent 0.5px);
            background-size: 20px 20px;
            opacity: 0.15;
            pointer-events: none;
            z-index: 0;
        }

        header, section, table, footer { position: relative; z-index: 1; }

        /* Premium Header Layout */
        .header-main {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 60px;
            padding-bottom: 30px;
            border-bottom: 2px solid var(--slate-900);
        }

        .brand-block h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 36px;
            font-weight: 900;
            color: var(--slate-900);
            margin: 0;
            letter-spacing: -1.5px;
            text-transform: uppercase;
        }
        .brand-block h1 span { color: var(--gold); }
        .brand-block p {
            font-size: 11px;
            font-weight: 800;
            color: var(--slate-400);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 4px;
        }

        .id-block { text-align: right; }
        .id-badge {
            background: var(--slate-900);
            color: white;
            padding: 8px 16px;
            font-family: 'Outfit', sans-serif;
            font-weight: 900;
            font-size: 20px;
            display: inline-block;
            margin-bottom: 12px;
        }
        .id-details { font-size: 12px; font-weight: 600; color: var(--slate-600); }
        .id-details strong { color: var(--slate-900); }

        /* Address Grid */
        .address-grid {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 50px;
            page-break-inside: avoid;
        }
        .address-box h3 {
            font-size: 10px;
            font-weight: 900;
            color: var(--gold);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 12px;
            border-bottom: 1px solid var(--border);
            padding-bottom: 6px;
        }
        .address-content { font-size: 13px; line-height: 1.6; }
        .address-content strong { color: var(--slate-900); font-size: 16px; display: block; margin-bottom: 4px; }

        /* Table Redesign */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th {
            background: var(--bg-light);
            color: var(--slate-900);
            text-align: left;
            padding: 14px 20px;
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-top: 1px solid var(--slate-900);
            border-bottom: 1px solid var(--slate-900);
        }
        td {
            padding: 20px;
            border-bottom: 1px solid var(--border);
            font-size: 14px;
            vertical-align: top;
        }
        .item-name {
            font-weight: 800;
            color: var(--slate-900);
            font-size: 15px;
            margin-bottom: 4px;
        }
        .item-specs {
            font-size: 11px;
            color: var(--slate-400);
            font-weight: 500;
            line-height: 1.4;
        }
        .item-specs strong { color: var(--slate-600); }
        .cell-right { text-align: right; }

        /* Financial Ledger */
        .financial-container {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }
        .ledger-box {
            width: 300px;
            background: var(--bg-light);
            padding: 24px;
            border: 1px solid var(--border);
        }
        .ledger-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 13px;
            font-weight: 600;
            color: var(--slate-600);
        }
        .ledger-row.total {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 2px solid var(--slate-900);
            color: var(--slate-900);
            font-size: 24px;
            font-family: 'Outfit', sans-serif;
            font-weight: 900;
        }

        /* Footer & Signatures */
        footer { margin-top: 60px; }
        .notes-section {
            margin-bottom: 40px;
            padding-left: 20px;
            border-left: 3px solid var(--gold);
        }
        .notes-section h4 { font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
        .notes-section p { font-size: 12px; color: var(--slate-600); margin: 0; }

        .signature-block {
            margin-top: 50px;
            background: #fff;
            border: 1.5px solid var(--slate-900);
            display: flex;
            justify-content: space-between;
            overflow: hidden;
            border-radius: 4px;
        }
        .sig-meta {
            padding: 24px;
            background: var(--bg-light);
            border-right: 1.5px solid var(--slate-900);
            width: 60%;
        }
        .sig-header { font-size: 9px; font-weight: 900; text-transform: uppercase; color: var(--gold); letter-spacing: 1px; margin-bottom: 10px; }
        .sig-name { font-size: 22px; font-weight: 950; color: var(--slate-900); letter-spacing: -0.5px; }
        .sig-details { font-size: 11px; color: var(--slate-400); margin-top: 6px; font-family: monospace; }
        
        .sig-drawing {
            padding: 24px;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .sig-drawing img { max-height: 80px; filter: contrast(1.2) grayscale(1); }
        .sig-label { font-size: 9px; font-weight: 800; text-transform: uppercase; color: var(--slate-400); margin-top: 12px; }

        .print-fab {
            position: fixed;
            bottom: 40px; right: 40px;
            background: var(--slate-900);
            color: #fff;
            border: none;
            padding: 16px 32px;
            border-radius: 50px;
            font-weight: 800;
            font-size: 14px;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(15,23,42,0.3);
            z-index: 9999;
            display: flex; align-items: center; gap: 10px;
        }

        @media print {
            .print-fab { display: none; }
            body { background: white; }
            .page { padding: 40px 0; border: none; max-width: 100%; box-shadow: none; }
            .page::before { opacity: 0.05; }
        }
        @media screen and (max-width: 768px) {
            .header-main { flex-direction: column !important; }
            .id-block { text-align: left; margin-top: 20px; }
            .address-grid { flex-direction: column !important; gap: 40px; }
            .address-box { text-align: left !important; }
            .page { padding: 20px; }
            .table-responsive { width: 100%; overflow-x: auto; }
            .signature-block { flex-direction: column; }
            .sig-meta { width: auto; border-right: none; border-bottom: 1.5px solid var(--slate-900); }
        }
    </style>
</head>
<body onload="window.print()">
    <button class="print-fab" onclick="window.print()">
        <span>⎙</span> PRINT DOCUMENT
    </button>

    <div class="page">
        <header class="header-main">
                @php $nParts = explode(' ', $globalSettings['company_name'] ?? 'Modu Shade'); $first = array_shift($nParts); $rest = implode(' ', $nParts); @endphp
                <h1>{{ $first }}<span>{{ $rest }}</span></h1>
                <p>Digital Industrial Solutions</p>
                <div style="margin-top: 20px; font-size: 12px; color: var(--slate-600); line-height: 1.5;">
                    {{ $globalSettings['company_address_1'] ?? '24 Poplar Street' }}, {{ $globalSettings['company_address_2'] ?? 'Creskill, NJ 07626' }}<br>
                    <strong>{{ $globalSettings['company_phone'] ?? '+1 201 660 5298' }}</strong> | {{ $globalSettings['company_email'] ?? 'info@modu-shade.com' }}
                </div>
            </div>
            <div class="id-block">
                <div class="sig-header" style="color: var(--slate-400); text-align: right; margin-bottom: 8px;">Official Project Registry</div>
                <div class="id-badge">PROPOSAL #{{ $quote->quote_number }}</div>
                <div class="id-details">
                    Issue Date: <strong>{{ $quote->created_at?->format('F d, Y') }}</strong><br>
                    Valid Until: <strong>{{ $quote->expiry_date?->format('F d, Y') }}</strong>
                </div>
            </div>
        </header>

        <section class="address-grid">
            <div class="address-box">
                <h3>Origin Entity</h3>
                <div class="address-content">
                    <strong>{{ $globalSettings['company_name'] ?? 'Vellora Shades' }}</strong>
                    {{ $globalSettings['company_address_1'] ?? '24 Poplar Street' }}<br>
                    {{ $globalSettings['company_address_2'] ?? 'Creskill, NJ 07626' }}<br>
                    {{ $globalSettings['company_phone'] ?? '+1 201 660 5298' }}
                </div>
            </div>
            
            <div class="address-box" style="text-align: right;">
                <h3>Recipient Client</h3>
                <div class="address-content">
                    <strong>{{ $lead->name }}</strong>
                    {{ $lead->address ?: 'Site Address Pending' }}<br>
                    {{ $lead->phone ?: 'No Phone Recorded' }}<br>
                    {{ $lead->email ?: '' }}
                </div>
            </div>
        </section>

        <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th width="40">Pos</th>
                    <th>Configuration Details</th>
                    <th width="60" class="cell-right">Qty</th>
                    <th width="100" class="cell-right">Rate</th>
                    <th width="80" class="cell-right">VAT %</th>
                    <th width="120" class="cell-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td style="font-weight: 800; color: var(--slate-400);">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                    <td>
                        <div class="item-name">{{ $item->product_name ?: 'Custom Unit' }}</div>
                        <div class="item-specs">
                            @if($item->width > 0) 
                            <strong>DIMENSIONS:</strong> {{ $item->width }}" × {{ $item->height }}" (W×H)<br> 
                            @endif
                            
                            @php $opts = $item->options_json ?? [] @endphp
                            @if(!empty($opts)) 
                                @foreach($opts as $k => $v)
                                    <strong>{{ strtoupper(str_replace('_',' ',$k)) }}:</strong> {{ $v }}{{ !$loop->last ? ' | ' : '' }}
                                @endforeach
                            @endif
                        </div>
                    </td>
                    <td class="cell-right" style="font-weight: 700;">{{ number_format($item->quantity, 0) }}</td>
                    <td class="cell-right">${{ number_format($item->unit_price, 2) }}</td>
                    <td class="cell-right">{{ number_format($item->vat_percentage, 1) }}%</td>
                    <td class="cell-right" style="font-weight: 800; color: var(--slate-900);">${{ number_format($item->subtotal + ($item->vat_amount ?? 0), 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>

        <div class="financial-container" style="display: flex; justify-content: space-between; align-items: flex-start; gap: 40px; margin-top: 30px;">
            <div style="flex: 1;">
                @if($quote->narration)
                <h4 style="font-size: 10px; font-weight: 900; color: var(--gold); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 12px; border-bottom: 1px solid var(--border); padding-bottom: 6px;">Project Scope & Narration</h4>
                <div style="font-size: 12px; color: var(--slate-600); line-height: 1.7; white-space: pre-line;">{{ $quote->narration }}</div>
                @endif
            </div>
            
            <div class="ledger-box">
                <div class="ledger-row">
                    <span>Item Subtotal</span>
                    <span>${{ number_format($quote->subtotal ?: $items->sum('subtotal'), 2) }}</span>
                </div>
                <div class="ledger-row">
                    <span>VAT Total</span>
                    <span>${{ number_format($quote->vat_amount, 2) }}</span>
                </div>

                @if($quote->discount > 0)
                <div class="ledger-row" style="color: #ef4444;">
                    <span>Total Discount</span>
                    <span>-${{ number_format($quote->discount, 2) }}</span>
                </div>
                @endif
                <div class="ledger-row total">
                    <span>GRAND TOTAL</span>
                    <span>${{ number_format($quote->total_amount, 2) }}</span>
                </div>
                <div style="font-size: 9px; color: var(--slate-400); text-align: right; margin-top: 8px; font-weight: 700;">All prices in USD</div>
            </div>
        </div>

        <footer>
            <div class="notes-section">
                <h4>Terms of Engagement</h4>
                <p>
                    • A 50% commitment deposit is required to initiate manufacture.<br>
                    • Quotation remains active for 30 calendar days from issue date.<br>
                    • Standard warranty covers mechanical systems for 5 years post-installation.
                </p>
            </div>

            @if($quote->signature_data)
            <div class="signature-block">
                <div class="sig-meta">
                    <div class="sig-header">Verification Certificate</div>
                    <div class="sig-name">{{ $quote->signature_name }}</div>
                    <div class="sig-details">
                        SIGNED_BY: {{ strtoupper($quote->signature_name) }}<br>
                        CLIENT_ID: {{ strtoupper($quote->client_token) }}<br>
                        TIMESTAMP: {{ $quote->signed_at?->format('Y-m-d H:i:s') }} UTC<br>
                        SECURITY: DIGITAL_SIGNATURE_VERIFIED
                    </div>
                </div>
                <div class="sig-drawing">
                    <img src="{{ $quote->signature_data }}" alt="Digital Signature">
                    <div class="sig-label">Authorized Acceptance Signature</div>
                </div>
            </div>
            @else
            <div style="margin-top: 100px; display: flex; justify-content: flex-end; gap: 60px;">
                <div style="text-align: center; width: 220px;">
                    <div style="height: 60px; border-bottom: 1px solid var(--border); margin-bottom: 8px;"></div>
                    <div style="font-size: 10px; font-weight: 800; color: var(--slate-400); text-transform: uppercase;">{{ $globalSettings['company_name'] ?? 'ModuShade' }} Authority</div>
                </div>
                <div style="text-align: center; width: 220px;">
                    <div style="height: 60px; border-bottom: 1px solid var(--border); margin-bottom: 8px;"></div>
                    <div style="font-size: 10px; font-weight: 800; color: var(--slate-400); text-transform: uppercase;">Client Acceptance</div>
                </div>
            </div>
            @endif
        </footer>
    </div>
</body>
</html>
