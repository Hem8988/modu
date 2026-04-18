<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proposal #{{ $quote->quote_number }} — Modu-Shade Official</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --accent: #2563eb;
            --accent-soft: rgba(37, 99, 235, 0.1);
            --slate-50: #f8fafc;
            --slate-100: #f1f5f9;
            --slate-200: #e2e8f0;
            --slate-300: #cbd5e1;
            --slate-400: #94a3b8;
            --slate-500: #64748b;
            --slate-600: #475569;
            --slate-700: #334155;
            --slate-800: #1e293b;
            --slate-900: #0f172a;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #f1f5f9;
            color: var(--slate-900);
            line-height: 1.6;
            padding-bottom: 80px;
        }
        
        h1, h2, h3, h4, .outfit { font-family: 'Outfit', sans-serif; }

        .app-container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
        }

        /* Hero Header */
        .glass-header {
            background: #ffffff;
            border-radius: 32px;
            border: 1px solid var(--slate-200);
            padding: 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow-xl);
            margin-bottom: 40px;
            position: relative;
            overflow: hidden;
        }
        .glass-header::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 8px;
            background: linear-gradient(90deg, #2563eb, #3b82f6);
        }

        .brand-section h1 {
            font-size: 24px;
            font-weight: 900;
            letter-spacing: -1px;
            color: var(--slate-900);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .brand-section h1 span { color: var(--accent); }
        .brand-section p {
            font-size: 13px;
            color: var(--slate-500);
            font-weight: 600;
            margin-top: 4px;
        }

        .ref-badge {
            text-align: right;
        }
        .ref-label {
            font-size: 10px;
            font-weight: 900;
            color: var(--slate-400);
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .ref-number {
            font-size: 28px;
            font-weight: 950;
            color: var(--slate-900);
            font-family: 'Outfit';
            line-height: 1;
            margin: 4px 0;
        }
        .ref-date {
            font-size: 13px;
            color: var(--accent);
            font-weight: 700;
        }

        /* Project Specs Area */
        .specs-card {
            background: #ffffff;
            border-radius: 32px;
            border: 1px solid var(--slate-200);
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            margin-bottom: 32px;
        }
        .specs-header {
            padding: 24px 32px;
            background: var(--slate-50);
            border-bottom: 1px solid var(--slate-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .specs-header h2 {
            font-size: 16px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .specs-header h2 i { color: var(--accent); }

        .items-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .items-table th {
            padding: 16px 32px;
            text-align: left;
            font-size: 10px;
            font-weight: 900;
            color: var(--slate-400);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            background: #ffffff;
            border-bottom: 1px solid var(--slate-100);
        }
        .items-table td {
            padding: 32px;
            border-bottom: 1px solid var(--slate-100);
            vertical-align: top;
        }

        .item-main {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .product-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--slate-900);
            font-family: 'Outfit';
        }

        .attribute-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            gap: 12px;
        }
        .attr-box {
            background: var(--slate-50);
            border: 1px solid var(--slate-200);
            padding: 10px 14px;
            border-radius: 12px;
        }
        .attr-label {
            font-size: 9px;
            font-weight: 800;
            color: var(--slate-400);
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .attr-value {
            font-size: 12px;
            font-weight: 700;
            color: var(--slate-700);
        }

        .valuation-cell {
            text-align: right;
            font-family: 'Outfit';
        }
        .unit-price {
            font-size: 14px;
            color: var(--slate-500);
            font-weight: 600;
        }
        .line-total {
            font-size: 20px;
            font-weight: 900;
            color: var(--slate-900);
            margin-top: 4px;
        }

        /* Financial Summary Dashboard */
        .summary-dashboard {
            background: #ffffff;
            border-radius: 28px;
            border: 1px solid var(--slate-200);
            padding: 32px;
            margin-bottom: 40px;
            box-shadow: var(--shadow-lg);
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            align-items: center;
        }
        .summary-box {
            padding: 20px;
            border-radius: 20px;
            background: var(--slate-50);
            border: 1px solid var(--slate-200);
        }
        .summary-box .label {
            font-size: 11px;
            font-weight: 800;
            color: var(--slate-500);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            display: block;
        }
        .summary-box .value {
            font-size: 18px;
            font-weight: 800;
            font-family: 'Outfit';
        }
        
        .total-payable-box {
            background: var(--slate-900) !important;
            color: white;
            border: none !important;
        }
        .total-payable-box .label { color: rgba(255,255,255,0.4); }
        .total-payable-box .value { font-size: 32px; font-weight: 950; }

        /* Acceptance Portal */
        .acceptance-section {
            background: #ffffff;
            border-radius: 32px;
            border: 1px solid var(--slate-200);
            padding: 60px 40px;
            text-align: center;
            box-shadow: var(--shadow-xl);
        }
        .acceptance-section h2 {
            font-size: 32px;
            font-weight: 950;
            letter-spacing: -1.5px;
            margin-bottom: 12px;
        }
        .acceptance-section p {
            color: var(--slate-500);
            max-width: 600px;
            margin: 0 auto 40px;
        }

        .auth-form {
            max-width: 500px;
            margin: 0 auto;
            text-align: left;
        }
        .input-group { margin-bottom: 24px; }
        .input-label {
            display: block;
            font-size: 11px;
            font-weight: 900;
            color: var(--slate-400);
            text-transform: uppercase;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }
        .premium-input {
            width: 100%;
            padding: 16px 20px;
            border-radius: 14px;
            border: 2px solid var(--slate-200);
            font-family: inherit;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.2s;
            outline: none;
        }
        .premium-input:focus {
            border-color: var(--accent);
            background: var(--slate-50);
        }

        .signature-wrapper {
            background: var(--slate-50);
            border: 2px dashed var(--slate-200);
            border-radius: 20px;
            height: 200px;
            position: relative;
            margin-bottom: 32px;
        }
        #signature-pad { width: 100%; height: 100%; cursor: crosshair; }
        .sig-hint {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            font-size: 12px;
            font-weight: 800;
            color: var(--slate-300);
            text-transform: uppercase;
            letter-spacing: 2px;
            pointer-events: none;
        }

        .btn-authorize {
            width: 100%;
            padding: 20px;
            border-radius: 16px;
            background: var(--accent);
            color: white;
            border: none;
            font-size: 16px;
            font-weight: 900;
            letter-spacing: 1px;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(37,99,235,0.3);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-authorize:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(37,99,235,0.4);
        }

        /* Success State */
        .success-hero {
            text-align: center;
            padding: 60px 20px;
        }
        .success-icon {
            width: 80px; height: 80px;
            background: #10b981;
            color: white;
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            margin: 0 auto 24px;
            box-shadow: 0 15px 30px rgba(16,185,129,0.3);
        }

        /* Loading Overlay */
        #loading-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(255,255,255,0.9);
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            backdrop-filter: blur(8px);
        }
        .spinner {
            width: 48px; height: 48px;
            border: 5px solid var(--slate-100);
            border-top: 5px solid var(--accent);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        
        /* Agreement Box */
        .agreement-box {
            background: var(--slate-50);
            border: 1px solid var(--slate-200);
            border-radius: 20px;
            padding: 32px;
            margin-bottom: 32px;
            text-align: left;
            white-space: pre-wrap;
            font-size: 14px;
            color: var(--slate-600);
            border-left: 4px solid var(--accent);
        }
        .agreement-box h3 {
            color: var(--slate-900);
            margin-bottom: 16px;
            font-size: 18px;
            font-weight: 800;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 32px;
            padding: 16px;
            background: var(--accent-soft);
            border: 1px solid var(--accent);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .checkbox-container:hover { background: rgba(37, 99, 235, 0.15); }
        .checkbox-container input { width: 20px; height: 20px; cursor: pointer; }
        .checkbox-label { font-size: 14px; font-weight: 700; color: var(--accent); cursor: pointer; }

        @media (max-width: 768px) {
            .summary-grid { grid-template-columns: 1fr 1fr; }
            .glass-header { flex-direction: column; text-align: center; gap: 24px; }
            .ref-badge { text-align: center; }
            .items-table th:nth-child(2), .items-table td:nth-child(2) { display: none; }
        }

        @media print {
            body { background: white !important; padding-bottom: 0; }
            .app-container { margin: 0; padding: 0; max-width: none; }
            .print-btn, .auth-form { display: none !important; }
            .glass-header, .specs-card, .summary-dashboard { box-shadow: none !important; border: 1px solid #000 !important; }
            .glass-header::before { display: none !important; }
            .acceptance-section { page-break-inside: avoid; }
        }
    </style>
</head>
<body>

<div id="loading-overlay">
    <div class="spinner"></div>
    <p style="margin-top: 20px; font-weight: 800; color: var(--slate-900);">Finalizing Agreement...</p>
</div>

<div class="app-container">
    {{-- Portal Header --}}
    <header class="glass-header">
        <div class="brand-section">
            <p>OFFICIAL QUOTATION PORTAL</p>
            <h1>MODU <span>SHADE</span></h1>
            <p>Premium Architectural Solutions Registry</p>
        </div>
        <div class="ref-badge">
            <div class="ref-label">PROPOSAL REFERENCE</div>
            <div class="ref-number">#{{ $quote->quote_number }}</div>
            <div class="ref-date">Expires: {{ $quote->expiry_date->format('M d, Y') }}</div>
            <button class="print-btn" onclick="window.print()" style="margin-top: 12px; padding: 8px 16px; background: var(--slate-100); border: 1px solid var(--slate-300); border-radius: 8px; font-weight: 700; color: var(--slate-700); cursor: pointer; display: inline-flex; align-items: center; gap: 8px; font-size: 13px;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print Proposal
            </button>
        </div>
    </header>

    @if(session('success'))
        <div class="specs-card" style="border-color: #10b981;">
            <div class="success-hero">
                <div class="success-icon">✓</div>
                <h2 style="font-size: 32px; font-weight: 950; margin-bottom: 8px;">Agreement Secured</h2>
                <p style="color: var(--slate-500); margin-bottom: 40px;">Professional Proposal #{{ $quote->quote_number }} has been digitally authorized.</p>
                
                <div style="background: var(--slate-50); padding: 40px; border-radius: 24px; border: 1px solid var(--slate-200); display: inline-block; min-width: 400px; text-align: left;">
                    <div class="input-label">SIGNATORY IDENTITY</div>
                    <div style="font-size: 20px; font-weight: 800; color: var(--slate-900);">{{ $quote->signature_name }}</div>
                    
                    <div class="row" style="display: flex; gap: 40px; margin-top: 24px;">
                        <div>
                            <div class="input-label">AUTHORIZED AT</div>
                            <div style="font-weight: 700; color: var(--slate-700);">{{ $quote->signed_at?->format('F d, Y — h:i A') }}</div>
                        </div>
                        <div>
                            <div class="input-label">IP ADDRESS</div>
                            <div style="font-weight: 700; color: var(--slate-700);">{{ $quote->signature_ip }}</div>
                        </div>
                    </div>

                    @if($quote->signature_data)
                        <div class="input-label" style="margin-top: 24px;">DIGITAL AUTHORIZATION</div>
                        <img src="{{ $quote->signature_data }}" style="height: 100px; display: block; margin-top: 10px; filter: grayscale(1) contrast(1.2);">
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- Items Overview --}}
    @if(!session('success'))
    <div class="specs-card">
        <div class="specs-header">
            <h2><i>𐇪</i> Technical Specifications</h2>
        </div>
        
        <table class="items-table">
            <thead>
                <tr>
                    <th>Configuration Breakdown</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Valuation</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quote->items as $item)
                <tr>
                    <td>
                        <div class="item-main">
                            <div class="product-title">{{ $item->product_name }}</div>
                            
                            <div class="attribute-grid">
                                <div class="attr-box">
                                    <div class="attr-label">Dimensions</div>
                                    <div class="attr-value">{{ $item->width }} x {{ $item->height }}</div>
                                </div>
                                @if($item->options_json)
                                    @foreach($item->options_json as $key => $value)
                                        @if($key !== 'installation_note')
                                        <div class="attr-box">
                                            <div class="attr-label">{{ str_replace('_', ' ', $key) }}</div>
                                            <div class="attr-value">{{ $value }}</div>
                                        </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>

                            @if(isset($item->options_json['installation_note']))
                                <div style="background: #fffbeb; border: 1px solid #fef3c7; padding: 12px 16px; border-radius: 10px; font-size: 13px; color: #92400e;">
                                    <strong>Installation Note:</strong> {{ $item->options_json['installation_note'] }}
                                </div>
                            @endif
                        </div>
                    </td>
                    <td style="text-align: center; font-weight: 800; font-size: 18px; color: var(--slate-900);">{{ $item->quantity }}</td>
                    <td class="valuation-cell">
                        <div class="unit-price">@ ${{ number_format($item->unit_price, 2) }}</div>
                        <div class="line-total">${{ number_format($item->subtotal, 2) }}</div>
                        <div style="font-size: 11px; color: var(--slate-400); font-weight: 700;">Inc. VAT (${{ number_format($item->vat_amount, 2) }})</div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Summary Dashboard --}}
    <div class="summary-dashboard">
        <div class="summary-grid">
            <div class="summary-box">
                <span class="label">Gross Subtotal</span>
                <span class="value">${{ number_format($quote->subtotal, 2) }}</span>
            </div>
            <div class="summary-box">
                <span class="label">Aggregate VAT</span>
                <span class="value">${{ number_format($quote->vat_amount, 2) }}</span>
            </div>
            <div class="summary-box" style="background: #fffbeb; border-color: #fef3c7;">
                <span class="label" style="color: #92400e;">Total Discount</span>
                <span class="value" style="color: #92400e;">-${{ number_format($quote->discount, 2) }}</span>
            </div>
            <div class="summary-box total-payable-box">
                <span class="label">Net Total Payable</span>
                <span class="value">${{ number_format($quote->total_amount, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- Acceptance Flow --}}
    <div class="acceptance-section">
           <div class="agreement-box">
                    <h3>Service Agreement & Terms</h3>
                    {!! nl2br(e($quote->terms_conditions ?: \App\Models\Setting::get('quote_service_agreement', 'Default Service Agreement text will appear here.'))) !!}
                </div>
        @if($quote->status === 'accepted')
             <div class="success-icon" style="background: var(--accent);">✓</div>
             <h2>Signed & Authorized</h2>
             <p>This proposal has already been digitally authorized by {{ $quote->signature_name }}.</p>
        @else
            <h2>Digital Authorization</h2>
            <p>Review the specifications and financials above. By signing below, you authorize the project to move into the manufacturing and logistics phase.</p>
            
           
            <form id="acceptance-form" class="auth-form" method="POST" action="{{ route('quote.accept', $quote->client_token) }}">
                @csrf
                <input type="hidden" name="signature_data" id="signature_data">

                {{-- Service Agreement Display --}}
              

                {{-- Mandatory Checkbox --}}
                <label class="checkbox-container" for="agree_terms">
                    <input type="checkbox" id="agree_terms" required>
                    <span class="checkbox-label">I have read and agree to the Service Agreement above.</span>
                </label>
                
                <div class="input-group">
                    <label class="input-label">Full Legal Name</label>
                    <input type="text" name="name" class="premium-input" required placeholder="Your full name as signature">
                </div>

                <div class="input-group">
                    <label class="input-label">Digital Signature</label>
                    <div class="signature-wrapper">
                        <div class="sig-hint" id="sig-hint">Draw Signature Here</div>
                        <canvas id="signature-pad"></canvas>
                    </div>
                </div>

                <div style="display: flex; gap: 16px;">
                    <button type="button" id="clear-pad" style="flex: 0.3; background: var(--slate-100); border: none; padding: 16px; border-radius: 16px; font-weight: 800; color: var(--slate-500); cursor: pointer;">Clear</button>
                    <button type="submit" class="btn-authorize">Authorize & Sign Proposal ➔</button>
                </div>
            </form>
        @endif
    </div>
    @endif

    <footer style="margin-top: 60px; text-align: center; color: var(--slate-400); font-size: 11px; font-weight: 800; letter-spacing: 1px;">
        MODU SHADE OFFICIAL • SECURE ARCHITECTURAL REGISTRY • ENCRYPTED AUDIT LOGS
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('signature-pad');
        if (!canvas) return;

        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgba(255, 255, 255, 0)',
            penColor: '#0f172a'
        });

        const hint = document.getElementById('sig-hint');
        signaturePad.onBegin = () => { if(hint) hint.style.display = 'none'; };

        function resizeCanvas() {
            const ratio =  Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            signaturePad.clear();
            if(hint) hint.style.display = 'block';
        }
        
        window.addEventListener("resize", resizeCanvas);
        resizeCanvas();

        document.getElementById('clear-pad').addEventListener('click', () => {
            signaturePad.clear();
            if(hint) hint.style.display = 'block';
        });

        const form = document.getElementById('acceptance-form');
        form.addEventListener('submit', function(e) {
            const agreeCheckbox = document.getElementById('agree_terms');
            if (agreeCheckbox && !agreeCheckbox.checked) {
                alert("You must agree to the Service Agreement before signing.");
                e.preventDefault();
                return;
            }
            if (signaturePad.isEmpty()) {
                alert("Please provide a digital signature to authorize this proposal.");
                e.preventDefault();
                return;
            }
            document.getElementById('loading-overlay').style.display = 'flex';
            document.getElementById('signature_data').value = signaturePad.toDataURL();
        });
    });
</script>

</body>
</html>
