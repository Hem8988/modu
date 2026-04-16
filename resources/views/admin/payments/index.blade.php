@extends('layouts.admin')
@section('title','Payments')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <h2 class="fs-4 fw-bolder text-dark mb-0">Payments Registry</h2>
    <button type="button" class="btn btn-primary fw-bold text-nowrap shadow-sm px-4 py-2 rounded-3" data-bs-toggle="modal" data-bs-target="#addPayModal">+ Record Project Deposit</button>
</div>

<div class="card border-0 shadow-sm overflow-hidden mb-4 rounded-4">
    <div class="table-responsive mb-0 border-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="py-3 px-3 text-secondary opacity-75 small text-uppercase">Customer / Project</th>
                    <th class="py-3 px-3 text-secondary opacity-75 small text-uppercase">Invoice Registry</th>
                    <th class="py-3 px-3 text-secondary opacity-75 small text-uppercase">Amount Received</th>
                    <th class="py-3 px-3 text-secondary opacity-75 small text-uppercase">Transfer Mode</th>
                    <th class="py-3 px-3 text-secondary opacity-75 small text-uppercase">Registry Date</th>
                    <th class="py-3 px-3 text-secondary opacity-75 small text-uppercase">Balance Due</th>
                    <th class="py-3 px-3 text-secondary opacity-75 small text-uppercase text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $p)
                <tr>
                    <td class="p-3 border-bottom">
                        <div class="fw-bolder text-dark">{{ $p->customer?->name }}</div>
                        <div class="small text-secondary fw-bold mt-1" style="font-size: 0.75rem;">Ref: #C{{ $p->customer?->id }}</div>
                    </td>
                    <td class="p-3 border-bottom text-primary fw-bold">{{ $p->invoice?->invoice_number }}</td>
                    <td class="p-3 border-bottom text-success fw-bolder fs-6">${{ number_format($p->amount,2) }}</td>
                    <td class="p-3 border-bottom">
                        @php 
                            $mode = strtolower($p->mode);
                            $badgeClass = $mode === 'cash' ? 'bg-warning text-dark border-warning' : ($mode === 'bank transfer' ? 'bg-primary text-primary bg-opacity-10 border-primary' : 'bg-info text-info bg-opacity-10 border-info');
                        @endphp
                        <span class="badge border {{ $badgeClass }}-subtle {{ $badgeClass }} px-2 py-1 text-uppercase fw-bolder">{{ $p->mode }}</span>
                    </td>
                    <td class="p-3 border-bottom text-secondary fw-semibold small">{{ $p->date?->format('M d, Y') }}</td>
                    <td class="p-3 border-bottom fw-bolder text-danger">${{ number_format($p->invoice?->due ?? 0, 2) }}</td>
                    <td class="p-3 border-bottom text-end text-nowrap">
                        <div class="d-flex gap-2 justify-content-end align-items-center">
                            <a href="{{ route('admin.payments.print',$p->id) }}" target="_blank" class="btn btn-sm btn-light border text-secondary d-flex justify-content-center align-items-center rounded-3" title="Print Receipt" style="width:32px; height:32px;"><i class="fas fa-file-pdf"></i></a>
                            <a href="mailto:{{ $p->customer?->email }}?subject=Payment Receipt - Ref: #{{ $p->invoice?->invoice_number }}" class="btn btn-sm btn-light border text-primary d-flex justify-content-center align-items-center rounded-3 bg-primary bg-opacity-10" title="Email Confirmation" style="width:32px; height:32px;"><i class="fas fa-envelope"></i></a>
                            <button onclick="openEditPayModal({{ $p->id }}, {{ $p->amount }}, '{{ $p->mode }}', '{{ addslashes($p->notes) }}')" class="btn btn-sm btn-light border text-secondary d-flex justify-content-center align-items-center rounded-3" title="Adjust Entry" style="width:32px; height:32px;"><i class="fas fa-pencil-alt"></i></button>
                            <form method="POST" action="{{ route('admin.payments.destroy',$p->id) }}" class="d-inline" onsubmit="return confirm('Archive record?')">
                                @csrf @method('DELETE')<button class="btn btn-sm btn-light border border-danger-subtle text-danger bg-danger bg-opacity-10 d-flex justify-content-center align-items-center rounded-3" type="submit" style="width:32px; height:32px;"><i class="fas fa-times"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-secondary py-5">Zero transactions in registry.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- High-Fidelity Record Modal --}}
<div class="modal fade" id="addPayModal" tabindex="-1" aria-hidden="true" style="backdrop-filter: blur(5px);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h4 class="modal-title fw-bolder">Record Project Deposit</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" action="{{ route('admin.payments.store') }}">@csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1" style="font-size: 0.75rem;">Select Project Invoice</label>
                            <select class="form-select bg-light fw-bold py-2" name="invoice_id" required>
                                @foreach($unpaidInvoices as $inv)
                                <option value="{{ $inv->id }}">{{ $inv->invoice_number }} — {{ $inv->customer?->name }} (Due: ${{ number_format($inv->due,2) }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1" style="font-size: 0.75rem;">Amount Received ($)</label>
                            <input type="number" step="0.01" class="form-control bg-light fw-bold text-success fs-5 py-2" name="amount" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1" style="font-size: 0.75rem;">Transfer Mode</label>
                            <select class="form-select bg-light fw-bold py-2 text-center text-md-start" name="mode">
                                <option>Cash</option>
                                <option>Bank Transfer</option>
                                <option>UPI / Digital</option>
                            </select>
                        </div>
                        <div class="col-12 mt-3">
                            <label class="form-label small fw-bold text-secondary text-uppercase mb-1" style="font-size: 0.75rem;">Transaction Notes</label>
                            <textarea class="form-control bg-light" name="notes" rows="2" placeholder="Internal reference..."></textarea>
                        </div>
                    </div>
                    <div class="mt-4 pt-2">
                        <button class="btn btn-primary fw-bolder w-100 py-3 rounded-3 shadow-sm fs-6" type="submit">Confirm Deposit to Registry</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Edit Payment Modal --}}
<div class="modal fade" id="editPayModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h4 class="modal-title fw-bolder">Edit Payment</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="editPayForm" method="POST">@csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary mb-1">Amount ($)</label>
                            <input type="number" step="0.01" class="form-control bg-light fw-bold" name="amount" id="epay_amount" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary mb-1">Payment Mode</label>
                            <select class="form-select bg-light fw-bold" name="mode" id="epay_mode">
                                <option>Cash</option>
                                <option>Bank Transfer</option>
                                <option>UPI</option>
                            </select>
                        </div>
                        <div class="col-12 mt-3">
                            <label class="form-label small fw-bold text-secondary mb-1">Notes</label>
                            <textarea class="form-control bg-light" name="notes" id="epay_notes" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4 pt-3 border-top">
                        <button class="btn btn-primary fw-bold px-4" type="submit">Update Payment</button>
                        <button type="button" class="btn btn-light fw-bold px-4" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openEditPayModal(id, amount, mode, notes) {
        document.getElementById('editPayForm').action = '/admin/payments/' + id;
        document.getElementById('epay_amount').value = amount;
        document.getElementById('epay_mode').value = mode;
        document.getElementById('epay_notes').value = notes;
        
        const modal = new bootstrap.Modal(document.getElementById('editPayModal'));
        modal.show();
    }
</script>
@endpush
@endsection
