@extends('layouts.admin')
@section('title','Live Appointments')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
    <div>
        <h2 style="font-size:22px;font-weight:800;margin:0;color:var(--text);display:flex;align-items:center;gap:10px">
            <span style="color:var(--accent)">📅</span> Appointment Scheduler
        </h2>
        <p style="color:var(--muted);margin:4px 0 0;font-size:13px">Track site visits, measurements, and consultations in real-time.</p>
    </div>
    <button onclick="document.getElementById('addApptModal').style.display='flex'" class="btn btn-primary" style="padding:10px 20px; font-weight:700; border-radius:10px; box-shadow:0 4px 15px rgba(88,166,255,.2)">+ Schedule New Visit</button>
</div>

    <div style="background:transparent; display:grid; gap:16px; padding:16px;">
        @forelse($appointments as $a)
        <div style="background:#fff; border-radius:15px; border:1px solid var(--border); box-shadow:0 10px 30px rgba(0,0,0,0.04); padding:20px; display:flex; align-items:center; justify-content:space-between; transition:transform 0.2s, box-shadow 0.2s; cursor:default;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 15px 35px rgba(37,99,235,0.08)'" onmouseout="this.style.transform='none'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.04)'">
            <div style="display:flex; align-items:center; gap:20px; flex:1.2;">
                <div style="width:48px; height:48px; background:rgba(37,99,235,0.05); border-radius:12px; display:flex; align-items:center; justify-content:center; font-weight:800; color:var(--accent); font-size:18px; border:1.5px solid rgba(37,99,235,0.1)">
                    {{ strtoupper(substr($a->lead?->name, 0, 1)) }}
                </div>
                <div>
                    <a href="{{ route('admin.leads.show',$a->lead_id) }}" style="color:#000; font-weight:800; text-decoration:none; font-size:16px; letter-spacing:-0.4px;">{{ $a->lead?->name }}</a>
                    <div style="font-size:12px; color:var(--muted); font-weight:600; margin-top:2px;">Lead Ref: <span style="color:var(--accent)">#L{{ $a->lead_id }}</span></div>
                    
                    <div style="display:flex; align-items:center; gap:8px; margin-top:10px;">
                        <span style="font-size:13px; font-weight:700; color:var(--muted);">{{ $a->lead?->phone }}</span>
                        <a href="tel:{{ $a->lead?->phone }}" style="text-decoration:none; color:var(--success); font-weight:800; font-size:11px; padding:4px 8px; background:rgba(16,185,129,0.05); border-radius:6px;">☎ CALL</a>
                    </div>
                </div>
            </div>

            <div style="flex:0.8; padding:0 20px;">
                <div style="font-size:10px; font-weight:800; color:var(--muted); text-transform:uppercase; letter-spacing:1px; margin-bottom:6px;">ACTION TYPE</div>
                <span style="background:rgba(88,166,255,0.1); padding:6px 14px; border-radius:8px; font-size:12px; font-weight:800; color:var(--accent); border:1.5px solid rgba(88,166,255,0.1);">
                    {{ strtoupper($a->type) }}
                </span>
            </div>

            <div style="flex:1;">
                <div style="font-size:10px; font-weight:800; color:var(--muted); text-transform:uppercase; letter-spacing:1px; margin-bottom:6px;">SCHEDULED FOR</div>
                <div style="font-weight:800; color:#000; font-size:14px;">{{ $a->date?->format('M d, Y') }}</div>
                <div style="font-size:12px; color:var(--accent); font-weight:700;">@ {{ date('h:i A', strtotime($a->time)) }}</div>
            </div>

            <div style="flex:1;">
                <div style="font-size:10px; font-weight:800; color:var(--muted); text-transform:uppercase; letter-spacing:1px; margin-bottom:6px;">CURRENT STATUS</div>
                @if($a->status === 'scheduled')
                    <span style="background:rgba(215,153,34,0.1); color:#d29922; padding:6px 14px; border-radius:8px; font-size:11px; font-weight:800; border:1.5px solid rgba(215,153,34,0.1); display:inline-flex; align-items:center; gap:6px;">⏳ SCHEDULED</span>
                @elseif($a->status === 'completed')
                    <span style="background:rgba(63,185,80,0.1); color:#3fb950; padding:6px 14px; border-radius:8px; font-size:11px; font-weight:800; border:1.5px solid rgba(63,185,80,0.1); display:inline-flex; align-items:center; gap:6px;">✓ COMPLETED</span>
                @else
                    <span style="background:rgba(215,58,73,0.1); color:#f85149; padding:6px 14px; border-radius:8px; font-size:11px; font-weight:800; border:1.5px solid rgba(215,58,73,0.1); display:inline-flex; align-items:center; gap:6px;">✕ CANCELLED</span>
                @endif
            </div>

            <div style="flex:0.8; display:flex; justify-content:flex-end; gap:10px;">
                @if($a->status==='scheduled')
                @php
                    $startDt = $a->date?->format('Ymd') . 'T' . date('His', strtotime($a->time));
                    $endDt   = $a->date?->format('Ymd') . 'T' . date('His', strtotime($a->time . ' +1 hour'));
                    $title   = urlencode(ucfirst($a->type) . " — " . ($a->lead?->name ?? 'Client Visit'));
                    $loc     = urlencode($a->lead?->address ?? "Client Site");
                    $details = urlencode("ModuShade Project Ref: #L" . $a->lead_id . "\nNotes: " . ($a->notes ?? 'No specific instructions.'));
                    $gCalUrl = "https://www.google.com/calendar/render?action=TEMPLATE&text={$title}&dates={$startDt}/{$endDt}&details={$details}&location={$loc}&sf=true&output=xml";
                @endphp
                <a href="{{ $gCalUrl }}" target="_blank" class="btn-tool" style="background:rgba(88,166,255,0.1); color:var(--accent); width:40px; height:40px; border-radius:12px; display:flex; align-items:center; justify-content:center; text-decoration:none; border:1.5px solid rgba(88,166,255,0.1);" title="Sync to Calendar">📅</a>
                
                <button onclick="openEditApptModal({{ $a->id }}, '{{ $a->type }}', '{{ $a->date?->format('Y-m-d') }}', '{{ $a->time }}', '{{ addslashes($a->notes) }}')" class="btn-tool" style="background:rgba(238,238,238,0.5); color:#666; width:40px; height:40px; border-radius:12px; border:1.5px solid #eee; display:flex; align-items:center; justify-content:center;" title="Edit Appointment">✎</button>
                
                <form method="POST" action="{{ route('admin.appointments.complete',$a->id) }}" style="display:inline">@csrf
                    <button class="btn-tool" style="background:rgba(63,185,80,0.15); color:#3fb950; width:40px; height:40px; border-radius:12px; border:1.5px solid rgba(63,185,80,0.15); display:flex; align-items:center; justify-content:center; font-weight:800;" type="submit" title="Finalize Visit">✓</button>
                </form>
                @endif
                <form method="POST" action="{{ route('admin.appointments.destroy',$a->id) }}" style="display:inline" onsubmit="return confirm('Archive record?')">
                    @csrf @method('DELETE')
                    <button class="btn-tool" style="background:rgba(215,58,73,0.1); color:#f85149; width:40px; height:40px; border-radius:12px; border:1.5px solid rgba(215,58,73,0.1); display:flex; align-items:center; justify-content:center;" type="submit" title="Delete">✕</button>
                </form>
            </div>
        </div>
        @empty
        <div style="text-align:center; padding:100px 20px;">
            <div style="font-size:60px; margin-bottom:20px">🔍</div>
            <h3 style="color:#000; font-weight:800; margin:0; font-size:24px;">No Site Visits Found</h3>
            <p style="color:var(--muted); font-size:14px; margin-top:10px">Your field team is all caught up!</p>
        </div>
        @endforelse
    </div>

{{-- Modern Add Modal --}}
<div id="addApptModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.85);backdrop-filter:blur(8px);z-index:2000;align-items:center;justify-content:center">
    <div style="background:var(--surface);border:1px solid var(--border);border-radius:20px;padding:32px;width:450px;box-shadow:0 20px 50px rgba(0,0,0,0.5)">
        <h3 style="margin:0 0 8px; font-weight:800">Schedule Visit</h3>
        <p style="color:var(--muted); font-size:13px; margin-bottom:24px">Associate this visit with a lead to track history.</p>
        
        <form method="POST" action="{{ route('admin.appointments.store') }}">@csrf
            <div style="display:grid;gap:16px">
                <div>
                    <label class="form-label">Client Lead</label>
                    <select class="form-control" name="lead_id" required style="background:var(--surface2)">
                        @foreach(\App\Models\Lead::latest()->limit(50)->get() as $l)
                        <option value="{{ $l->id }}">{{ $l->name }} ({{ $l->phone }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Purpose of Visit</label>
                    <select class="form-control" name="type" style="background:var(--surface2)">
                        <option>Measurement</option><option>Installation</option><option>Consultation</option><option>Repair</option>
                    </select>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div><label class="form-label">Date</label><input type="date" class="form-control" name="date" required style="background:var(--surface2)"></div>
                    <div><label class="form-label">Time</label><input type="time" class="form-control" name="time" required style="background:var(--surface2)"></div>
                </div>
                <div><label class="form-label">Observation Notes</label><textarea class="form-control" name="notes" rows="2" style="background:var(--surface2)" placeholder="Any specific requirements..."></textarea></div>
            </div>
            <div style="display:flex;gap:12px;margin-top:30px">
                <button class="btn btn-primary" type="submit" style="flex:1; padding:12px; font-weight:700">Confirm Schedule</button>
                <button type="button" onclick="document.getElementById('addApptModal').style.display='none'" class="btn" style="flex:1; background:var(--surface2); color:var(--muted); font-weight:700">Cancel</button>
            </div>
        </form>
    </div>
</div>

{{-- Modern Edit Modal --}}
<div id="editApptModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.85);backdrop-filter:blur(8px);z-index:2000;align-items:center;justify-content:center">
    <div style="background:var(--surface);border:1px solid var(--border);border-radius:20px;padding:32px;width:450px;box-shadow:0 20px 50px rgba(0,0,0,0.5)">
        <h3 style="margin:0 0 8px; font-weight:800">Adjust Schedule</h3>
        <p style="color:var(--muted); font-size:13px; margin-bottom:24px">Modify the visit details or update status.</p>

        <form id="editApptForm" method="POST">@csrf @method('PUT')
            <div style="display:grid;gap:16px">
                <div>
                    <label class="form-label">Purpose</label>
                    <select class="form-control" name="type" id="ea_type" style="background:var(--surface2)">
                        <option>Measurement</option><option>Installation</option><option>Consultation</option><option>Repair</option>
                    </select>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div><label class="form-label">Reschedule Date</label><input type="date" class="form-control" name="date" id="ea_date" required style="background:var(--surface2)"></div>
                    <div><label class="form-label">Reschedule Time</label><input type="time" class="form-control" name="time" id="ea_time" required style="background:var(--surface2)"></div>
                </div>
                <div><label class="form-label">Activity Notes</label><textarea class="form-control" name="notes" id="ea_notes" rows="3" style="background:var(--surface2)"></textarea></div>
                <div>
                    <label class="form-label">Progress Status</label>
                    <select class="form-control" name="status" id="ea_status" style="background:var(--surface2)">
                        <option value="scheduled">Scheduled</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
            <div style="display:flex;gap:12px;margin-top:30px">
                <button class="btn btn-primary" type="submit" style="flex:1; padding:12px; font-weight:700">Update Record</button>
                <button type="button" onclick="document.getElementById('editApptModal').style.display='none'" class="btn" style="flex:1; background:var(--surface2); color:var(--muted); font-weight:700">Dismiss</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditApptModal(id, type, date, time, notes) {
        document.getElementById('editApptModal').style.display = 'flex';
        document.getElementById('editApptForm').action = '/admin/appointments/' + id;
        document.getElementById('ea_type').value = type;
        document.getElementById('ea_date').value = date;
        document.getElementById('ea_time').value = time;
        document.getElementById('ea_notes').value = notes;
    }
</script>
@endsection
