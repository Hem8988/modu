@extends('layouts.admin')
@section('title','Enquiries')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
    <h2 style="font-size:18px;font-weight:600">Enquiries</h2>
    <button onclick="document.getElementById('addEnquiryModal').style.display='flex'" class="btn btn-primary">+ Add Enquiry</button>
</div>
<div class="card" style="padding:0;overflow:hidden">
    <table>
        <thead><tr><th>Name</th><th>Phone</th><th>City</th><th>Project</th><th>Source</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
        <tbody>
        @forelse($enquiries as $e)
        <tr>
            <td>{{ $e->name }}</td>
            <td>{{ $e->phone }}</td>
            <td style="color:var(--muted)">{{ $e->city }}</td>
            <td style="color:var(--muted)">{{ Str::limit($e->project,30) }}</td>
            <td style="color:var(--muted)">{{ $e->source }}</td>
            <td><span class="badge badge-{{ $e->status==='pending'?'new':($e->status==='converted'?'won':'lost') }}">{{ $e->status }}</span></td>
            <td style="color:var(--muted)">{{ $e->created_at?->format('M d, Y') }}</td>
            <td style="white-space:nowrap; display:flex; gap:6px;">
                {{-- Communication Tools --}}
                <a href="tel:{{ $e->phone }}" class="btn btn-sm" title="Call" style="background:rgba(63,185,80,.1);color:#3fb950;border:1px solid rgba(63,185,80,.2)">☎</a>
                @if($e->email)
                <a href="mailto:{{ $e->email }}" class="btn btn-sm" title="Email" style="background:rgba(212,160,23,.1);color:var(--gold);border:1px solid rgba(212,160,23,.2)">@</a>
                @endif
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$e->phone) }}" target="_blank" class="btn btn-sm" title="WhatsApp" style="background:rgba(88,166,255,.1);color:var(--accent);border:1px solid rgba(88,166,255,.2)">💬</a>

                {{-- Workflow Actions --}}
                @if($e->status==='pending')
                <a href="{{ route('admin.enquiries.convert',$e->id) }}" class="btn btn-sm" title="Promote to Lead" style="background:rgba(63,185,80,.15); color:#3fb950; font-weight:700; padding:6px 14px; border:1px solid rgba(63,185,80,0.4); display:inline-flex; align-items:center; gap:8px;">Convert to Lead ➔</a>
                <a href="{{ route('admin.enquiries.spam',$e->id) }}" class="btn btn-sm" title="Mark Spam" style="background:rgba(248,81,73,.1);color:var(--danger);border:1px solid rgba(248,81,73,.1)">⃠</a>
                @elseif($e->status==='converted' && $e->lead)
                <a href="{{ route('admin.leads.show', $e->lead->id) }}" class="btn btn-sm" style="background:rgba(88,166,255,.1); color:var(--accent); font-weight:700; padding:6px 12px; border:1px solid rgba(88,166,255,0.3)">View Lead ➜</a>
                @endif
                
                {{-- Permanent CRUD Actions (Always Visible) --}}
                <button type="button" class="btn btn-sm" title="Edit" style="background:var(--surface2); border:1px solid var(--border)" onclick="openEnquiryEditModal({{ $e->id }}, '{{ addslashes($e->name) }}', '{{ $e->phone }}', '{{ @addslashes($e->email) }}', '{{ addslashes($e->city) }}', '{{ addslashes($e->project) }}', '{{ $e->budget }}', '{{ $e->source }}', '{{ $e->campaign }}', '{{ $e->status }}')">✎</button>

                <form method="POST" action="{{ route('admin.enquiries.destroy',$e->id) }}" style="display:inline" onsubmit="return confirm('Delete permanently?')">
                    @csrf @method('DELETE')<button class="btn btn-sm" type="submit" style="background:rgba(248,81,73,.05);color:var(--danger);opacity:0.6; border:1px solid rgba(248,81,73,.1)">✕</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="8" style="text-align:center;color:var(--muted);padding:30px">No enquiries found</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div id="addEnquiryModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:1000;align-items:center;justify-content:center">
    <div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:28px;width:480px">
        <h3 style="margin-bottom:20px">Add Enquiry</h3>
        <form method="POST" action="{{ route('admin.enquiries.store') }}">@csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                <div><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">Name</label><input class="form-control" name="name"></div>
                <div><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">Phone</label><input class="form-control" name="phone"></div>
                <div><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">Email</label><input class="form-control" name="email" type="email"></div>
                <div><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">City</label><input class="form-control" name="city"></div>
                <div><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">Project</label><input class="form-control" name="project"></div>
                <div><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">Budget</label><input class="form-control" name="budget"></div>
                <div style="grid-column: span 2;"><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">Source</label>
                    <select class="form-control" name="source">
                        <option>Landing Page Form</option>
                        <option>Meta Lead Form (Facebook / Instagram)</option>
                        <option>Manual Entry (Admin / Staff)</option>
                        <option>Other Sources (Google Ads / Referral)</option>
                    </select>
                </div>
                <div style="grid-column: span 2;"><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">Campaign Name</label><input class="form-control" name="campaign"></div>
            </div>
            <div style="display:flex;gap:10px;margin-top:20px">
                <button class="btn btn-primary">Save</button>
                <button type="button" onclick="document.getElementById('addEnquiryModal').style.display='none'" class="btn" style="background:var(--surface2);color:var(--muted)">Cancel</button>
            </div>
        </form>
    </div>
</div>
    {{-- Edit Enquiry Modal --}}
    <div id="editEnquiryModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:1000;align-items:center;justify-content:center">
        <div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:28px;width:480px">
            <h3 style="margin-bottom:20px">Edit Enquiry</h3>
            <form id="editEnquiryForm" method="POST">
                @csrf @method('PUT')
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">Name</label><input class="form-control" name="name" id="ee_name"></div>
                    <div><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">Phone</label><input class="form-control" name="phone" id="ee_phone"></div>
                    <div><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">Email</label><input class="form-control" name="email" id="ee_email" type="email"></div>
                    <div><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">City</label><input class="form-control" name="city" id="ee_city"></div>
                    <div style="grid-column: span 2"><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">Project</label><input class="form-control" name="project" id="ee_project"></div>
                    <div><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">Budget</label><input class="form-control" name="budget" id="ee_budget"></div>
                    <div><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">Campaign Name</label><input class="form-control" name="campaign" id="ee_campaign"></div>
                    <div style="grid-column: span 2;"><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">Source</label>
                        <select class="form-control" name="source" id="ee_source">
                            <option>Landing Page Form</option>
                            <option>Meta Lead Form (Facebook / Instagram)</option>
                            <option>Manual Entry (Admin / Staff)</option>
                            <option>Other Sources (Google Ads / Referral)</option>
                        </select>
                    </div>
                    <div style="grid-column: span 2;"><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">Status</label>
                        <select class="form-control" name="status" id="ee_status">
                            <option value="pending">Pending</option>
                            <option value="converted">Converted</option>
                            <option value="spam">Spam</option>
                        </select>
                    </div>
                </div>
                <div style="display:flex;gap:10px;margin-top:20px">
                    <button class="btn btn-primary">Update</button>
                    <button type="button" onclick="document.getElementById('editEnquiryModal').style.display='none'" class="btn" style="background:var(--surface2);color:var(--muted)">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEnquiryEditModal(id, name, phone, email, city, project, budget, source, campaign, status) {
            document.getElementById('editEnquiryModal').style.display = 'flex';
            document.getElementById('editEnquiryForm').action = '/admin/enquiries/' + id;
            document.getElementById('ee_name').value = name;
            document.getElementById('ee_phone').value = phone;
            document.getElementById('ee_email').value = email;
            document.getElementById('ee_city').value = city;
            document.getElementById('ee_project').value = project;
            document.getElementById('ee_budget').value = budget;
            document.getElementById('ee_source').value = source;
            document.getElementById('ee_campaign').value = campaign;
            document.getElementById('ee_status').value = status;
        }
    </script>
@endsection

