@extends('layouts.admin')
@section('title','Enquiries')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <h2 class="fs-4 fw-bolder text-dark mb-0">Enquiries</h2>
    <button onclick="document.getElementById('addEnquiryModal').style.display='flex'" class="btn btn-primary fw-bold text-nowrap" data-bs-toggle="modal" data-bs-target="#addEnquiryModal">+ Add Enquiry</button>
</div>

<div class="card border-0 shadow-sm overflow-hidden mb-4">
    <div class="table-responsive mb-0 border-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="text-secondary opacity-75 small text-uppercase">Name</th>
                    <th class="text-secondary opacity-75 small text-uppercase">Phone</th>
                    <th class="text-secondary opacity-75 small text-uppercase">City</th>
                    <th class="text-secondary opacity-75 small text-uppercase">Project</th>
                    <th class="text-secondary opacity-75 small text-uppercase">Source</th>
                    <th class="text-secondary opacity-75 small text-uppercase">Status</th>
                    <th class="text-secondary opacity-75 small text-uppercase">Date</th>
                    <th class="text-secondary opacity-75 small text-uppercase text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enquiries as $e)
                <tr>
                    <td class="fw-bold text-dark">{{ $e->name }}</td>
                    <td class="fw-semibold">{{ $e->phone }}</td>
                    <td class="text-secondary small">{{ $e->city }}</td>
                    <td class="text-secondary small">{{ Str::limit($e->project,30) }}</td>
                    <td class="text-secondary small">{{ $e->source }}</td>
                    <td>
                        <span class="badge rounded-pill fw-semibold bg-{{ $e->status==='pending'?'primary bg-opacity-10 text-primary border border-primary-subtle':($e->status==='converted'?'success bg-opacity-10 text-success border border-success-subtle':'danger bg-opacity-10 text-danger border border-danger-subtle') }}">
                            {{ ucfirst($e->status) }}
                        </span>
                    </td>
                    <td class="text-secondary small text-nowrap">{{ $e->created_at?->format('M d, Y') }}</td>
                    <td class="text-end text-nowrap">
                        <div class="d-flex gap-1 justify-content-end align-items-center">
                            {{-- Communication Tools --}}
                            <a href="tel:{{ $e->phone }}" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center" title="Call" style="width: 32px; height: 32px; background:rgba(63,185,80,.1);color:#3fb950;"><i class="fas fa-phone"></i></a>
                            @if($e->email)
                            <a href="mailto:{{ $e->email }}" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center" title="Email" style="width: 32px; height: 32px; background:rgba(212,160,23,.1);color:var(--bs-warning);"><i class="fas fa-envelope"></i></a>
                            @endif
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$e->phone) }}" target="_blank" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center" title="WhatsApp" style="width: 32px; height: 32px; background:rgba(88,166,255,.1);color:var(--bs-primary);"><i class="fab fa-whatsapp"></i></a>

                            {{-- Workflow Actions --}}
                            @if($e->status==='pending')
                            <a href="{{ route('admin.enquiries.convert',$e->id) }}" class="btn btn-sm btn-outline-success border fw-bold d-flex align-items-center px-3" title="Promote to Lead">Convert ➔</a>
                            <a href="{{ route('admin.enquiries.spam',$e->id) }}" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger" title="Mark Spam" style="width: 32px; height: 32px;"><i class="fas fa-ban"></i></a>
                            @elseif($e->status==='converted' && $e->lead)
                            <a href="{{ route('admin.leads.show', $e->lead->id) }}" class="btn btn-sm btn-outline-primary border fw-bold d-flex align-items-center px-3">View Lead ➜</a>
                            @endif
                            
                            {{-- Permanent CRUD Actions --}}
                            <button type="button" class="btn btn-sm btn-light border rounded-circle d-flex align-items-center justify-content-center" title="Edit" style="width: 32px; height: 32px;" onclick="openEnquiryEditModal({{ $e->id }}, '{{ addslashes($e->name) }}', '{{ $e->phone }}', '{{ @addslashes($e->email) }}', '{{ addslashes($e->city) }}', '{{ addslashes($e->project) }}', '{{ $e->budget }}', '{{ $e->source }}', '{{ $e->campaign }}', '{{ $e->status }}')"><i class="fas fa-pencil-alt text-secondary"></i></button>

                            <form method="POST" action="{{ route('admin.enquiries.destroy',$e->id) }}" class="d-inline" onsubmit="return confirm('Delete permanently?')">
                                @csrf @method('DELETE')<button class="btn btn-sm border-0 bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center" type="submit" style="width: 32px; height: 32px; opacity:0.7;"><i class="fas fa-times"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-secondary py-5">No enquiries found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Enquiry Modal -->
<div class="modal fade" id="addEnquiryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bolder">Add Enquiry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('admin.enquiries.store') }}">@csrf
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label small text-secondary fw-bold mb-1">Name</label><input class="form-control" name="name"></div>
                        <div class="col-md-6"><label class="form-label small text-secondary fw-bold mb-1">Phone</label><input class="form-control" name="phone"></div>
                        <div class="col-md-6"><label class="form-label small text-secondary fw-bold mb-1">Email</label><input class="form-control" name="email" type="email"></div>
                        <div class="col-md-6"><label class="form-label small text-secondary fw-bold mb-1">City</label><input class="form-control" name="city"></div>
                        <div class="col-md-6"><label class="form-label small text-secondary fw-bold mb-1">Project</label><input class="form-control" name="project"></div>
                        <div class="col-md-6"><label class="form-label small text-secondary fw-bold mb-1">Budget</label><input class="form-control" name="budget"></div>
                        <div class="col-12"><label class="form-label small text-secondary fw-bold mb-1">Source</label>
                            <select class="form-select" name="source">
                                <option>Landing Page Form</option>
                                <option>Meta Lead Form (Facebook / Instagram)</option>
                                <option>Manual Entry (Admin / Staff)</option>
                                <option>Other Sources (Google Ads / Referral)</option>
                            </select>
                        </div>
                        <div class="col-12"><label class="form-label small text-secondary fw-bold mb-1">Campaign Name</label><input class="form-control" name="campaign"></div>
                    </div>
                    <div class="d-flex gap-2 mt-4 pt-3 border-top">
                        <button class="btn btn-primary px-4 fw-bold">Save</button>
                        <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Enquiry Modal -->
<div class="modal fade" id="editEnquiryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bolder">Edit Enquiry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editEnquiryForm" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label small text-secondary fw-bold mb-1">Name</label><input class="form-control" name="name" id="ee_name"></div>
                        <div class="col-md-6"><label class="form-label small text-secondary fw-bold mb-1">Phone</label><input class="form-control" name="phone" id="ee_phone"></div>
                        <div class="col-md-6"><label class="form-label small text-secondary fw-bold mb-1">Email</label><input class="form-control" name="email" id="ee_email" type="email"></div>
                        <div class="col-md-6"><label class="form-label small text-secondary fw-bold mb-1">City</label><input class="form-control" name="city" id="ee_city"></div>
                        <div class="col-12"><label class="form-label small text-secondary fw-bold mb-1">Project</label><input class="form-control" name="project" id="ee_project"></div>
                        <div class="col-md-6"><label class="form-label small text-secondary fw-bold mb-1">Budget</label><input class="form-control" name="budget" id="ee_budget"></div>
                        <div class="col-md-6"><label class="form-label small text-secondary fw-bold mb-1">Campaign Name</label><input class="form-control" name="campaign" id="ee_campaign"></div>
                        <div class="col-12"><label class="form-label small text-secondary fw-bold mb-1">Source</label>
                            <select class="form-select" name="source" id="ee_source">
                                <option>Landing Page Form</option>
                                <option>Meta Lead Form (Facebook / Instagram)</option>
                                <option>Manual Entry (Admin / Staff)</option>
                                <option>Other Sources (Google Ads / Referral)</option>
                            </select>
                        </div>
                        <div class="col-12"><label class="form-label small text-secondary fw-bold mb-1">Status</label>
                            <select class="form-select" name="status" id="ee_status">
                                <option value="pending">Pending</option>
                                <option value="converted">Converted</option>
                                <option value="spam">Spam</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4 pt-3 border-top">
                        <button class="btn btn-primary fw-bold px-4">Update</button>
                        <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openEnquiryEditModal(id, name, phone, email, city, project, budget, source, campaign, status) {
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
        
        const modal = new bootstrap.Modal(document.getElementById('editEnquiryModal'));
        modal.show();
    }
</script>
@endpush
@endsection
