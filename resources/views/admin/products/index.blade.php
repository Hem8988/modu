@extends('layouts.admin')
@section('title', 'Product Registry')
@section('content')

<div style="max-width: 1200px; margin: 0 auto;">
    {{-- High-End Header --}}
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 32px; padding-bottom: 24px; border-bottom: 1.5px solid var(--border);">
        <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                <span style="background: var(--gold); color: white; padding: 4px 12px; border-radius: 20px; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px;">Master Catalog</span>
                <span style="color: var(--muted); font-size: 13px; font-weight: 600;">Inventory Management</span>
            </div>
            <h1 style="font-size: 32px; font-weight: 950; color: var(--text); letter-spacing: -1px; line-height: 1;">Product Registry</h1>
            <p style="color: var(--muted); font-size: 14px; margin-top: 8px; font-weight: 500;">Manage the items available in your Quotation Builder's Quick-Add sidebar.</p>
        </div>
        
        <button onclick="openAddModal()" class="btn btn-primary" style="padding: 12px 28px; border-radius: 12px; font-weight: 800; font-size: 14px; box-shadow: 0 10px 15px -3px rgba(37,99,235,0.2);">
            ✨ Register New Product
        </button>
    </div>

    {{-- Main Registry Table --}}
    <div class="card" style="padding: 0; overflow: hidden; border: 1.5px solid var(--border); border-radius: 20px; box-shadow: var(--shadow);">
        <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
            <thead>
                <tr style="background: var(--surface2);">
                    <th style="padding: 18px 24px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--muted); border-bottom: 2px solid var(--border);">Essential Details</th>
                    <th style="padding: 18px 24px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--muted); border-bottom: 2px solid var(--border);">Classification</th>
                    <th style="padding: 18px 24px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--muted); border-bottom: 2px solid var(--border);">Pricing Model</th>
                    <th style="padding: 18px 24px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--muted); border-bottom: 2px solid var(--border); text-align: center;">Unit Value</th>
                    <th style="padding: 18px 24px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--muted); border-bottom: 2px solid var(--border); text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $p)
                <tr>
                    <td style="padding: 20px 24px;">
                        <div style="font-weight: 800; color: var(--text); font-size: 15px;">{{ $p->name }}</div>
                        <div style="font-size: 12px; color: var(--muted); margin-top: 2px;">ID: #PROD-{{ str_pad($p->id, 4, '0', STR_PAD_LEFT) }}</div>
                    </td>
                    <td style="padding: 20px 24px;">
                        <span style="background: var(--surface2); color: var(--muted); padding: 4px 12px; border-radius: 6px; font-size: 12px; font-weight: 700;">
                            {{ $p->category ?: 'Standard shade' }}
                        </span>
                    </td>
                    <td style="padding: 20px 24px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="width: 8px; height: 8px; border-radius: 50%; background: {{ $p->pricing_type == 'fixed' ? 'var(--accent)' : 'var(--success)' }}"></div>
                            <span style="font-weight: 700; font-size: 13px; text-transform: capitalize;">{{ $p->pricing_type }}</span>
                        </div>
                    </td>
                    <td style="padding: 20px 24px; text-align: center;">
                        <div style="font-weight: 900; color: var(--success); font-size: 16px;">${{ number_format($p->unit_price, 2) }}</div>
                    </td>
                    <td style="padding: 20px 24px; text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 8px;">
                            <button onclick="openEditModal({{ $p->id }}, '{{ addslashes($p->name) }}', '{{ addslashes($p->category) }}', '{{ $p->pricing_type }}', '{{ $p->unit_price }}', '{{ addslashes($p->description) }}')" class="btn-action" title="Modify Details">✎</button>
                            <form method="POST" action="{{ route('admin.products.destroy', $p->id) }}" style="display:inline" onsubmit="return confirm('Archive this product?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" title="Remove Product">✕</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 80px 40px; text-align: center;">
                        <div style="font-size: 48px; filter: grayscale(1); opacity: 0.3; margin-bottom: 16px;">📦</div>
                        <h4 style="font-size: 18px; font-weight: 900; color: var(--text);">Catalog is Empty</h4>
                        <p style="color: var(--muted); font-size: 14px; margin-top: 4px;">Start adding products to enable your sales team.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modern Glassmorphism Modals --}}
<div id="product-modal-overlay" class="modal-overlay" onclick="closeModal()">
    <div class="modal-content" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 id="modal-title" style="margin: 0; font-size: 18px; font-weight: 900; color: var(--text); display: flex; align-items: center; gap: 10px;">
                📦 Product Configuration
            </h3>
            <button onclick="closeModal()" style="background: none; border: none; font-size: 24px; color: var(--muted); cursor: pointer;">&times;</button>
        </div>
        <form id="product-form" method="POST">
            @csrf
            <div id="method-container"></div>
            <div class="modal-body">
                <div style="display: grid; gap: 20px;">
                    <div>
                        <label class="form-label-premium">Product Identification</label>
                        <input class="form-control" name="name" id="p_name" required placeholder="e.g. Zebra Shade — Premium Blackout">
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <label class="form-label-premium">Classification</label>
                            <input class="form-control" name="category" id="p_category" placeholder="e.g. Motorized">
                        </div>
                        <div>
                            <label class="form-label-premium">Pricing Model</label>
                            <select class="form-control" name="pricing_type" id="p_pricing_type">
                                <option value="unit">Per Unit</option>
                                <option value="sqft">Per Sq Ft</option>
                                <option value="fixed">Fixed Rate</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="form-label-premium">Financial Value (Base Rate $)</label>
                        <div style="position: relative;">
                            <span style="position: absolute; left: 14px; top: 12px; color: var(--muted); font-weight: 800;">$</span>
                            <input class="form-control" name="unit_price" id="p_unit_price" type="number" step="0.01" required style="padding-left: 28px;">
                        </div>
                    </div>
                    <div>
                        <label class="form-label-premium">Internal Description & Notes</label>
                        <textarea class="form-control" name="description" id="p_description" rows="3" placeholder="Specs, material info, or manufacturing details..." style="resize: none;"></textarea>
                    </div>
                </div>
            </div>
            <div style="padding: 20px 28px; background: #fafafa; border-top: 1px solid var(--border); text-align: right; display: flex; justify-content: flex-end; gap: 12px;">
                <button type="button" onclick="closeModal()" class="btn" style="background: var(--surface2); color: var(--muted); font-weight: 700;">Cancel</button>
                <button type="submit" id="submit-btn" class="btn btn-primary" style="padding-left: 32px; padding-right: 32px;">Confirm Details</button>
            </div>
        </form>
    </div>
</div>

<style>
    .btn-action { width: 32px; height: 32px; border-radius: 8px; border: 1.5px solid var(--border); background: #fff; cursor: pointer; color: var(--muted); display: flex; align-items: center; justify-content: center; transition: all .2s; }
    .btn-action:hover { border-color: var(--accent); color: var(--accent); transform: scale(1.1); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .btn-delete:hover { border-color: var(--danger); color: var(--danger); }

    .modal-overlay { position: fixed; inset: 0; background: rgba(15,23,42,0.6); backdrop-filter: blur(8px); display: none; align-items: center; justify-content: center; z-index: 9999; padding: 20px; }
    .modal-content { background: #fff; width: 100%; max-width: 500px; border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); border: 1px solid rgba(255,255,255,0.2); animation: modalIn .3s cubic-bezier(0.34, 1.56, 0.64, 1); overflow: hidden; }
    @keyframes modalIn { from { opacity: 0; transform: scale(0.95) translateY(10px); } to { opacity: 1; transform: scale(1) translateY(0); } }
    .modal-header { padding: 24px 28px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; }
    .modal-body { padding: 28px; }
    .form-label-premium { font-size: 11px; font-weight: 800; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 8px; }
    
    td { transition: background .2s; }
    tr:hover td { background: rgba(37,99,235,0.01); }
</style>

<script>
    function openAddModal() {
        resetForm();
        document.getElementById('modal-title').innerText = '✨ Register New Product';
        document.getElementById('product-form').action = '{{ route('admin.products.store') }}';
        document.getElementById('method-container').innerHTML = '';
        document.getElementById('submit-btn').innerText = 'Add to Catalog';
        document.getElementById('product-modal-overlay').style.display = 'flex';
    }

    function openEditModal(id, name, cat, type, price, desc) {
        resetForm();
        document.getElementById('modal-title').innerText = '🔧 Modify Product Details';
        document.getElementById('product-form').action = '/admin/products/' + id;
        document.getElementById('method-container').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('p_name').value = name;
        document.getElementById('p_category').value = cat || '';
        document.getElementById('p_pricing_type').value = type;
        document.getElementById('p_unit_price').value = price;
        document.getElementById('p_description').value = desc || '';
        document.getElementById('submit-btn').innerText = 'Update Product';
        document.getElementById('product-modal-overlay').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('product-modal-overlay').style.display = 'none';
    }

    function resetForm() {
        document.getElementById('product-form').reset();
    }
</script>

@endsection
