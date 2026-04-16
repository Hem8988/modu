@extends('layouts.admin')
@section('title','Client — '.$customer->name)
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <a href="{{ route('admin.customers.index') }}" class="text-secondary text-decoration-none fw-semibold small">
        <i class="fas fa-arrow-left me-1"></i> Back to Client Dashboard
    </a>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.quotations.builder', $customer->lead_id ?: 0) }}" class="btn btn-sm btn-outline-primary fw-bold text-uppercase d-flex align-items-center gap-2"><i class="fas fa-file-contract"></i> New Quote</a>
        <a href="#" class="btn btn-sm btn-outline-success fw-bold text-uppercase d-flex align-items-center gap-2"><i class="fas fa-receipt"></i> New Invoice</a>
        <a href="#" onclick="alert('Receive Payment Hub Active')" class="btn btn-sm btn-outline-warning text-dark fw-bold text-uppercase d-flex align-items-center gap-2"><i class="fas fa-money-bill-wave"></i> Receive Payment</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-lg-8">
        {{-- Executive Profile --}}
        <div class="card border border-light-subtle shadow-sm mb-4 rounded-4">
            <div class="card-header bg-white border-bottom py-3">
                <span class="text-uppercase text-secondary fw-bold small" style="letter-spacing:1px;">Executive Profile</span>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.customers.update',$customer->id) }}">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary mb-1">Client Name</label>
                            <input class="form-control bg-light fw-bold" name="name" value="{{ $customer->name }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary mb-1">Outreach Phone</label>
                            <input class="form-control bg-light fw-bold" name="phone" value="{{ $customer->phone }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary mb-1">Corporate Email</label>
                            <input class="form-control bg-light fw-bold" name="email" value="{{ $customer->email }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary mb-1">Project Specifications</label>
                            <input class="form-control bg-light fw-bold" name="project" value="{{ $customer->project }}">
                        </div>
                        <div class="col-12 mt-2">
                            <label class="form-label small fw-bold text-secondary mb-1">Site Installation Address</label>
                            <textarea class="form-control bg-light fw-bold" name="address" rows="2">{{ $customer->address }}</textarea>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-top">
                        <button class="btn btn-primary fw-bold px-4" type="submit">Update Client Registry</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Executive Invoices --}}
        <div class="card border border-light-subtle shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom py-3">
                <span class="text-uppercase text-secondary fw-bold small" style="letter-spacing:1px;">Executive Invoices</span>
            </div>
            <div class="table-responsive border-0 mb-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-3 text-secondary opacity-75 small text-uppercase">Document #</th>
                            <th class="py-3 px-3 text-secondary opacity-75 small text-uppercase">Total Project</th>
                            <th class="py-3 px-3 text-secondary opacity-75 small text-uppercase">Paid To Date</th>
                            <th class="py-3 px-3 text-secondary opacity-75 small text-uppercase">Outstanding</th>
                            <th class="py-3 px-3 text-secondary opacity-75 small text-uppercase">Status</th>
                            <th class="py-3 px-3 text-secondary opacity-75 small text-uppercase">Management</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customer->invoices as $inv)
                        <tr>
                            <td class="p-3 border-bottom fw-bold text-primary">{{ $inv->invoice_number }}</td>
                            <td class="p-3 border-bottom fw-bold">${{ number_format($inv->total,2) }}</td>
                            <td class="p-3 border-bottom fw-bold text-success">${{ number_format($inv->paid,2) }}</td>
                            <td class="p-3 border-bottom fw-bold text-danger">${{ number_format($inv->due,2) }}</td>
                            <td class="p-3 border-bottom">
                                <span class="badge border {{ $inv->status==='paid'?'bg-success text-success border-success-subtle':'bg-warning text-warning border-warning-subtle' }} bg-opacity-10 px-2 py-1 text-uppercase fw-bolder">{{ $inv->status }}</span>
                            </td>
                            <td class="p-3 border-bottom">
                                <div class="d-flex gap-2">
                                    <a href="#" class="btn btn-sm btn-light border text-secondary d-flex justify-content-center align-items-center rounded-3" title="Print PDF" style="width:28px; height:28px;"><i class="fas fa-file-pdf"></i></a>
                                    <a href="#" class="btn btn-sm btn-light border text-primary d-flex justify-content-center align-items-center rounded-3 bg-primary bg-opacity-10" title="Email Attachment" style="width:28px; height:28px;"><i class="fas fa-envelope"></i></a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-secondary py-5 small">Zero official invoices generated for this client.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        {{-- Audit History --}}
        <div class="card border border-light-subtle shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom py-3">
                <span class="text-uppercase text-secondary fw-bold small" style="letter-spacing:1px;">Executive Audit History</span>
            </div>
            <div class="card-body p-4">
                @forelse($logs as $log)
                <div class="bg-light p-3 rounded-3 mb-3 border">
                    <div class="fw-bolder text-primary small" style="font-size: 0.75rem;">{{ strtoupper($log->title) }}</div>
                    <p class="text-secondary small fw-semibold my-2">{{ $log->notes }}</p>
                    <div class="text-secondary fw-bold" style="font-size: 0.65rem;"><i class="far fa-clock me-1"></i> {{ $log->created_at?->format('M d, h:i A') }}</div>
                </div>
                @empty
                <div class="text-center text-secondary py-4 small">No high-fidelity activity recorded.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
