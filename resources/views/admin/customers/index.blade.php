@extends('layouts.admin')
@section('title','Customers')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
    <h2 style="font-size:18px;font-weight:600">Customers</h2>
    <div style="display:flex;gap:12px">
        <form method="GET" style="display:flex;gap:8px">
            <input class="form-control" name="search" placeholder="Search customer…" value="{{ request('search') }}">
        </form>
        <button onclick="document.getElementById('addCustModal').style.display='flex'" class="btn btn-primary">+ Add Customer</button>
    </div>
</div>
<div class="card" style="padding:0;overflow:hidden">
    <table>
        <thead><tr><th>Name</th><th>Contact</th><th>Details</th><th>Actions</th></tr></thead>
        <tbody>
        @forelse($customers as $c)
        <tr>
            <td><a href="{{ route('admin.customers.show',$c->id) }}" style="color:var(--accent);text-decoration:none;font-weight:500">{{ $c->name }}</a></td>
            <td>
                <div style="font-size:14px">{{ $c->phone }}</div>
                <div style="font-size:12px;color:var(--muted)">{{ $c->email }}</div>
            </td>
            <td style="color:var(--muted)">{{ Str::limit($c->project,30) }}</td>
            <td style="white-space:nowrap">
                <a href="tel:{{ $c->phone }}" class="btn btn-sm" title="Call" style="background:#238636;color:#fff">☎</a>
                <a href="mailto:{{ $c->email }}" class="btn btn-sm" title="Email" style="background:#d4a017;color:#000">@</a>
                <a href="{{ route('admin.customers.show',$c->id) }}" class="btn btn-sm" title="Profile/Edit">✎</a>
                <form method="POST" action="{{ route('admin.customers.destroy',$c->id) }}" style="display:inline" onsubmit="return confirm('Delete?')">
                    @csrf @method('DELETE')<button class="btn btn-sm btn-danger" type="submit">✕</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;color:var(--muted);padding:30px">No customers yet</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div id="addCustModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:2000;align-items:center;justify-content:center">
    <div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:28px;width:440px">
        <h3 style="margin-bottom:20px">Add Customer</h3>
        <form method="POST" action="{{ route('admin.customers.store') }}">@csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                <div><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">Full Name</label><input class="form-control" name="name" required></div>
                <div><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">Phone</label><input class="form-control" name="phone" required></div>
                <div><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">Email</label><input class="form-control" name="email" type="email"></div>
                <div><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">Project</label><input class="form-control" name="project" placeholder="e.g. Living room shades"></div>
            </div>
            <div style="margin-top:12px"><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">Address</label><textarea class="form-control" name="address" rows="2"></textarea></div>
            <div style="display:flex;gap:10px;margin-top:24px">
                <button class="btn btn-primary" type="submit">Create Customer</button>
                <button type="button" onclick="document.getElementById('addCustModal').style.display='none'" class="btn" style="background:var(--surface2);color:var(--muted)">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection
