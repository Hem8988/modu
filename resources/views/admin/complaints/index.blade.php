@extends('layouts.admin')
@section('title', 'Feedback & Resolutions Hub')
@section('content')

{{-- 1. Executive Intelligence Hub (6 Metrics) --}}
<div style="display:grid; grid-template-columns: repeat(6, 1fr); gap:15px; margin-bottom:25px">
    <div class="card" style="margin-bottom:0; text-align:center; padding:15px">
        <div style="font-size:20px; font-weight:800; color:var(--text)">{{ $stats['total_feedback'] }}</div>
        <div style="font-size:9px; color:var(--muted); text-transform:uppercase; font-weight:800">Total Feedbacks</div>
    </div>
    <div class="card" style="margin-bottom:0; text-align:center; padding:15px">
        <div style="font-size:20px; font-weight:800; color:var(--danger)">{{ $stats['open_complaints'] }}</div>
        <div style="font-size:9px; color:var(--muted); text-transform:uppercase; font-weight:800">Open Complaints</div>
    </div>
    <div class="card" style="margin-bottom:0; text-align:center; padding:15px">
        <div style="font-size:20px; font-weight:800; color:var(--warning)">{{ $stats['active_resolving'] }}</div>
        <div style="font-size:9px; color:var(--muted); text-transform:uppercase; font-weight:800">Active Resolutions</div>
    </div>
    <div class="card" style="margin-bottom:0; text-align:center; padding:15px">
        <div style="font-size:20px; font-weight:800; color:var(--success)">{{ $stats['resolved_total'] }}</div>
        <div style="font-size:9px; color:var(--muted); text-transform:uppercase; font-weight:800">Closed Issues</div>
    </div>
    <div class="card" style="margin-bottom:0; text-align:center; padding:15px">
        <div style="font-size:20px; font-weight:800; color:var(--accent)">{{ $stats['csat_score'] }}</div>
        <div style="font-size:9px; color:var(--muted); text-transform:uppercase; font-weight:800">CSAT Score</div>
    </div>
    <div class="card" style="margin-bottom:0; text-align:center; padding:15px">
        <div style="font-size:20px; font-weight:800; color:var(--gold)">{{ $stats['avg_resp_time'] }}</div>
        <div style="font-size:9px; color:var(--muted); text-transform:uppercase; font-weight:800">Avg Resp Time</div>
    </div>
</div>

{{-- 2. Multi-Tab Command Switcher --}}
<div class="card" style="padding:0; overflow:hidden">
    <div style="display:flex; background:rgba(0,0,0,0.02); border-bottom:1px solid var(--border)">
        <button onclick="switchTab('complaints')" class="tab-btn active" id="tab-complaints">Complaints Management</button>
        <button onclick="switchTab('feedback')" class="tab-btn" id="tab-feedback">Customer Feedback</button>
        <button onclick="switchTab('service')" class="tab-btn" id="tab-service">Service Requests</button>
        <button onclick="switchTab('resolutions')" class="tab-btn" id="tab-resolutions">Resolution Tracker</button>
        <button onclick="openModal('complaint')" style="margin-left:auto; background:var(--gold); color:var(--surface); border:none; padding:10px 20px; font-weight:800; cursor:pointer">+ Log Complaint</button>
    </div>

    {{-- A. Complaints Registry --}}
    <div id="content-complaints" class="tab-content active">
        <table style="width:100%">
            <thead><tr><th>Client / Project</th><th>Priority</th><th>Staff</th><th>Status pulse</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($complaints as $c)
                <tr>
                    <td>
                        <div style="font-weight:800">{{ $c->customer->name ?? 'N/A' }}</div>
                        <div style="font-size:10px; color:var(--muted)">Project: #{{ $c->project_number ?? 'MODU-00' }}</div>
                    </td>
                    <td>
                        <span class="badge" style="background:{{ $c->priority == 'urgent' ? 'var(--danger)' : 'rgba(0,0,0,0.1)' }}">
                            {{ strtoupper($c->priority) }}
                        </span>
                    </td>
                    <td>🛠 {{ $c->assignedStaff->name ?? 'Unassigned' }}</td>
                    <td>
                        <form action="{{ route('admin.complaints.update', $c->id) }}" method="POST">
                            @csrf @method('PUT')
                            <select name="status" onchange="this.form.submit()" style="font-size:11px; border:none; font-weight:800">
                                <option value="open" {{ $c->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="progress" {{ $c->status == 'progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ $c->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            </select>
                        </form>
                    </td>
                    <td>
                        <button class="btn btn-sm" onclick="showResolutionModal({{ $c->id }})">Resolve</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- B. Feedback registry --}}
    <div id="content-feedback" class="tab-content">
        <table style="width:100%">
            <thead><tr><th>Customer pulse</th><th>Rating</th><th>Type</th><th>Comments pulse</th></tr></thead>
            <tbody>
                @foreach($feedbacks as $f)
                <tr>
                    <td>
                        <div style="font-weight:700">{{ $f->name }}</div>
                        <div style="font-size:10px; color:var(--muted)">{{ $f->phone }}</div>
                    </td>
                    <td style="color:var(--gold)">
                        @for($i=0; $i<$f->rating; $i++)★@endfor
                    </td>
                    <td>{{ $f->type }}</td>
                    <td style="font-size:12px; font-style:italic">"{{ $f->comments }}"</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- C. Service Requests --}}
    <div id="content-service" class="tab-content">
        <table style="width:100%">
            <thead><tr><th>Lead Client</th><th>Issue Registry</th><th>Technician</th><th>Schedule</th></tr></thead>
            <tbody>
                @foreach($serviceRequests as $s)
                <tr>
                    <td>{{ $s->customer->name ?? 'N/A' }}</td>
                    <td>{{ $s->issue_description }}</td>
                    <td>⚡ {{ $s->assigned_technician ?? 'TBD' }}</td>
                    <td>{{ $s->requested_date }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- D. Resolution tracker --}}
    <div id="content-resolutions" class="tab-content">
        <table style="width:100%">
            <thead><tr><th>Resolution ID</th><th>Action pulse</th><th>Registry Logic</th><th>Finalized</th></tr></thead>
            <tbody>
                @foreach($resolutions as $r)
                <tr>
                    <td>#RESOLVE-{{ $r->id }}</td>
                    <td style="font-weight:800">{{ $r->action_taken }}</td>
                    <td style="font-size:11px; color:var(--muted)">{{ $r->notes }}</td>
                    <td>{{ $r->resolution_date }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const activeTab = urlParams.get('tab') || 'complaints';
    switchTab(activeTab);
});

function switchTab(tab) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    
    const tabBtn = document.getElementById('tab-'+tab);
    const tabContent = document.getElementById('content-'+tab);
    
    if (tabBtn && tabContent) {
        tabBtn.classList.add('active');
        tabContent.classList.add('active');
    }
}

function openModal(type) {
    document.getElementById('modal-'+type).style.display = 'flex';
}

function closeModal(type) {
    document.getElementById('modal-'+type).style.display = 'none';
}

function showResolutionModal(complaintId) {
    document.getElementById('res-complaint-id').value = complaintId;
    openModal('resolution');
}
</script>

{{-- MODALS --}}
<div id="modal-complaint" class="modal-overlay" style="display:none">
    <div class="modal-card">
        <div class="card-title">Log Project Complaint</div>
        <form action="{{ route('admin.complaints.store') }}" method="POST">
            @csrf
            <div style="margin-bottom:15px">
                <label style="display:block; font-size:11px; font-weight:800; color:var(--muted); margin-bottom:5px">CUSTOMER REGISTRY</label>
                <select name="customer_id" class="form-control" required>
                    @foreach($customers as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                </select>
            </div>
            <div style="margin-bottom:15px">
                <label style="display:block; font-size:11px; font-weight:800; color:var(--muted); margin-bottom:5px">COMPLAINT TITLE</label>
                <input type="text" name="title" class="form-control" placeholder="Short description..." required>
            </div>
            <div style="margin-bottom:15px">
                <label style="display:block; font-size:11px; font-weight:800; color:var(--muted); margin-bottom:5px">URGENCY PRIORITY</label>
                <select name="priority" class="form-control">
                    <option value="low">Low</option>
                    <option value="medium" selected>Medium</option>
                    <option value="high">High</option>
                    <option value="urgent">Urgent</option>
                </select>
            </div>
            <div style="margin-bottom:15px">
                <label style="display:block; font-size:11px; font-weight:800; color:var(--muted); margin-bottom:5px">DESCRIPTION REGISTRY</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <div style="display:flex; gap:10px; justify-content:flex-end">
                <button type="button" onclick="closeModal('complaint')" class="btn">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background:var(--gold); border:none; color:var(--surface)">Dispatch Complaint</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-resolution" class="modal-overlay" style="display:none">
    <div class="modal-card">
        <div class="card-title">Dispatch Resolution Forensic</div>
        <form action="{{ route('admin.complaints.resolution.store') }}" method="POST">
            @csrf
            <input type="hidden" name="complaint_id" id="res-complaint-id">
            <div style="margin-bottom:15px">
                <label style="display:block; font-size:11px; font-weight:800; color:var(--muted); margin-bottom:5px">ACTION TAKEN HUB</label>
                <input type="text" name="action_taken" class="form-control" placeholder="What was done?" required>
            </div>
            <div style="margin-bottom:15px">
                <label style="display:block; font-size:11px; font-weight:800; color:var(--muted); margin-bottom:5px">RESOLUTION DATE</label>
                <input type="date" name="resolution_date" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>
            <div style="margin-bottom:15px">
                <label style="display:block; font-size:11px; font-weight:800; color:var(--muted); margin-bottom:5px">INTERNAL NOTES</label>
                <textarea name="notes" class="form-control" rows="3"></textarea>
            </div>
            <div style="display:flex; gap:10px; justify-content:flex-end">
                <button type="button" onclick="closeModal('resolution')" class="btn">Archive</button>
                <button type="submit" class="btn btn-primary" style="background:var(--success); border:none">Resolve Issue</button>
            </div>
        </form>
    </div>
</div>

<style>
.tab-btn { background:none; border:none; padding:15px 25px; font-weight:800; font-size:12px; color:var(--muted); cursor:pointer; }
.tab-btn.active { color:var(--text); border-bottom:2px solid var(--gold); }
.tab-content { display:none; padding:20px; }
.tab-content.active { display:block; }
.badge { padding:3px 8px; border-radius:4px; font-size:9px; color:#fff; font-weight:900; }

.modal-overlay { position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); display:flex; align-items:center; justify-content:center; z-index:1000; backdrop-filter: blur(5px); }
.modal-card { background:var(--surface); padding:25px; border-radius:15px; width:450px; border:1px solid var(--border); box-shadow: 0 20px 40px rgba(0,0,0,0.5); }
.form-control { width:100%; padding:10px; background:rgba(0,0,0,0.02); border:1px solid var(--border); border-radius:5px; color:var(--text); font-size:13px; }
</style>

@endsection
