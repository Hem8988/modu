@extends('layouts.admin')
@section('title','Customers')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <h2 class="fs-4 fw-bolder text-dark mb-0">Customers</h2>
    <div class="d-flex gap-2">
        <form method="GET" class="d-flex gap-2">
            <input class="form-control" name="search" placeholder="Search customer…" value="{{ request('search') }}">
            <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
        </form>
        <button type="button" class="btn btn-primary fw-bold text-nowrap" data-bs-toggle="modal" data-bs-target="#addCustModal">+ Add Customer</button>
    </div>
</div>

<div class="card border-0 shadow-sm overflow-hidden">
    <div class="table-responsive mb-0 border-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="text-secondary opacity-75 small text-uppercase">Name</th>
                    <th class="text-secondary opacity-75 small text-uppercase">Contact</th>
                    <th class="text-secondary opacity-75 small text-uppercase w-25">Details</th>
                    <th class="text-secondary opacity-75 small text-uppercase text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $c)
                <tr>
                    <td><a href="{{ route('admin.customers.show',$c->id) }}" class="text-primary text-decoration-none fw-bold">{{ $c->name }}</a></td>
                    <td>
                        <div class="fw-semibold text-dark">{{ $c->phone }}</div>
                        <div class="small text-secondary">{{ $c->email }}</div>
                    </td>
                    <td class="text-secondary small">{{ Str::limit($c->project,30) }}</td>
                    <td class="text-end text-nowrap">
                        <a href="tel:{{ $c->phone }}" class="btn btn-sm btn-success rounded-circle" title="Call" style="width: 32px; height: 32px; padding: 0; line-height: 32px;"><i class="fas fa-phone"></i></a>
                        <a href="mailto:{{ $c->email }}" class="btn btn-sm btn-warning rounded-circle text-white" title="Email" style="width: 32px; height: 32px; padding: 0; line-height: 32px;"><i class="fas fa-envelope"></i></a>
                        <a href="{{ route('admin.customers.show',$c->id) }}" class="btn btn-sm btn-light border rounded-circle" title="Profile/Edit" style="width: 32px; height: 32px; padding: 0; line-height: 32px;"><i class="fas fa-pencil-alt text-secondary"></i></a>
                        <form method="POST" action="{{ route('admin.customers.destroy',$c->id) }}" class="d-inline" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger rounded-circle bg-opacity-10 text-danger border-0" type="submit" style="width: 32px; height: 32px; padding: 0; line-height: 32px;"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-secondary py-5">No customers yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Customer Modal -->
<div class="modal fade" id="addCustModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bolder">Add Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('admin.customers.store') }}">@csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small text-secondary fw-bold mb-1">Full Name</label>
                            <input class="form-control" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-secondary fw-bold mb-1">Phone</label>
                            <input class="form-control" name="phone" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-secondary fw-bold mb-1">Email</label>
                            <input class="form-control" name="email" type="email">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-secondary fw-bold mb-1">Project</label>
                            <input class="form-control" name="project" placeholder="e.g. Living room shades">
                        </div>
                        <div class="col-12 mt-2">
                            <label class="form-label small text-secondary fw-bold mb-1">Address</label>
                            <textarea class="form-control" name="address" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4 pt-2 border-top">
                        <button class="btn btn-primary px-4 fw-bold" type="submit">Create Customer</button>
                        <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
