@extends('layouts.admin')
@section('title','Leads')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
    <h2 style="font-size:18px;font-weight:600">Lead Management</h2>
    <button onclick="document.getElementById('addLeadModal').style.display='flex'" class="btn btn-primary">+ Add Lead</button>
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
<div style="margin-top:12px">{{ $leads->links() }}</div>

{{-- Add Lead Modal --}}
<div id="addLeadModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:1000;align-items:center;justify-content:center">
    <div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:28px;width:480px;max-height:90vh;overflow-y:auto">
        <h3 style="margin-bottom:20px">Add New Lead</h3>
        <form method="POST" action="{{ route('admin.leads.store') }}">@csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                <div><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">Name</label><input class="form-control" name="name" required></div>
                <div><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">Phone</label><input class="form-control" name="phone" required></div>
                <div><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">Email</label><input class="form-control" name="email" type="email"></div>
                <div><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">City</label><input class="form-control" name="city"></div>
                <div style="grid-column: span 2;"><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">Project Interested</label><input class="form-control" name="shades_needed"></div>
                <div><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">Budget</label><input class="form-control" name="budget"></div>
                <div><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">Campaign Name</label><input class="form-control" name="campaign"></div>
                <div><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">Source</label>
                    <select class="form-control" name="source">
                        <option>Landing Page Form</option>
                        <option>Meta Lead Form (Facebook / Instagram)</option>
                        <option>Manual Entry (Admin / Staff)</option>
                        <option>Other Sources (Google Ads / Referral)</option>
                    </select>
                </div>
                <div><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">Address</label><input class="form-control" name="address"></div>
            </div>
            <div style="display:flex;gap:10px;margin-top:20px">
                <button class="btn btn-primary" type="submit">Save Lead</button>
                <button type="button" onclick="document.getElementById('addLeadModal').style.display='none'" class="btn" style="background:var(--surface2);color:var(--muted)">Cancel</button>
            </div>
        </form>
    </div>
</div>
    {{-- SMS Quick Send Modal --}}
    <div id="smsModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:2000;align-items:center;justify-content:center">
        <div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:28px;width:380px">
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

