ok @extends('layouts.admin')
@section('title', 'Proposals Registry')
@section('content')

<div style="max-width: 1400px; margin: 0 auto;">
    {{-- High-End Header --}}
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 32px; padding-bottom: 24px; border-bottom: 1.5px solid var(--border);">
        <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                <span style="background: var(--accent); color: white; padding: 4px 12px; border-radius: 20px; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px;">Official Bids</span>
                <span style="color: var(--muted); font-size: 13px; font-weight: 600;">{{ $quotations->count() }} Total Proposals</span>
            </div>
            <h1 style="font-size: 32px; font-weight: 950; color: var(--text); letter-spacing: -1px; line-height: 1;">Project Proposals</h1>
            <p style="color: var(--muted); font-size: 14px; margin-top: 8px; font-weight: 500;">Review, track, and manage all active client bids and digital signatures.</p>
        </div>
        
        <button onclick="document.getElementById('manualQuoteModal').style.display='flex'" class="btn btn-primary" style="padding: 12px 28px; border-radius: 12px; font-weight: 800; font-size: 14px; box-shadow: 0 10px 15px -3px rgba(37,99,235,0.2);">
            ✨ Generate New Proposal
        </button>
    </div>

    {{-- Main Registry Table --}}
    <div class="card" style="padding: 0; overflow: hidden; border: 1.5px solid var(--border); border-radius: 20px; box-shadow: var(--shadow); background: #fff;">
        <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
            <thead>
                <tr style="background: var(--surface2);">
                    <th style="padding: 18px 24px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--muted); border-bottom: 2px solid var(--border);">Proposal ID</th>
                    <th style="padding: 18px 24px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--muted); border-bottom: 2px solid var(--border);">Customer & Project</th>
                    <th style="padding: 18px 24px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--muted); border-bottom: 2px solid var(--border);">Valuation</th>
                    <th style="padding: 18px 24px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--muted); border-bottom: 2px solid var(--border);">Bid Status</th>
                    <th style="padding: 18px 24px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--muted); border-bottom: 2px solid var(--border);">Validity</th>
                    <th style="padding: 18px 24px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--muted); border-bottom: 2px solid var(--border); text-align: right;">Command</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quotations as $q)
                <tr>
                    <td style="padding: 20px 24px;">
                        <div style="font-weight: 900; color: var(--accent); font-size: 15px; letter-spacing: -0.5px;">#{{ $q->quote_number }}</div>
                        <div style="font-size: 11px; color: var(--muted); margin-top: 2px; font-weight: 800;">REF-{{ $q->id }}</div>
                    </td>
                    <td style="padding: 20px 24px;">
                        <div style="font-weight: 800; color: var(--text); font-size: 15px;">{{ $q->lead?->name }}</div>
                        <div style="font-size: 12px; color: var(--muted); margin-top: 2px; font-weight: 500;">
                            📍 {{ $q->lead?->city ?: 'Site Project' }} — {{ Str::limit($q->lead?->shades_needed ?: 'Custom Shades', 20) }}
                        </div>
                    </td>
                    <td style="padding: 20px 24px;">
                        <div style="font-weight: 900; color: var(--gold); font-size: 18px; letter-spacing: -1px;">
                            ${{ number_format($q->total_amount, 2) }}
                        </div>
                        <div style="font-size: 10px; font-weight: 800; color: var(--muted); text-transform: uppercase;">Current Proposal</div>
                    </td>
                    <td style="padding: 20px 24px;">
                        <form method="POST" action="{{ route('admin.quotations.status-update', $q->id) }}" id="status-form-{{ $q->id }}">
                            @csrf @method('PUT')
                            @php
                                $statusColors = [
                                    'draft' => ['bg' => 'var(--surface2)', 'text' => 'var(--muted)', 'icon' => '⏳'],
                                    'sent' => ['bg' => '#dbeafe', 'text' => '#1e40af', 'icon' => '✉'],
                                    'accepted' => ['bg' => '#d1fae5', 'text' => '#065f46', 'icon' => '✓'],
                                    'rejected' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'icon' => '❌'],
                                    'converted' => ['bg' => '#ede9fe', 'text' => '#5b21b6', 'icon' => '🎯'],
                                ];
                                $c = $statusColors[$q->status] ?? $statusColors['draft'];
                            @endphp
                            <div style="position: relative; display: inline-block;">
                                <select name="status" onchange="this.form.submit()" 
                                    style="appearance: none; background: {{ $c['bg'] }}; color: {{ $c['text'] }}; border: 1.5px solid rgba(0,0,0,0.05); padding: 6px 32px 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 900; text-transform: uppercase; cursor: pointer; transition: all 0.2s;">
                                    <option value="draft" {{ $q->status === 'draft' ? 'selected' : '' }}>⏳ Draft</option>
                                    <option value="sent" {{ $q->status === 'sent' ? 'selected' : '' }}>✉ Sent</option>
                                    <option value="accepted" {{ $q->status === 'accepted' ? 'selected' : '' }}>✓ Accepted</option>
                                    <option value="rejected" {{ $q->status === 'rejected' ? 'selected' : '' }}>❌ Rejected</option>
                                    <option value="converted" {{ $q->status === 'converted' ? 'selected' : '' }} disabled>🎯 Converted</option>
                                </select>
                                <div style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 10px; opacity: 0.5;">▼</div>
                            </div>
                        </form>
                        @if($q->signature_data)
                            <div style="margin-top: 5px; font-size: 9px; font-weight: 900; color: var(--success); display: flex; align-items: center; gap: 4px;">
                                <span style="background: var(--success); color: white; width: 14px; height: 14px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 8px;">✓</span>
                                DIGITALLY SIGNED
                            </div>
                        @endif
                    </td>
                    <td style="padding: 20px 24px;">
                        <div style="font-weight: 700; color: var(--text); font-size: 13px;">{{ $q->expiry_date?->format('M d, Y') }}</div>
                        <div style="font-size: 10px; font-weight: 800; color: {{ $q->expiry_date && $q->expiry_date->isPast() ? 'var(--danger)' : 'var(--success)' }}; text-transform: uppercase;">
                            {{ $q->expiry_date && $q->expiry_date->isPast() ? 'Expired' : 'Active Bid' }}
                        </div>
                    </td>
                    <td style="padding: 20px 24px; text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 8px;">
                            @if($q->client_token)
                                <button onclick="copyToClipboard('{{ route('quote.client', $q->client_token) }}')" class="btn-tool btn-tool-accent" title="Copy Digital Sign Link">🔗</button>
                            @endif
                            <a href="{{ route('admin.quotations.show', $q->id) }}" class="btn-tool" title="Print/View PDF">📄</a>
                            <a href="mailto:{{ $q->lead?->email }}?subject=Official Proposal #{{ $q->quote_number }}" class="btn-tool" title="Email Client">✉</a>
                            
                            @if($q->status !== 'converted')
                                <a href="{{ route('admin.quotations.builder', $q->lead_id) }}" class="btn-tool" title="Refine Bid Dashboard">🛠</a>
                                <a href="{{ route('admin.quotations.convert', $q->id) }}" class="btn-tool btn-tool-success" title="Finalize to Invoice">🎯</a>
                            @endif
                            
                            <form method="POST" action="{{ route('admin.quotations.destroy', $q->id) }}" style="display:inline" onsubmit="return confirm('Archive this proposal?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-tool btn-tool-danger" title="Remove Record">✕</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 80px 40px; text-align: center;">
                        <div style="font-size: 56px; filter: grayscale(1); opacity: 0.3; margin-bottom: 20px;">📄</div>
                        <h4 style="font-size: 20px; font-weight: 950; color: var(--text); letter-spacing: -0.5px;">Registry Empty</h4>
                        <p style="color: var(--muted); font-size: 14px; font-weight: 600;">Start by generating your first project proposal using the button above.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Executive Client Selector Modal (Glassmorphism) --}}
<div id="manualQuoteModal" class="modal-backdrop" onclick="this.style.display='none'">
    <div class="modal-card" onclick="event.stopPropagation()">
        <div class="modal-header-premium">
            <div>
                <h3 style="font-size: 18px; font-weight: 950; color: var(--text); margin-bottom: 4px;">Executive Client Selector</h3>
                <p style="font-size: 12px; color: var(--muted); font-weight: 600;">Identify the project record to start a new bid.</p>
            </div>
            <button onclick="document.getElementById('manualQuoteModal').style.display='none'" class="close-btn-minimal">&times;</button>
        </div>
        
        <div style="padding: 24px;">
            <div style="position: relative; margin-bottom: 24px;">
                <input type="text" id="clientSearchInput" onkeyup="filterManualClients()" placeholder="Search project registry (Name, Phone, or City)..." 
                    style="width: 100%; padding: 16px 20px 16px 48px; background: var(--surface2); border: 1.5px solid var(--border); border-radius: 14px; font-family: inherit; font-size: 14px; font-weight: 700; transition: all 0.2s; outline: none;"
                    onfocus="this.style.borderColor='var(--accent)'; this.style.background='#fff';">
                <span style="position: absolute; left: 18px; top: 16px; font-size: 18px; filter: grayscale(1);">🔍</span>
            </div>

            <div id="manualClientList" style="max-height: 400px; overflow-y: auto; display: grid; gap: 12px; padding-right: 4px;">
                @php $allClients = \App\Models\Lead::latest()->take(50)->get(); @endphp
                @forelse($allClients as $client)
                <div class="client-item-premium">
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <div class="client-avatar">{{ strtoupper(substr($client->name, 0, 1)) }}</div>
                        <div>
                            <div style="font-weight: 800; color: var(--text); font-size: 14px;">{{ $client->name }}</div>
                            <div style="font-size: 11px; color: var(--muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                                #L{{ $client->id }} • {{ $client->city ?: 'On-Site' }}
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.quotations.builder', $client->id) }}" class="bid-build-btn">SELECT & BUILD ➔</a>
                </div>
                @empty
                <div style="text-align: center; padding: 40px; color: var(--muted); font-weight: 700;">No active project records found.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    .btn-tool { width: 34px; height: 34px; background: var(--surface2); border: 1.5px solid var(--border); border-radius: 10px; display: flex; align-items: center; justify-content: center; text-decoration: none; font-size: 14px; transition: all 0.2s; color: var(--muted); }
    .btn-tool:hover { transform: translateY(-2px); border-color: var(--accent); color: var(--accent); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .btn-tool-accent:hover { background: #dbeafe; color: var(--accent); }
    .btn-tool-success:hover { background: #d1fae5; color: var(--success); border-color: var(--success); }
    .btn-tool-danger:hover { background: #fee2e2; color: var(--danger); border-color: var(--danger); }

    .modal-backdrop { display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(10px); z-index: 2000; align-items: center; justify-content: center; padding: 20px; }
    .modal-card { background: #fff; width: 100%; max-width: 600px; border-radius: 28px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.2); animation: modalReveal 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); overflow: hidden; }
    @keyframes modalReveal { from { opacity: 0; transform: scale(0.9) translateY(20px); } to { opacity: 1; transform: scale(1) translateY(0); } }
    
    .modal-header-premium { padding: 24px 28px; border-bottom: 1.5px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: #fafafa; }
    .close-btn-minimal { background: none; border: none; font-size: 28px; color: var(--muted); cursor: pointer; line-height: 1; }
    
    .client-item-premium { background: var(--surface2); border: 1.5px solid var(--border); border-radius: 16px; padding: 14px 20px; display: flex; align-items: center; justify-content: space-between; transition: all 0.2s; }
    .client-item-premium:hover { background: #fff; border-color: var(--accent); transform: translateX(5px); }
    
    .client-avatar { width: 42px; height: 42px; background: rgba(37, 99, 235, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 900; color: var(--accent); font-size: 16px; }
    .bid-build-btn { background: var(--accent); color: #fff; padding: 10px 18px; border-radius: 10px; font-weight: 800; font-size: 11px; text-decoration: none; letter-spacing: 0.5px; transition: all 0.2s; box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2); }
    .bid-build-btn:hover { background: #1d4ed8; transform: scale(1.05); }

    td { transition: background 0.2s; }
    tr:hover td { background: rgba(37, 99, 235, 0.015); }
</style>

<script>
function filterManualClients() {
    let input = document.getElementById('clientSearchInput').value.toLowerCase();
    let items = document.querySelectorAll('.client-item-premium');
    items.forEach(item => {
        let text = item.innerText.toLowerCase();
        item.style.display = text.includes(input) ? 'flex' : 'none';
    });
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert("✓ Proposal Sign Link copied to clipboard!");
    }).catch(err => {
        console.error('Failed to copy: ', err);
    });
}
</script>
@endsection
