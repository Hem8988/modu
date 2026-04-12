@extends('layouts.admin')
@section('title','Invoices')
@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px">
    <h2 style="font-size:18px;font-weight:700;margin:0;">Invoice Ledger</h2>
    <button onclick="document.getElementById('manualInvoiceModal').style.display='flex'" class="btn btn-primary" style="padding:10px 24px; font-weight:800; border-radius:10px; box-shadow:0 10px 25px rgba(37,99,235,0.15);">+ Issue Manual Invoice</button>
</div>

{{-- High-Fidelity Client Selector Modal for Invoices --}}
<div id="manualInvoiceModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.85);backdrop-filter:blur(8px);z-index:2000;align-items:center;justify-content:center">
    <div style="background:#fff;width:90%;max-width:800px;border-radius:24px;padding:32px;border:1.5px solid var(--border);box-shadow:0 15px 45px rgba(0,0,0,.2);display:flex;flex-direction:column;max-height:80vh;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
            <h3 style="color:#000;font-weight:800;font-size:20px;margin:0">Billing Client Selector</h3>
            <button onclick="document.getElementById('manualInvoiceModal').style.display='none'" style="background:none;border:none;font-size:24px;color:var(--muted);cursor:pointer">✕</button>
        </div>
        
        <div style="position:relative;margin-bottom:20px">
            <input type="text" id="invoiceSearchInput" onkeyup="filterManualInvoices()" placeholder="Search project registry (Name or Ref)..." style="width:100%;padding:15px 45px;background:var(--surface2);border-radius:12px;border:1.5px solid var(--border);font-family:inherit;font-size:14px;font-weight:600;">
            <span style="position:absolute;left:18px;top:15px;opacity:0.4">🔍</span>
        </div>

        <div id="manualInvoiceList" style="flex:1;overflow-y:auto;display:grid;gap:12px;">
            @php $allClients = \App\Models\Customer::latest()->get(); @endphp
            @forelse($allClients as $client)
            <div class="invoice-client-item" style="background:var(--surface2);border:1.5px solid var(--border);border-radius:14px;padding:16px;display:flex;align-items:center;justify-content:space-between;">
                <div style="display:flex;align-items:center;gap:16px">
                    <div style="width:40px;height:40px;background:rgba(184,155,94,.1);border-radius:10px;display:flex;align-items:center;justify-content:center;font-weight:800;color:var(--gold)">
                        {{ strtoupper(substr($client->name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight:800;color:#000;font-size:15px">{{ $client->name }}</div>
                        <div style="font-size:11px;color:var(--muted);font-weight:700">Ref: #C{{ $client->id }} | {{ $client->project ?? 'Site Visit' }}</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.invoices.store') }}">
                    @csrf
                    <input type="hidden" name="customer_id" value="{{ $client->id }}">
                    <input type="hidden" name="total" value="0">
                    <input type="hidden" name="due" value="0">
                    <input type="hidden" name="status" value="unpaid">
                    <input type="hidden" name="due_date" value="{{ date('Y-m-d', strtotime('+7 days')) }}">
                    <button type="submit" class="btn btn-sm" style="background:var(--gold);color:#fff;padding:8px 16px;border-radius:8px;font-weight:800;">🧾 ISSUE BILL</button>
                </form>
            </div>
            @empty
            <div style="text-align:center;padding:40px;color:var(--muted)">Zero active project records found.</div>
            @endforelse
        </div>
    </div>
</div>

<script>
function filterManualInvoices() {
    let input = document.getElementById('invoiceSearchInput').value.toLowerCase();
    let items = document.querySelectorAll('.invoice-client-item');
    items.forEach(item => {
        let text = item.innerText.toLowerCase();
        item.style.display = text.includes(input) ? 'flex' : 'none';
    });
}
</script>

<div class="card" style="padding:0;overflow:hidden">
    <table>
        <thead><tr><th>Invoice #</th><th>Customer</th><th>Total</th><th>Due</th><th>Status</th><th>Due Date</th><th>Actions</th></tr></thead>
        <tbody>
        @forelse($invoices as $inv)
        <tr>
            <td style="color:var(--accent); font-weight:700;">{{ $inv->invoice_number }}</td>
            <td style="font-weight:500;">{{ $inv->customer?->name }}</td>
            <td style="font-weight:600;">${{ number_format($inv->total,2) }}</td>
            <td style="color:#f85149;font-weight:600;">${{ number_format($inv->due,2) }}</td>
            <td>
                <form method="POST" action="{{ route('admin.invoices.status-update', $inv->id) }}" style="display:inline;">
                    @csrf @method('PUT')
                    <select name="status" onchange="this.form.submit()" class="form-control-minimal" style="width:130px; height:30px; font-size:11px; font-weight:800; padding:2px 8px; border:2px solid {{ $inv->status === 'paid' ? 'var(--success)' : 'var(--accent)' }}; border-radius:6px; background:rgba(37,99,235,0.03); color:{{ $inv->status === 'paid' ? 'var(--success)' : 'var(--accent)' }}; cursor:pointer;">
                        <option value="unpaid" {{ $inv->status === 'unpaid' ? 'selected' : '' }}>⏳ UNPAID</option>
                        <option value="partial" {{ $inv->status === 'partial' ? 'selected' : '' }}>🔶 PARTIAL</option>
                        <option value="paid" {{ $inv->status === 'paid' ? 'selected' : '' }}>✓ PAID</option>
                    </select>
                </form>
            </td>
            <td style="color:var(--muted)">{{ $inv->due_date?->format('M d, Y') }}</td>
            <td style="white-space:nowrap">
                <div style="display:flex; gap:6px; align-items:center;">
                    <a href="{{ route('admin.invoices.show',$inv->id) }}" class="btn btn-sm" title="View Detail" style="background:#eee; color:#666; width:28px; height:28px; padding:0; display:flex; align-items:center; justify-content:center; border-radius:6px; font-size:12px;">👁</a>
                    <a href="mailto:{{ $inv->customer?->email }}?subject=Invoice #{{ $inv->invoice_number }}" class="btn btn-sm" title="Email Client" style="background:rgba(37,99,235,0.08); color:var(--accent); width:28px; height:28px; padding:0; display:flex; align-items:center; justify-content:center; border-radius:6px; font-size:12px;">✉</a>
                    <a href="{{ route('admin.invoices.print',$inv->id) }}" target="_blank" class="btn btn-sm" title="Print PDF" style="background:var(--surface2); width:28px; height:28px; padding:0; display:flex; align-items:center; justify-content:center; border-radius:6px; font-size:12px;">📄</a>
                    
                    <button onclick="openEditInvModal({{ $inv->id }}, {{ $inv->total }}, {{ $inv->due }}, '{{ $inv->status }}', '{{ $inv->due_date?->format('Y-m-d') }}')" class="btn btn-sm" title="Adjust Ledger" style="background:var(--surface2); width:28px; height:28px; padding:0; display:flex; align-items:center; justify-content:center; border-radius:6px; font-size:12px;">✎</button>
                    
                    <form method="POST" action="{{ route('admin.invoices.destroy',$inv->id) }}" style="display:inline" onsubmit="return confirm('Archive record?')">
                        @csrf @method('DELETE')<button class="btn btn-sm btn-danger" type="submit" title="Delete" style="width:28px; height:28px; padding:0; display:flex; align-items:center; justify-content:center; border-radius:6px; font-size:10px;">✕</button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;color:var(--muted);padding:30px">No invoices yet</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div id="editInvModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:2000;align-items:center;justify-content:center">
    <div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:28px;width:400px">
        <h3 style="margin-bottom:20px">Edit Invoice</h3>
        <form id="editInvForm" method="POST">@csrf @method('PUT')
            <div style="display:grid;gap:12px">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                    <div><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">Total Amount</label><input type="number" step="0.01" class="form-control" name="total" id="ei_total" required></div>
                    <div><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">Due Amount</label><input type="number" step="0.01" class="form-control" name="due" id="ei_due" required></div>
                </div>
                <div><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">Due Date</label><input type="date" class="form-control" name="due_date" id="ei_due_date" required></div>
                <div><label style="font-size:12px;color:var(--muted);display:block;margin-bottom:4px">Status</label>
                    <select class="form-control" name="status" id="ei_status"><option value="unpaid">Unpaid</option><option value="partial">Partial</option><option value="paid">Paid</option></select>
                </div>
            </div>
            <div style="display:flex;gap:10px;margin-top:24px">
                <button class="btn btn-primary" type="submit">Update Invoice</button>
                <button type="button" onclick="document.getElementById('editInvModal').style.display='none'" class="btn" style="background:var(--surface2);color:var(--muted)">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditInvModal(id, total, due, status, date) {
        document.getElementById('editInvModal').style.display = 'flex';
        document.getElementById('editInvForm').action = '/admin/invoices/' + id;
        document.getElementById('ei_total').value = total;
        document.getElementById('ei_due').value = due;
        document.getElementById('ei_status').value = status;
        document.getElementById('ei_due_date').value = date;
    }
</script>
@endsection
