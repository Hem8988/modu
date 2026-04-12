@extends('layouts.admin')
@section('title', 'Installation Command')
@section('content')
<div style="display:grid; grid-template-columns: repeat(4, 1fr); gap:20px; margin-bottom:30px">
    <div class="card" style="margin-bottom:0; text-align:center">
        <div style="font-size:28px; font-weight:800; color:var(--warning)">{{ $stats['pending'] }}</div>
        <div style="font-size:11px; font-weight:800; color:var(--muted); text-transform:uppercase; letter-spacing:1px">Pending Dispatch</div>
    </div>
    <div class="card" style="margin-bottom:0; text-align:center">
        <div style="font-size:28px; font-weight:800; color:var(--accent)">{{ $stats['active'] }}</div>
        <div style="font-size:11px; font-weight:800; color:var(--muted); text-transform:uppercase; letter-spacing:1px">Active on Site</div>
    </div>
    <div class="card" style="margin-bottom:0; text-align:center">
        <div style="font-size:28px; font-weight:800; color:var(--success)">{{ $stats['today'] }}</div>
        <div style="font-size:11px; font-weight:800; color:var(--muted); text-transform:uppercase; letter-spacing:1px">Deploying Today</div>
    </div>
    <div class="card" style="margin-bottom:0; text-align:center">
        <div style="font-size:28px; font-weight:800; color:var(--text)">{{ $stats['completed'] }}</div>
        <div style="font-size:11px; font-weight:800; color:var(--muted); text-transform:uppercase; letter-spacing:1px">Projects Finalized</div>
    </div>
</div>

<div class="card">
    <div class="card-title" style="display:flex; justify-content:space-between; align-items:center">
        <span>Installation Registry Hub</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Lead / Client</th>
                <th>Deployment Team</th>
                <th>Schedule Date</th>
                <th>Status Pulse</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($installations as $inst)
            <tr>
                <td style="font-weight:700">
                    <a href="{{ route('admin.leads.show', $inst->lead_id) }}" style="color:var(--accent); text-decoration:none">
                        #{{ $inst->lead_id }} {{ $inst->lead->name }}
                    </a>
                    <div style="font-size:11px; color:var(--muted); font-weight:500">{{ $inst->lead->address }}</div>
                </td>
                <td style="font-weight:600">🛠 {{ $inst->team ?? 'Assigned Team' }}</td>
                <td style="font-weight:600">{{ \Carbon\Carbon::parse($inst->date)->format('M d, Y') }}</td>
                <td>
                    <form action="{{ route('admin.installations.update', $inst->id) }}" method="POST" style="display:inline">
                        @csrf @method('PUT')
                        <select name="status" onchange="this.form.submit()" class="form-control" style="font-size:11px; font-weight:800; text-transform:uppercase; border:none; background:rgba(0,0,0,0.03); width:auto">
                            <option value="scheduled" {{ $inst->status === 'scheduled' ? 'selected' : '' }}>🟡 Scheduled</option>
                            <option value="in_progress" {{ $inst->status === 'in_progress' ? 'selected' : '' }}>🔵 Active</option>
                            <option value="completed" {{ $inst->status === 'completed' ? 'selected' : '' }}>🟢 Finalized</option>
                            <option value="rescheduled" {{ $inst->status === 'rescheduled' ? 'selected' : '' }}>🔴 Delayed</option>
                        </select>
                    </form>
                </td>
                <td>
                    <div style="display:flex; gap:10px">
                        <button class="btn btn-sm btn-primary" onclick="alert('Syncing dispatch logs...')"><i class="fas fa-sync"></i></button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="margin-top:20px">
        {{ $installations->links() }}
    </div>
</div>
@endsection
