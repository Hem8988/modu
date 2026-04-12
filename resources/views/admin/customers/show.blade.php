@extends('layouts.admin')
@section('title','Client — '.$customer->name)
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
    <a href="{{ route('admin.customers.index') }}" style="color:var(--muted);text-decoration:none;font-size:14px; font-weight:600;">← Back to Client Dashboard</a>
    <div style="display:flex; gap:10px;">
        <a href="{{ route('admin.quotations.builder', $customer->lead_id ?: 0) }}" class="btn btn-sm" style="background:rgba(37,99,235,0.08); color:var(--accent); border:1.5px solid rgba(37,99,235,0.1); padding:8px 16px; font-weight:800; border-radius:8px;">📄 NEW QUOTE</a>
        <a href="#" class="btn btn-sm" style="background:rgba(16,185,129,0.08); color:var(--success); border:1.5px solid rgba(16,185,129,0.1); padding:8px 16px; font-weight:800; border-radius:8px;">🧾 NEW INVOICE</a>
        <a href="#" onclick="alert('Receive Payment Hub Active')" class="btn btn-sm" style="background:rgba(215,153,34,0.08); color:#d29922; border:1.5px solid rgba(215,153,34,0.1); padding:8px 16px; font-weight:800; border-radius:8px;">💰 RECEIVE PAYMENT</a>
    </div>
</div>

<div style="display:flex;gap:20px;align-items:flex-start">
    <div style="flex:2.2">
        <div class="card" style="border:1.5px solid var(--border); box-shadow:0 10px 30px rgba(0,0,0,0.02)">
            <div style="font-size:11px; font-weight:800; color:var(--muted); text-transform:uppercase; letter-spacing:1px; margin-bottom:15px; border-bottom:1px solid var(--border); padding-bottom:10px;">Executive Profile</div>
            <form method="POST" action="{{ route('admin.customers.update',$customer->id) }}">
                @csrf @method('PUT')
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div><label style="font-size:11px;font-weight:700;color:var(--muted);display:block;margin-bottom:4px">Client Name</label><input class="form-control" name="name" value="{{ $customer->name }}" style="background:var(--surface2); font-weight:700;"></div>
                    <div><label style="font-size:11px;font-weight:700;color:var(--muted);display:block;margin-bottom:4px">Outreach Phone</label><input class="form-control" name="phone" value="{{ $customer->phone }}" style="background:var(--surface2); font-weight:700;"></div>
                    <div><label style="font-size:11px;font-weight:700;color:var(--muted);display:block;margin-bottom:4px">Corporate Email</label><input class="form-control" name="email" value="{{ $customer->email }}" style="background:var(--surface2); font-weight:700;"></div>
                    <div><label style="font-size:11px;font-weight:700;color:var(--muted);display:block;margin-bottom:4px">Project Specifications</label><input class="form-control" name="project" value="{{ $customer->project }}" style="background:var(--surface2); font-weight:700;"></div>
                </div>
                <div style="margin-top:12px"><label style="font-size:11px;font-weight:700;color:var(--muted);display:block;margin-bottom:4px">Site Installation Address</label><textarea class="form-control" name="address" rows="2" style="background:var(--surface2); font-weight:700;">{{ $customer->address }}</textarea></div>
                <button class="btn btn-primary" style="margin-top:20px; padding:10px 24px; font-weight:700;" type="submit">Update Client Registry</button>
            </form>
        </div>

        <div class="card" style="border:1.5px solid var(--border); box-shadow:0 10px 30px rgba(0,0,0,0.02)">
            <div style="font-size:11px; font-weight:800; color:var(--muted); text-transform:uppercase; letter-spacing:1px; margin-bottom:15px; border-bottom:1px solid var(--border); padding-bottom:10px;">Executive Invoices</div>
            <table>
                <thead><tr><th>Document #</th><th>Total Project</th><th>Paid To Date</th><th>Outstanding</th><th>Status</th><th>Management</th></tr></thead>
                <tbody>
                @forelse($customer->invoices as $inv)
                <tr>
                    <td style="font-weight:800; color:var(--accent)">{{ $inv->invoice_number }}</td>
                    <td style="font-weight:700;">${{ number_format($inv->total,2) }}</td>
                    <td style="color:var(--success); font-weight:700;">${{ number_format($inv->paid,2) }}</td>
                    <td style="color:var(--danger); font-weight:700;">${{ number_format($inv->due,2) }}</td>
                    <td><span style="background:{{ $inv->status==='paid'?'rgba(63,185,80,0.1)':'rgba(215,153,34,0.1)' }}; color:{{ $inv->status==='paid'?'#3fb950':'#d29922' }}; padding:4px 10px; border-radius:6px; font-size:10px; font-weight:800; border:1px solid rgba(0,0,0,0.05);">{{ strtoupper($inv->status) }}</span></td>
                    <td>
                        <div style="display:flex; gap:6px;">
                            <a href="#" class="btn btn-sm" style="background:#eee; color:#666; width:28px; height:28px; padding:0; display:flex; align-items:center; justify-content:center; border-radius:6px;" title="Print PDF">📄</a>
                            <a href="#" class="btn btn-sm" style="background:rgba(37,99,235,0.08); color:var(--accent); width:28px; height:28px; padding:0; display:flex; align-items:center; justify-content:center; border-radius:6px;" title="Email Attachment">✉</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;color:var(--muted);padding:40px">Zero official invoices generated for this client.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="flex:1">
        <div class="card" style="border:1.5px solid var(--border); box-shadow:0 10px 30px rgba(0,0,0,0.02)">
            <div style="font-size:11px; font-weight:800; color:var(--muted); text-transform:uppercase; letter-spacing:1px; margin-bottom:15px; border-bottom:1px solid var(--border); padding-bottom:10px;">Executive Audit History</div>
            @forelse($logs as $log)
            <div style="padding:12px;background:var(--surface2);border-radius:10px;margin-bottom:10px; border:1px solid var(--border);">
                <div style="font-weight:800; font-size:13px; color:var(--accent);">{{ strtoupper($log->title) }}</div>
                <p style="color:var(--muted);margin:4px 0; font-size:12px; font-weight:500;">{{ $log->notes }}</p>
                <div style="color:var(--muted); font-size:10px; font-weight:800; margin-top:6px;">🕒 {{ $log->created_at?->format('M d, h:i A') }}</div>
            </div>
            @empty
            <p style="color:var(--muted);font-size:13px; padding:20px; text-align:center;">No high-fidelity activity recorded.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
