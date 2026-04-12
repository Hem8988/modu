@extends('layouts.admin')
@section('title','Payments')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
    <h2 style="font-size:18px;font-weight:700;margin:0">Payments Registry</h2>
    <button onclick="document.getElementById('addPayModal').style.display='flex'" class="btn btn-primary" style="padding:10px 24px; font-weight:800; border-radius:10px; box-shadow:0 10px 25px rgba(37,99,235,0.15);">+ Record Project Deposit</button>
</div>

<div class="card" style="padding:0;overflow:hidden">
    <table>
        <thead><tr><th>Customer / Project</th><th>Invoice Registry</th><th>Amount Received</th><th>Transfer Mode</th><th>Registry Date</th><th>Balance Due</th><th>Actions</th></tr></thead>
        <tbody>
        @forelse($payments as $p)
        <tr>
            <td>
                <div style="font-weight:800;color:#000">{{ $p->customer?->name }}</div>
                <div style="font-size:11px;color:var(--muted);font-weight:700">Ref: #C{{ $p->customer?->id }}</div>
            </td>
            <td style="color:var(--accent); font-weight:700;">{{ $p->invoice?->invoice_number }}</td>
            <td style="color:#10b981;font-weight:800; font-size:15px;">${{ number_format($p->amount,2) }}</td>
            <td>
                @php 
                    $mode = strtolower($p->mode);
                    $color = $mode === 'cash' ? '#b89b5e' : ($mode === 'bank transfer' ? 'var(--accent)' : '#8b5cf6');
                    $bg = $mode === 'cash' ? 'rgba(184,155,94,.1)' : ($mode === 'bank transfer' ? 'rgba(37,99,235,.1)' : 'rgba(139,92,246,.1)');
                @endphp
                <span style="background:{{ $bg }}; color:{{ $color }}; padding:4px 10px; border-radius:6px; font-size:10px; font-weight:800; text-transform:uppercase;">{{ $p->mode }}</span>
            </td>
            <td style="color:var(--muted); font-weight:600">{{ $p->date?->format('M d, Y') }}</td>
            <td style="font-weight:800; color:var(--danger)">${{ number_format($p->invoice?->due ?? 0, 2) }}</td>
            <td style="white-space:nowrap">
                <div style="display:flex; gap:6px; align-items:center;">
                    <a href="{{ route('admin.payments.print',$p->id) }}" target="_blank" class="btn btn-sm" title="Print Receipt" style="background:var(--surface2); width:28px; height:28px; padding:0; display:flex; align-items:center; justify-content:center; border-radius:6px; font-size:12px;">📄</a>
                    <a href="mailto:{{ $p->customer?->email }}?subject=Payment Receipt - Ref: #{{ $p->invoice?->invoice_number }}" class="btn btn-sm" title="Email Confirmation" style="background:rgba(37,99,235,0.08); color:var(--accent); width:28px; height:28px; padding:0; display:flex; align-items:center; justify-content:center; border-radius:6px; font-size:12px;">✉</a>
                    <button onclick="openEditPayModal({{ $p->id }}, {{ $p->amount }}, '{{ $p->mode }}', '{{ addslashes($p->notes) }}')" class="btn btn-sm" title="Adjust Entry" style="background:var(--surface2); width:28px; height:28px; padding:0; display:flex; align-items:center; justify-content:center; border-radius:6px; font-size:12px;">✎</button>
                    <form method="POST" action="{{ route('admin.payments.destroy',$p->id) }}" style="display:inline" onsubmit="return confirm('Archive record?')">
                        @csrf @method('DELETE')<button class="btn btn-sm btn-danger" type="submit" style="width:28px; height:28px; padding:0; display:flex; align-items:center; justify-content:center; border-radius:6px; font-size:10px;">✕</button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;color:var(--muted);padding:30px">Zero transactions in registry.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

{{-- High-Fidelity Record Modal --}}
<div id="addPayModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.85);backdrop-filter:blur(8px);z-index:2000;align-items:center;justify-content:center">
    <div style="background:#fff;width:100%;max-width:480px;border-radius:24px;padding:32px;border:1.5px solid var(--border);box-shadow:0 15px 45px rgba(0,0,0,.2);">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
            <h3 style="color:#000;font-weight:800;font-size:20px;margin:0">Record Project Deposit</h3>
            <button onclick="document.getElementById('addPayModal').style.display='none'" style="background:none;border:none;font-size:24px;color:var(--muted);cursor:pointer">✕</button>
        </div>
        <form method="POST" action="{{ route('admin.payments.store') }}">@csrf
            <div style="display:grid;gap:16px">
                <div><label class="form-label" style="font-weight:800;font-size:11px;color:var(--muted);text-transform:uppercase;">Select Project Invoice</label>
                    <select class="form-control" name="invoice_id" required style="height:45px;border-radius:10px;font-weight:600;">
                        @foreach($unpaidInvoices as $inv)
                        <option value="{{ $inv->id }}">{{ $inv->invoice_number }} — {{ $inv->customer?->name }} (Due: ${{ number_format($inv->due,2) }})</option>
                        @endforeach
                    </select>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div>
                        <label class="form-label" style="font-weight:800;font-size:11px;color:var(--muted);text-transform:uppercase;">Amount Received ($)</label>
                        <input type="number" step="0.01" class="form-control" name="amount" required style="height:45px;border-radius:10px;font-weight:700;color:#10b981;font-size:16px;">
                    </div>
                    <div>
                        <label class="form-label" style="font-weight:800;font-size:11px;color:var(--muted);text-transform:uppercase;">Transfer Mode</label>
                        <select class="form-control" name="mode" style="height:45px;border-radius:10px;font-weight:600;">
                            <option>Cash</option>
                            <option>Bank Transfer</option>
                            <option>UPI / Digital</option>
                        </select>
                    </div>
                </div>
                <div><label class="form-label" style="font-weight:800;font-size:11px;color:var(--muted);text-transform:uppercase;">Transaction Notes</label>
                    <textarea class="form-control" name="notes" rows="2" style="border-radius:10px;padding:12px;" placeholder="Internal reference..."></textarea>
                </div>
            </div>
            <div style="margin-top:32px">
                <button class="btn btn-primary" type="submit" style="width:100%;padding:14px;border-radius:12px;font-weight:800;font-size:15px;box-shadow:0 10px 20px rgba(37,99,235,0.2);">Confirm Deposit to Registry</button>
            </div>
        </form>
    </div>
</div>
<div id="editPayModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:2000;align-items:center;justify-content:center">
    <div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:28px;width:400px">
        <h3 style="margin-bottom:20px">Edit Payment</h3>
        <form id="editPayForm" method="POST">@csrf @method('PUT')
            <div style="display:grid;gap:12px">
                <div><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">Amount ($)</label><input type="number" step="0.01" class="form-control" name="amount" id="epay_amount" required></div>
                <div><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">Payment Mode</label>
                    <select class="form-control" name="mode" id="epay_mode"><option>Cash</option><option>Bank Transfer</option><option>UPI</option></select>
                </div>
                <div><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">Notes</label><textarea class="form-control" name="notes" id="epay_notes" rows="3"></textarea></div>
            </div>
            <div style="display:flex;gap:10px;margin-top:24px">
                <button class="btn btn-primary" type="submit">Update Payment</button>
                <button type="button" onclick="document.getElementById('editPayModal').style.display='none'" class="btn" style="background:var(--surface2);color:var(--muted)">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditPayModal(id, amount, mode, notes) {
        document.getElementById('editPayModal').style.display = 'flex';
        document.getElementById('editPayForm').action = '/admin/payments/' + id;
        document.getElementById('epay_amount').value = amount;
        document.getElementById('epay_mode').value = mode;
        document.getElementById('epay_notes').value = notes;
    }
</script>
@endsection
