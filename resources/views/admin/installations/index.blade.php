@extends('layouts.admin')
@section('title', 'Installation Command')
@section('content')
<div class="row row-cols-2 row-cols-md-4 g-3 mb-4">
    <div class="col">
        <div class="card border-0 shadow-sm text-center p-4 h-100 border-top border-warning border-4">
            <div class="fs-2 fw-bolder text-warning">{{ $stats['pending'] }}</div>
            <div class="small fw-bold text-secondary text-uppercase mt-1" style="font-size: 0.7rem; letter-spacing: 1px;">Pending Dispatch</div>
        </div>
    </div>
    <div class="col">
        <div class="card border-0 shadow-sm text-center p-4 h-100 border-top border-primary border-4">
            <div class="fs-2 fw-bolder text-primary">{{ $stats['active'] }}</div>
            <div class="small fw-bold text-secondary text-uppercase mt-1" style="font-size: 0.7rem; letter-spacing: 1px;">Active on Site</div>
        </div>
    </div>
    <div class="col">
        <div class="card border-0 shadow-sm text-center p-4 h-100 border-top border-success border-4">
            <div class="fs-2 fw-bolder text-success">{{ $stats['today'] }}</div>
            <div class="small fw-bold text-secondary text-uppercase mt-1" style="font-size: 0.7rem; letter-spacing: 1px;">Deploying Today</div>
        </div>
    </div>
    <div class="col">
        <div class="card border-0 shadow-sm text-center p-4 h-100 border-top border-dark border-4">
            <div class="fs-2 fw-bolder text-dark">{{ $stats['completed'] }}</div>
            <div class="small fw-bold text-secondary text-uppercase mt-1" style="font-size: 0.7rem; letter-spacing: 1px;">Projects Finalized</div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
        <span class="fs-6 fw-bold">Installation Registry Hub</span>
    </div>
    <div class="table-responsive border-0 mb-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="py-3 px-3 text-secondary opacity-75 small text-uppercase" style="font-size: 0.8rem;">Lead / Client</th>
                    <th class="py-3 px-3 text-secondary opacity-75 small text-uppercase" style="font-size: 0.8rem;">Deployment Team</th>
                    <th class="py-3 px-3 text-secondary opacity-75 small text-uppercase" style="font-size: 0.8rem;">Schedule Date</th>
                    <th class="py-3 px-3 text-secondary opacity-75 small text-uppercase" style="font-size: 0.8rem;">Status Pulse</th>
                    <th class="py-3 px-3 text-secondary opacity-75 small text-uppercase" style="font-size: 0.8rem;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($installations as $inst)
                <tr>
                    <td class="p-3 border-bottom">
                        <a href="{{ route('admin.leads.show', $inst->lead_id) }}" class="text-primary text-decoration-none fw-bold">
                            #{{ $inst->lead_id }} {{ $inst->lead->name }}
                        </a>
                        <div class="small text-secondary fw-semibold mt-1">{{ $inst->lead->address }}</div>
                    </td>
                    <td class="p-3 border-bottom fw-bold text-dark">🛠 {{ $inst->team ?? 'Assigned Team' }}</td>
                    <td class="p-3 border-bottom fw-bold text-dark">{{ \Carbon\Carbon::parse($inst->date)->format('M d, Y') }}</td>
                    <td class="p-3 border-bottom">
                        <form action="{{ route('admin.installations.update', $inst->id) }}" method="POST" class="d-inline">
                            @csrf @method('PUT')
                            <select name="status" onchange="this.form.submit()" class="form-select form-select-sm fw-bold border-0 bg-light text-uppercase d-inline-block w-auto" style="font-size: 0.75rem;">
                                <option value="scheduled" {{ $inst->status === 'scheduled' ? 'selected' : '' }}>🟡 Scheduled</option>
                                <option value="in_progress" {{ $inst->status === 'in_progress' ? 'selected' : '' }}>🔵 Active</option>
                                <option value="completed" {{ $inst->status === 'completed' ? 'selected' : '' }}>🟢 Finalized</option>
                                <option value="rescheduled" {{ $inst->status === 'rescheduled' ? 'selected' : '' }}>🔴 Delayed</option>
                            </select>
                        </form>
                    </td>
                    <td class="p-3 border-bottom">
                        <button class="btn btn-sm btn-primary rounded-3 text-white fw-bold d-flex align-items-center justify-content-center" onclick="alert('Syncing dispatch logs...')" style="width: 32px; height: 32px;" title="Sync Logs"><i class="fas fa-sync"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">
    {{ $installations->links('pagination::bootstrap-5') }}
</div>
@endsection
