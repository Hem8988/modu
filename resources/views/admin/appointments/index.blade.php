@extends('layouts.admin')
@section('title','Live Appointments')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h2 class="fs-4 fw-bolder text-dark mb-1 d-flex align-items-center gap-2">
            <span class="text-primary">📅</span> Appointment Scheduler
        </h2>
        <p class="text-secondary mb-0 small fw-semibold">Track site visits, measurements, and consultations in real-time.</p>
    </div>
    <button type="button" data-bs-toggle="modal" data-bs-target="#addApptModal" class="btn btn-primary fw-bold px-4 py-2 rounded-3 shadow-sm text-nowrap">+ Schedule New Visit</button>
</div>

<div class="row g-3">
    @forelse($appointments as $a)
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden appt-card transition-all">
            <div class="card-body p-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
                
                <div class="d-flex align-items-center gap-3" style="flex:1.2; min-width: 250px;">
                    <div class="bg-primary bg-opacity-10 text-primary border border-primary-subtle fw-bolder rounded-3 d-flex align-items-center justify-content-center fs-5" style="width: 48px; height: 48px;">
                        {{ strtoupper(substr($a->lead?->name, 0, 1)) }}
                    </div>
                    <div>
                        <a href="{{ route('admin.leads.show',$a->lead_id) }}" class="text-dark fw-bolder text-decoration-none fs-6">{{ $a->lead?->name }}</a>
                        <div class="small fw-semibold text-secondary mt-1">Lead Ref: <span class="text-primary">#L{{ $a->lead_id }}</span></div>
                        
                        <div class="d-flex align-items-center gap-2 mt-2">
                            <span class="small fw-bold text-secondary">{{ $a->lead?->phone }}</span>
                            <a href="tel:{{ $a->lead?->phone }}" class="text-decoration-none bg-success bg-opacity-10 text-success fw-bolder px-2 py-1 rounded" style="font-size: 0.65rem;">☎ CALL</a>
                        </div>
                    </div>
                </div>

                <div style="flex:0.8; min-width: 150px;">
                    <div class="text-secondary fw-bold text-uppercase mb-2" style="font-size: 0.65rem; letter-spacing: 1px;">ACTION TYPE</div>
                    <span class="badge border border-primary-subtle text-primary bg-primary bg-opacity-10 px-3 py-2 text-uppercase fw-bold">
                        {{ strtoupper($a->type) }}
                    </span>
                </div>

                <div style="flex:1; min-width: 150px;">
                    <div class="text-secondary fw-bold text-uppercase mb-2" style="font-size: 0.65rem; letter-spacing: 1px;">SCHEDULED FOR</div>
                    <div class="fw-bolder text-dark mb-1">{{ $a->date?->format('M d, Y') }}</div>
                    <div class="text-primary fw-bold small">@ {{ date('h:i A', strtotime($a->time)) }}</div>
                </div>

                <div style="flex:1; min-width: 150px;">
                    <div class="text-secondary fw-bold text-uppercase mb-2" style="font-size: 0.65rem; letter-spacing: 1px;">CURRENT STATUS</div>
                    @if($a->status === 'scheduled')
                        <span class="badge border border-warning-subtle text-warning bg-warning bg-opacity-10 px-3 py-2 fw-bold"><i class="fas fa-hourglass-half me-1"></i> SCHEDULED</span>
                    @elseif($a->status === 'completed')
                        <span class="badge border border-success-subtle text-success bg-success bg-opacity-10 px-3 py-2 fw-bold"><i class="fas fa-check me-1"></i> COMPLETED</span>
                    @else
                        <span class="badge border border-danger-subtle text-danger bg-danger bg-opacity-10 px-3 py-2 fw-bold"><i class="fas fa-times me-1"></i> CANCELLED</span>
                    @endif
                </div>

                <div class="d-flex justify-content-end gap-2" style="flex:0.8;">
                    @if($a->status==='scheduled')
                    @php
                        $startDt = $a->date?->format('Ymd') . 'T' . date('His', strtotime($a->time));
                        $endDt   = $a->date?->format('Ymd') . 'T' . date('His', strtotime($a->time . ' +1 hour'));
                        $title   = urlencode(ucfirst($a->type) . " — " . ($a->lead?->name ?? 'Client Visit'));
                        $loc     = urlencode($a->lead?->address ?? "Client Site");
                        $details = urlencode("ModuShade Project Ref: #L" . $a->lead_id . "\nNotes: " . ($a->notes ?? 'No specific instructions.'));
                        $gCalUrl = "https://www.google.com/calendar/render?action=TEMPLATE&text={$title}&dates={$startDt}/{$endDt}&details={$details}&location={$loc}&sf=true&output=xml";
                    @endphp
                    <a href="{{ $gCalUrl }}" target="_blank" class="btn btn-sm btn-light border text-primary d-flex align-items-center justify-content-center bg-primary bg-opacity-10" style="width:36px; height:36px; border-radius: 10px;" title="Sync to Calendar"><i class="far fa-calendar-alt"></i></a>
                    
                    <button onclick="openEditApptModal({{ $a->id }}, '{{ $a->type }}', '{{ $a->date?->format('Y-m-d') }}', '{{ $a->time }}', '{{ addslashes($a->notes) }}')" class="btn btn-sm btn-light border text-secondary d-flex align-items-center justify-content-center" style="width:36px; height:36px; border-radius: 10px;" title="Edit Appointment"><i class="fas fa-pencil-alt"></i></button>
                    
                    <form method="POST" action="{{ route('admin.appointments.complete',$a->id) }}" class="d-inline">@csrf
                        <button class="btn btn-sm btn-light border align-items-center justify-content-center fw-bolder text-success bg-success bg-opacity-10" type="submit" title="Finalize Visit" style="width:36px; height:36px; border-radius: 10px;"><i class="fas fa-check"></i></button>
                    </form>
                    @endif
                    <form method="POST" action="{{ route('admin.appointments.destroy',$a->id) }}" class="d-inline" onsubmit="return confirm('Archive record?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-light border d-flex align-items-center justify-content-center text-danger bg-danger bg-opacity-10" type="submit" title="Delete" style="width:36px; height:36px; border-radius: 10px;"><i class="fas fa-times"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 py-5 text-center">
        <div class="fs-1 mb-3">🔍</div>
        <h3 class="fw-bolder text-dark mb-2">No Site Visits Found</h3>
        <p class="text-secondary mb-0">Your field team is all caught up!</p>
    </div>
    @endforelse
</div>

<style>
    .appt-card:hover { transform: translateY(-2px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
    .transition-all { transition: all 0.2s ease-in-out; }
</style>

{{-- Modern Add Modal --}}
<div class="modal fade" id="addApptModal" tabindex="-1" aria-hidden="true" style="backdrop-filter: blur(5px);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h4 class="modal-title fw-bolder">Schedule Visit</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-secondary small fw-semibold mb-4">Associate this visit with a lead to track history.</p>
                
                <form method="POST" action="{{ route('admin.appointments.store') }}">@csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary">Client Lead</label>
                            <select class="form-select bg-light" name="lead_id" required>
                                @foreach(\App\Models\Lead::latest()->limit(50)->get() as $l)
                                <option value="{{ $l->id }}">{{ $l->name }} ({{ $l->phone }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary">Purpose of Visit</label>
                            <select class="form-select bg-light" name="type">
                                <option>Measurement</option><option>Installation</option><option>Consultation</option><option>Repair</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Date</label>
                            <input type="date" class="form-control bg-light" name="date" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Time</label>
                            <input type="time" class="form-control bg-light" name="time" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary">Observation Notes</label>
                            <textarea class="form-control bg-light" name="notes" rows="2" placeholder="Any specific requirements..."></textarea>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4 pt-2">
                        <button class="btn btn-primary fw-bold w-100 py-2" type="submit">Confirm Schedule</button>
                        <button type="button" class="btn btn-light fw-bold w-100 py-2" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modern Edit Modal --}}
<div class="modal fade" id="editApptModal" tabindex="-1" aria-hidden="true" style="backdrop-filter: blur(5px);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h4 class="modal-title fw-bolder">Adjust Schedule</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-secondary small fw-semibold mb-4">Modify the visit details or update status.</p>

                <form id="editApptForm" method="POST">@csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary">Purpose</label>
                            <select class="form-select bg-light" name="type" id="ea_type">
                                <option>Measurement</option><option>Installation</option><option>Consultation</option><option>Repair</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Reschedule Date</label>
                            <input type="date" class="form-control bg-light" name="date" id="ea_date" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Reschedule Time</label>
                            <input type="time" class="form-control bg-light" name="time" id="ea_time" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary">Activity Notes</label>
                            <textarea class="form-control bg-light" name="notes" id="ea_notes" rows="3"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary">Progress Status</label>
                            <select class="form-select bg-light" name="status" id="ea_status">
                                <option value="scheduled">Scheduled</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4 pt-2">
                        <button class="btn btn-primary fw-bold w-100 py-2" type="submit">Update Record</button>
                        <button type="button" class="btn btn-light fw-bold w-100 py-2" data-bs-dismiss="modal">Dismiss</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openEditApptModal(id, type, date, time, notes) {
        document.getElementById('editApptForm').action = '/admin/appointments/' + id;
        document.getElementById('ea_type').value = type;
        document.getElementById('ea_date').value = date;
        document.getElementById('ea_time').value = time;
        document.getElementById('ea_notes').value = notes;
        
        const modal = new bootstrap.Modal(document.getElementById('editApptModal'));
        modal.show();
    }
</script>
@endpush
@endsection
