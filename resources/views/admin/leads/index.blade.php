@extends('layouts.admin')
@section('title','Leads')
@section('content')
<style>
    .glass-card { background: rgba(255, 255, 255, 0.7) !important; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2) !important; }
    .btn-premium { display: inline-flex; align-items: center; gap: 8px; font-weight: 600; transition: all 0.3s; }
    .btn-premium:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2); }
    .form-label-sm { font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b; margin-bottom: 4px; display: block; letter-spacing: 0.5px; }
    .form-input-premium { background: #fff !important; border: 1.5px solid #e2e8f0 !important; border-radius: 8px !important; padding: 10px 14px !important; transition: all 0.2s !important; }
    .form-input-premium:focus { border-color: #0d6efd !important; box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1) !important; }
</style>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
    <h2 style="font-size:18px;font-weight:600">Lead Management</h2>
    <button type="button" data-bs-toggle="modal" data-bs-target="#addLeadModal" class="btn btn-primary btn-premium">
        <i class="fas fa-plus-circle"></i> Add New Lead
    </button>
</div>

    {{-- Funnel Tabs --}}
    <div style="display:flex;gap:4px;background:var(--surface);padding:4px;border-radius:10px;border:1px solid var(--border);margin-bottom:20px;overflow-x:auto;">
        @php
            $stages = [
                'all'                  => 'All Leads',
                'new_lead'             => 'New Leads',
                'contacted'            => 'Contacted',
                'site_visit_scheduled' => 'Appointment',
                'negotiation'          => 'Negotiation',
                'deal_won'             => 'Won',
                'lost'                 => 'Lost'
            ];
            $current = request('status', 'all');
        @endphp
        @foreach($stages as $key => $label)
            <a href="{{ route('admin.leads.index', array_merge(request()->query(), ['status' => ($key == 'all' ? '' : $key)])) }}" 
               class="nav-link" 
               style="margin:0;padding:8px 16px;font-weight:500;white-space:nowrap;{{ ($current == $key || ($current=='' && $key=='all')) ? 'background:var(--accent);color:#000;' : '' }}">
                {{ $label }} 
                <span style="font-size:10px;padding:2px 6px;background:rgba(0,0,0,0.1);border-radius:10px;margin-left:4px;">{{ $counts[$key] ?? 0 }}</span>
            </a>
        @endforeach
    </div>

    {{-- Filters Row --}}
    <form method="GET" style="display:flex;gap:12px;margin-bottom:20px;align-items:center;flex-wrap:wrap">
        <input type="hidden" name="status" value="{{ request('status') }}">
        <div style="flex:1;min-width:240px">
            <input class="form-control" name="search" value="{{ request('search') }}" placeholder="Search name, phone, or email...">
        </div>
        <input class="form-control" style="width:140px" type="date" name="from_date" value="{{ request('from_date') }}">
        <input class="form-control" style="width:140px" type="date" name="to_date" value="{{ request('to_date') }}">
        <button class="btn btn-primary" type="submit">Filter Leads</button>
        <a href="{{ route('admin.leads.index') }}" class="btn" style="background:var(--surface2);color:var(--muted)">Reset</a>
    </form>

<div class="card" style="padding:0;overflow:hidden">
    <div class="table-responsive">
    <table>
        <thead><tr><th>#</th><th>Name</th><th>Phone</th><th>Shades</th><th>Source</th><th>Score</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
        <tbody>
        @forelse($leads as $lead)
        <tr>
            <td style="color:var(--muted)">{{ $lead->id }}</td>
            <td><a href="{{ route('admin.leads.show',$lead->id) }}" style="color:var(--accent);text-decoration:none">{{ $lead->name }}</a></td>
            <td>{{ $lead->phone }}</td>
            <td style="color:var(--muted)">{{ Str::limit($lead->shades_needed,25) }}</td>
            <td style="color:var(--muted)">{{ $lead->source }}</td>
            <td><span style="color:{{ $lead->lead_score>80?'#3fb950':($lead->lead_score>40?'#d29922':'#8b949e') }}">{{ $lead->lead_score }}</span></td>
            <td>
                <form method="POST" action="{{ route('admin.leads.update', $lead->id) }}" style="display:inline;">
                    @csrf @method('PUT')
                    <select name="status" onchange="this.form.submit()" class="form-control-minimal" style="width:165px; height:32px; font-size:11px; font-weight:800; padding:2px 8px; border:2px solid var(--accent); border-radius:6px; background:rgba(37,99,235,0.03); color:var(--accent); cursor:pointer;">
                        <option value="new_lead" {{ $lead->status === 'new_lead' ? 'selected' : '' }}>🎯 NEW LEAD</option>
                        <option value="contacted" {{ $lead->status === 'contacted' ? 'selected' : '' }}>📻 CONTACTED</option>
                        <option value="site_visit_scheduled" {{ $lead->status === 'site_visit_scheduled' ? 'selected' : '' }}>📅 APPOINTMENT</option>
                        <option value="quotation_sent" {{ $lead->status === 'quotation_sent' ? 'selected' : '' }}>📄 QUOTE SENT</option>
                        <option value="invoice_sent" {{ $lead->status === 'invoice_sent' ? 'selected' : '' }}>🧾 INVOICE SENT</option>
                        <option value="negotiation" {{ $lead->status === 'negotiation' ? 'selected' : '' }}>🤝 NEGOTIATION</option>
                        <option value="deal_won" {{ $lead->status === 'deal_won' ? 'selected' : '' }}>🏆 WON</option>
                        <option value="lost" {{ $lead->status === 'lost' ? 'selected' : '' }}>❌ LOST</option>
                    </select>
                </form>
            </td>
            <td style="color:var(--muted)">{{ $lead->created_at?->format('M d, Y') }}</td>
            <td style="white-space:nowrap">
                <a href="tel:{{ $lead->phone }}" class="btn btn-sm" title="Call" style="background:#238636;color:#fff">☎</a>
                @if($lead->email)
                <a href="mailto:{{ $lead->email }}" class="btn btn-sm" title="Email" style="background:#d4a017;color:#000">@</a>
                @endif
                <button type="button" class="btn btn-sm" title="SMS" style="background:#58a6ff;color:#000" onclick="openSmsModal({{ $lead->id }}, '{{ $lead->phone }}', '{{ $lead->name }}')">✉</button>
                <a href="{{ route('admin.leads.show',$lead->id) }}" class="btn btn-sm" title="Edit/View" style="background:var(--surface2)">✎</a>
                <a href="{{ route('admin.leads.advance',$lead->id) }}" class="btn btn-sm" title="Advance Funnel" style="background:rgba(88,166,255,.15);color:var(--accent)">⇑</a>
                <form method="POST" action="{{ route('admin.leads.destroy',$lead->id) }}" style="display:inline" onsubmit="return confirm('Delete?')">
                    @csrf @method('DELETE')<button class="btn btn-sm btn-danger" type="submit" title="Delete">✕</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="9" style="text-align:center;color:var(--muted);padding:60px">
                <div style="font-size:40px;margin-bottom:12px">🔍</div>
                <div style="font-weight:700;font-size:18px;color:var(--text);margin-bottom:8px">No leads match these filters</div>
                <p style="font-size:13px;margin:0 0 24px">Try adjusting your search criteria or date range to find more results.</p>
                <a href="{{ route('admin.leads.index') }}" class="btn btn-primary" style="padding:10px 32px;font-weight:600">Clear All Filters</a>
            </td>
        </tr>
        @endforelse
        </tbody>
    </table>
    </div>
</div>
<div style="margin-top:12px">{{ $leads->links() }}</div>

{{-- Add Lead Modal (Bootstrap Native) --}}
<div class="modal fade" id="addLeadModal" tabindex="-1" aria-labelledby="addLeadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content glass-card border-0" style="border-radius:16px; overflow:hidden;">
            <div class="modal-header border-0 pt-4 px-4 pb-0" style="background:transparent;">
                <h3 class="modal-title fw-800" id="addLeadModalLabel" style="color:#1e293b">Create New Lead</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4">
                <form method="POST" action="{{ route('admin.leads.store') }}">@csrf
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label-sm">Full Name</label>
                            <input class="form-control form-input-premium" name="name" placeholder="John Doe" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label-sm">Phone Number</label>
                            <input class="form-control form-input-premium" name="phone" placeholder="+1..." required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label-sm">Email Address</label>
                            <input class="form-control form-input-premium" name="email" type="email" placeholder="john@example.com">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label-sm">City</label>
                            <input class="form-control form-input-premium" name="city" placeholder="City name">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label-sm">Zip Code</label>
                            <input class="form-control form-input-premium" name="zip_code" placeholder="12345">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label-sm">Windows Count</label>
                            <input class="form-control form-input-premium" name="windows_count" type="number" placeholder="5">
                        </div>
                        <div class="col-12">
                            <label class="form-label-sm">Project / Product Interest</label>
                            <input class="form-control form-input-premium" name="shades_needed" placeholder="e.g. Roller Shades, Motorized Blinds">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label-sm">Budget Estimate</label>
                            <input class="form-control form-input-premium" name="budget" placeholder="e.g. $2000">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label-sm">Campaign Name</label>
                            <input class="form-control form-input-premium" name="campaign" placeholder="Optional">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label-sm">Lead Source</label>
                            <select class="form-control form-input-premium" name="source">
                                <option value="Manual Entry (Admin / Staff)">Manual Entry (Admin / Staff)</option>
                                <option value="Landing Page Form">Landing Page Form</option>
                                <option value="Meta Lead Form (Facebook / Instagram)">Meta Lead Form (Facebook / Instagram)</option>
                                <option value="Other Sources (Google Ads / Referral)">Other Sources (Google Ads / Referral)</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label-sm">Full Address</label>
                            <input class="form-control form-input-premium" name="address" placeholder="Street layout">
                        </div>
                    </div>
                    
                    <div style="display:flex;gap:12px;margin-top:32px">
                        <button class="btn btn-primary btn-premium py-2" type="submit" style="flex:1;justify-content:center">
                            <i class="fas fa-check-circle"></i> Save Lead Record
                        </button>
                        <button type="button" class="btn py-2" data-bs-dismiss="modal" style="background:#f1f5f9;color:#64748b;font-weight:600">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    {{-- SMS Quick Send Modal --}}
    <div id="smsModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:2000;align-items:center;justify-content:center">
        <div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:28px;width:90%;max-width:380px">
            <h3 id="sms_name" style="margin-bottom:12px">Send SMS</h3>
            <p id="sms_phone" style="font-size:13px;color:var(--muted);margin-bottom:16px"></p>
            <form id="smsForm" method="POST">
                @csrf
                <textarea class="form-control" name="message" id="sms_message" rows="4" placeholder="Enter SMS message..." required></textarea>
                <div style="display:flex;gap:10px;margin-top:20px">
                    <button class="btn btn-primary" type="submit">Send Message</button>
                    <button type="button" onclick="document.getElementById('smsModal').style.display='none'" class="btn" style="background:var(--surface2);color:var(--muted)">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openSmsModal(id, phone, name) {
            document.getElementById('smsModal').style.display = 'flex';
            document.getElementById('smsForm').action = '/admin/leads/' + id + '/sms';
            document.getElementById('sms_phone').innerText = 'To: ' + phone;
            document.getElementById('sms_name').innerText = 'Send SMS to ' + name;
            document.getElementById('sms_message').value = '';
        }
        function openEnquirySmsModal(id, phone, name) {
            document.getElementById('smsModal').style.display = 'flex';
            document.getElementById('smsForm').action = '/admin/leads/' + id + '/sms'; // Enquiries need to be leads to use LeadController@sms, or create specific route.
            document.getElementById('sms_phone').innerText = 'To: ' + phone;
            document.getElementById('sms_name').innerText = 'Send SMS to ' + name;
        }
    </script>
@endsection

