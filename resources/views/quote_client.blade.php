<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@if(isset($globalSettings['seo_title'])) {{ $globalSettings['seo_title'] }} | @endif Proposal #{{ $quote->quote_number }} — Modu-Shade Official</title>
    
    @if(isset($globalSettings['seo_description']))
    <meta name="description" content="{{ $globalSettings['seo_description'] }}">
    @endif
    @if(isset($globalSettings['seo_keywords']))
    <meta name="keywords" content="{{ $globalSettings['seo_keywords'] }}">
    @endif

    {!! $globalSettings['header_scripts'] ?? '' !!}

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --accent: #2563eb;
            --accent-dark: #1d4ed8;
            --text: #0f172a;
            --muted: #64748b;
            --border: #e2e8f0;
            --surface: #ffffff;
            --background: #f1f5f9;
            --success: #10b981;
            --shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Outfit', sans-serif; 
            background: var(--background); 
            color: var(--text); 
            line-height: 1.6; 
            padding: 40px 20px;
        }

        .container { 
            max-width: 1000px; 
            margin: 0 auto; 
            background: var(--surface); 
            border-radius: 32px; 
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.1); 
            overflow: hidden; 
            border: 1px solid var(--border);
            position: relative;
        }

        /* Portal Header */
        .portal-header { 
            background: #1e293b; 
            padding: 60px 40px; 
            color: #fff; 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            background-image: radial-gradient(circle at 20% 150%, rgba(37,99,235,0.2) 0%, transparent 50%);
        }
        .logo-group h1 { font-size: 32px; font-weight: 900; letter-spacing: -1.5px; margin-bottom: 4px; }
        .logo-group p { font-size: 14px; font-weight: 500; color: #94a3b8; }
        
        .status-badge {
            background: rgba(16,185,129,0.1);
            color: #10b981;
            padding: 8px 20px;
            border-radius: 100px;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            border: 1px solid rgba(16,185,129,0.2);
        }

        /* Content Sections */
        .section { padding: 50px 40px; border-bottom: 1px solid var(--border); }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; }
        
        h3 { 
            font-size: 11px; 
            text-transform: uppercase; 
            letter-spacing: 2.5px; 
            color: var(--muted); 
            margin-bottom: 20px; 
            font-weight: 900; 
        }

        .info-card { background: #f8fafc; padding: 24px; border-radius: 20px; border: 1px solid var(--border); }
        .info-label { font-size: 13px; color: var(--muted); margin-bottom: 4px; }
        .info-value { font-size: 18px; font-weight: 700; color: var(--text); }

        /* Tables */
        .table-container { margin-top: 30px; border-radius: 20px; overflow: hidden; border: 1px solid var(--border); }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f8fafc; text-align: left; padding: 20px; font-size: 12px; text-transform: uppercase; color: var(--muted); font-weight: 900; }
        td { padding: 24px 20px; border-top: 1px solid var(--border); vertical-align: middle; }
        .item-name { font-weight: 800; font-size: 16px; margin-bottom: 2px; }
        .item-dims { font-size: 13px; color: var(--muted); font-weight: 500; }
        
        .grand-total { 
            background: #f1f5f9; 
            padding: 40px; 
            text-align: right; 
            display: flex; 
            justify-content: flex-end; 
            align-items: center; 
            gap: 40px;
        }
        .total-label { font-size: 14px; font-weight: 800; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; }
        .total-amount { font-size: 42px; font-weight: 900; color: var(--accent); letter-spacing: -2px; }

        /* Acceptance Hub */
        .acceptance-portal { 
            max-width: 600px; 
            margin: 0 auto; 
            text-align: center;
        }
        
        .form-group { text-align: left; margin-bottom: 24px; }
        .label { display: block; font-size: 12px; font-weight: 900; color: var(--muted); text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px; }
        
        .input {
            width: 100%;
            padding: 18px 24px;
            border-radius: 16px;
            border: 2px solid var(--border);
            font-family: inherit;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.2s;
            outline: none;
            background: #fff;
        }
        .input:focus { border-color: var(--accent); box-shadow: 0 0 0 4px rgba(37,99,235,0.1); }

        .signature-box { 
            background: #fff; 
            border: 2px solid var(--border); 
            border-radius: 16px; 
            margin-bottom: 24px;
            position: relative;
            height: 240px;
        }
        #signature-pad { width: 100%; height: 100%; touch-action: none; cursor: crosshair; }
        
        .sign-watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 14px;
            font-weight: 800;
            color: #cbd5e1;
            pointer-events: none;
            text-transform: uppercase;
            letter-spacing: 4px;
        }

        .actions { display: flex; gap: 16px; }
        .btn { 
            flex: 1;
            padding: 18px 32px; 
            border-radius: 16px; 
            border: none; 
            font-weight: 800; 
            cursor: pointer; 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
            font-family: inherit; 
            font-size: 16px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            gap: 10px;
        }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-primary:hover { background: var(--accent-dark); transform: translateY(-3px); box-shadow: 0 20px 25px -5px rgba(37,99,235,0.3); }
        .btn-secondary { background: #f1f5f9; color: var(--muted); flex: 0.4; }
        .btn-secondary:hover { background: #e2e8f0; color: var(--text); }

        /* Loading Overlay */
        #loading-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(255,255,255,0.9);
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 100;
            backdrop-filter: blur(8px);
        }
        .spinner {
            width: 40px; height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--accent);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 16px;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

        /* Desktop specific */
        @media (max-width: 768px) {
            .grid { grid-template-columns: 1fr; gap: 30px; }
            .portal-header { flex-direction: column; gap: 20px; text-align: center; }
            .grand-total { flex-direction: column; gap: 10px; text-align: center; }
        }
    </style>
</head>
<body>
{!! $globalSettings['body_scripts'] ?? '' !!}

<div class="container" id="portal-container">
    {{-- Submission Overlay --}}
    <div id="loading-overlay">
        <div class="spinner"></div>
        <p style="font-weight: 800; color: var(--text);">Securing Agreement...</p>
    </div>

    {{-- Portal Header --}}
    <div class="portal-header">
        <div class="logo-group">
            <p>PROJECT PROPOSAL</p>
            <h1>MODU SHADE OFFICIAL</h1>
            <p>Precision Engineering • Premium Aesthetics</p>
        </div>
        <div style="text-align: right;">
            <div style="font-size: 11px; opacity: 0.6; font-weight: 900; margin-bottom: 4px;">REFERENCE NUMBER</div>
            <div style="font-size: 28px; font-weight: 900; letter-spacing: -1px;">#{{ $quote->quote_number }}</div>
            <div style="font-size: 13px; font-weight: 600; color: #94a3b8; margin-top: 5px;">
                Valid Until: {{ $quote->expiry_date->format('F d, Y') }}
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="section" style="background: rgba(16,185,129,0.02); text-align: center;">
            <div style="font-size: 64px; margin-bottom: 20px;">🎖️</div>
            <h2 style="font-size: 32px; font-weight: 900; margin-bottom: 12px;">Agreement Fully Secured</h2>
            <p style="color: var(--muted); font-size: 18px; max-width: 600px; margin: 0 auto 32px;">
                Thank you for choosing Modu Shade. Your proposal has been digitally signed and logged. Our team is now preparing the next steps for your project.
            </p>
            <div class="info-card" style="display: inline-block; padding: 30px 60px;">
                <div class="info-label">SIGNATORY</div>
                <div class="info-value" style="font-size: 24px;">{{ $quote->signature_name }}</div>
                <div class="info-label" style="margin-top: 16px;">SIGNED AT</div>
                <div class="info-value">{{ $quote->signed_at?->format('M d, Y — h:i A') }}</div>
                @if($quote->signature_data)
                    <img src="{{ $quote->signature_data }}" style="display: block; margin: 30px auto 0; max-height: 120px; filter: contrast(1.1);">
                @endif
            </div>
        </div>
    @endif

    {{-- Details Section --}}
    <div class="section">
        <div class="grid">
            <div class="info-card">
                <h3>Client Details</h3>
                <div class="info-label">FULL NAME</div>
                <div class="info-value">{{ $quote->lead->name }}</div>
                <div class="info-label" style="margin-top: 16px;">CONTACT PHONE</div>
                <div class="info-value">{{ $quote->lead->phone }}</div>
                <div class="info-label" style="margin-top: 16px;">PROJECT SOURCE</div>
                <div class="info-value" style="text-transform: capitalize;">{{ $quote->lead->source ?: 'Direct Client' }}</div>
            </div>
            <div class="info-card">
                <h3>Agency Details</h3>
                <div class="info-label">OFFICIAL PARTNER</div>
                <div class="info-value">Modu Shade Official</div>
                <div class="info-label" style="margin-top: 16px;">SUPPORT CHANNEL</div>
                <div class="info-value">info@modu-shade.com</div>
                <div class="info-label" style="margin-top: 16px;">SECURE PORTAL IP</div>
                <div class="info-value">{{ request()->ip() }}</div>
            </div>
        </div>
    </div>

    {{-- Estimations Section --}}
    @if(!session('success'))
    <div class="section">
        <h3>Project Estimations</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Solution Description</th>
                        <th style="text-align: center;">Unit Dimensions</th>
                        <th style="text-align: center;">Quantity</th>
                        <th style="text-align: right;">Valuation</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quote->items as $item)
                    <tr>
                        <td>
                            <div class="item-name">{{ $item->product_name }}</div>
                            <div class="item-dims">Premium Structural Grade Architectural Solution</div>
                        </td>
                        <td style="text-align: center;">
                            <span style="background: #f1f5f9; padding: 4px 10px; border-radius: 6px; font-weight: 700;">
                                {{ $item->width }} x {{ $item->height }}
                            </span>
                        </td>
                        <td style="text-align: center; font-weight: 800;">{{ $item->quantity }}</td>
                        <td style="text-align: right; font-weight: 900; color: var(--text);">
                            ${{ number_format($item->subtotal, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="grand-total">
        <div class="total-label">Total Project Valuation</div>
        <div class="total-amount">${{ number_format($quote->total_amount, 2) }}</div>
    </div>

    {{-- Signature Portal --}}
    <div class="section" style="background: #f8fafc;">
        <div class="acceptance-portal">
            <h2 style="font-size: 28px; font-weight: 950; margin-bottom: 8px;">Final Acceptance</h2>
            <p style="color: var(--muted); margin-bottom: 40px; font-weight: 500;">By signing below, you agree to the project specifications and valuation listed above.</p>
            
            @if($quote->status === 'accepted')
                {{-- Already handled in the top success block if session just happened, 
                     but here for direct page loads of signed quotes --}}
                 <div style="padding: 20px; border: 2px solid var(--accent); border-radius: 20px; background: #fff;">
                    <span class="status-badge" style="margin-bottom: 10px; display: inline-block;">Accepted & Signed</span>
                    <p style="font-weight: 800;">Digitally Authorized by {{ $quote->signature_name }}</p>
                 </div>
            @else
                <form id="acceptance-form" method="POST" action="{{ route('quote.accept', $quote->client_token) }}">
                    @csrf
                    <input type="hidden" name="signature_data" id="signature_data">
                    
                    <div class="form-group">
                        <label class="label">Full Legal Name</label>
                        <input type="text" name="name" class="input" required placeholder="Type your name as it should appear on the agreement">
                    </div>

                    <div class="form-group">
                        <label class="label">Digital Signature</label>
                        <div class="signature-box">
                            <div class="sign-watermark" id="sign-hint">Draw Signature Here</div>
                            <canvas id="signature-pad"></canvas>
                        </div>
                    </div>
                    
                    <div class="actions">
                        <button type="button" class="btn btn-secondary" id="clear-btn">Clear Canvas</button>
                        <button type="submit" class="btn btn-primary">Authorize & Submit ➔</button>
                    </div>
                </form>
            @endif
        </div>
    </div>
    @endif

    <div style="padding: 40px; text-align: center; background: #f8fafc; border-top: 1px solid var(--border);">
        <p style="font-size: 12px; color: var(--muted); font-weight: 700; letter-spacing: 0.5px;">
            MODU SHADE OFFICIAL • DIGITAL TRUST PORTAL • ENCRYPTED AUDIT LOG
        </p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('signature-pad');
        if (!canvas) return;

        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgba(255, 255, 255, 0)',
            penColor: '#0f172a',
            velocityFilterWeight: 0.7,
            minWidth: 1.5,
            maxWidth: 4.5
        });

        // Hide watermark when signing
        const hint = document.getElementById('sign-hint');
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

        document.getElementById('clear-btn').addEventListener('click', () => {
            signaturePad.clear();
            if(hint) hint.style.display = 'block';
        });

        const form = document.getElementById('acceptance-form');
        form.addEventListener('submit', function(e) {
            if (signaturePad.isEmpty()) {
                alert("Please provide a digital signature to authorize this proposal.");
                e.preventDefault();
                return;
            }
            
            // Show loading state
            document.getElementById('loading-overlay').style.display = 'flex';
            document.getElementById('signature_data').value = signaturePad.toDataURL();
        });
    });
</script>

    {!! $globalSettings['footer_scripts'] ?? '' !!}
</body>
</html>
