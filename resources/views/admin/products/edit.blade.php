@extends('layouts.admin')

@section('content')
<div style="max-width: 900px; margin: 0 auto; padding: 40px;">
    
    <div style="margin-bottom: 40px;">
        <a href="{{ route('admin.products.index') }}" style="color: var(--muted); text-decoration: none; font-weight: 700; display: flex; align-items: center; gap: 8px; font-size: 14px; margin-bottom: 20px;">
            ← Back to Catalog
        </a>
        <h1 style="font-size: 32px; font-weight: 900; color: var(--text); letter-spacing: -1px; margin: 0;">Modify Product Details</h1>
        <p style="color: var(--muted); margin-top: 8px;">Updating configuration for <span style="color: var(--gold); font-weight: 800;">{{ $product->name }}</span>.</p>
    </div>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" id="product-form">
        @csrf
        @method('PUT')
        
        <div style="display: grid; gap: 30px;">
            
            {{-- Basic Information --}}
            <div style="background: #fff; border: 1px solid var(--border); border-radius: 24px; padding: 32px; box-shadow: 0 10px 30px rgba(0,0,0,0.02);">
                <h3 style="font-size: 16px; font-weight: 900; color: var(--text); margin-bottom: 24px; text-transform: uppercase; letter-spacing: 1px;">Basic Details</h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label class="form-label-premium">Product Name</label>
                        <input type="text" name="name" value="{{ $product->name }}" required class="form-control-premium">
                    </div>
                    <div>
                        <label class="form-label-premium">Category</label>
                        <input type="text" name="category" value="{{ $product->category }}" class="form-control-premium">
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label class="form-label-premium">Description</label>
                    <textarea name="description" rows="3" class="form-control-premium" style="resize: vertical;">{{ $product->description }}</textarea>
                </div>
            </div>

            {{-- Technical & Pricing --}}
            <div style="background: #fff; border: 1px solid var(--border); border-radius: 24px; padding: 32px; box-shadow: 0 10px 30px rgba(0,0,0,0.02);">
                <h3 style="font-size: 16px; font-weight: 900; color: var(--text); margin-bottom: 24px; text-transform: uppercase; letter-spacing: 1px;">Pricing Model</h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label class="form-label-premium">Pricing Type</label>
                        <select name="pricing_type" class="form-control-premium">
                            <option value="fixed" {{ $product->pricing_type == 'fixed' ? 'selected' : '' }}>Fixed Price</option>
                            <option value="area" {{ $product->pricing_type == 'area' ? 'selected' : '' }}>Area Based (Sqft/Sqm)</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label-premium">Base Unit Price ($)</label>
                        <input type="number" step="0.01" name="unit_price" value="{{ $product->unit_price }}" required class="form-control-premium">
                    </div>
                </div>
            </div>

            {{-- Dynamic Attributes --}}
            <div style="background: #fff; border: 1px solid var(--border); border-radius: 24px; padding: 32px; box-shadow: 0 10px 30px rgba(0,0,0,0.02);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                    <h3 style="font-size: 16px; font-weight: 900; color: var(--text); margin: 0; text-transform: uppercase; letter-spacing: 1px;">Configuration Options</h3>
                    <div style="display: flex; gap: 6px; flex-wrap: wrap; max-width: 60%;">
                        @foreach($masterAttributes as $ma)
                            <button type="button" onclick="addSpecificAttribute('{{ addslashes($ma->label) }}', '{{ addslashes($ma->default_values) }}', {{ $ma->default_price }})" class="btn-mini">
                                {{ $ma->label }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div id="attributes-container" style="display: grid; gap: 12px; margin-bottom: 20px;">
                    {{-- Rows will be injected here --}}
                </div>

                <button type="button" onclick="addAttributeRow()" style="width: 100%; padding: 16px; border: 2px dashed var(--border); border-radius: 16px; background: #fafafa; color: var(--muted); font-weight: 800; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.borderColor='var(--gold)'; this.style.color='var(--gold)';" onmouseout="this.style.borderColor='var(--border)'; this.style.color='var(--muted)';" >
                    + Add Custom Technical Attribute
                </button>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 16px; margin-top: 20px; padding-bottom: 60px;">
                <a href="{{ route('admin.products.index') }}" style="padding: 16px 32px; border-radius: 16px; background: #fff; border: 1px solid var(--border); color: var(--muted); font-weight: 700; text-decoration: none; display: flex; align-items: center;">Cancel Changes</a>
                <button type="submit" style="padding: 16px 48px; border-radius: 16px; background: var(--gold); border: none; color: #fff; font-weight: 900; box-shadow: 0 10px 20px -5px rgba(184, 155, 94, 0.4); cursor: pointer;">Update Product Registry ➔</button>
            </div>

        </div>
    </form>
</div>

<style>
    .form-label-premium { font-size: 11px; font-weight: 900; color: var(--muted); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 10px; display: block; }
    .form-control-premium { width: 100%; padding: 14px 20px; border-radius: 14px; border: 2px solid #f1f5f9; font-weight: 600; font-family: inherit; outline: none; transition: all 0.2s; box-sizing: border-box; }
    .form-control-premium:focus { border-color: var(--gold); box-shadow: 0 0 0 4px rgba(184, 155, 94, 0.1); }
    
    .attr-row { display: grid; grid-template-columns: 1.2fr 1.5fr 0.8fr auto; gap: 12px; align-items: start; background: #fcfcfc; padding: 16px; border-radius: 16px; border: 1px solid var(--border); animation: slideIn 0.3s ease-out; }
    @keyframes slideIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    .btn-mini { font-size: 10px; font-weight: 800; background: #fff; color: var(--muted); border: 1.5px solid var(--border); padding: 6px 14px; border-radius: 20px; cursor: pointer; transition: all .2s; text-transform: uppercase; letter-spacing: 0.5px; }
    .btn-mini:hover { background: var(--gold); color: #fff; border-color: var(--gold); transform: translateY(-1px); box-shadow: 0 4px 6px -1px rgba(184, 155, 94, 0.2); }
</style>

<script>
    function addAttributeRow(label = '', values = '', price = 0) {
        const container = document.getElementById('attributes-container');
        const rowId = 'attr_' + Date.now() + Math.random().toString(36).substr(2, 5);
        
        const row = document.createElement('div');
        row.className = 'attr-row';
        row.id = rowId;
        
        // Use a consistent name or use JS to sync when key changes
        // For industrial stability, we'll keep the Key input and use a listener to update the sub-input names
        row.innerHTML = `
            <div>
                <label style="font-size: 9px; font-weight: 900; color: #94a3b8; display: block; margin-bottom: 4px; text-transform: uppercase;">Label</label>
                <input type="text" class="form-control-premium attr-key-input" value="${label}" required placeholder="Key" style="padding: 10px 14px; font-size: 13px;" oninput="syncAttrNames(this)">
            </div>
            <div>
                <label style="font-size: 9px; font-weight: 900; color: #94a3b8; display: block; margin-bottom: 4px; text-transform: uppercase;">Options (Comma Seperated)</label>
                <input type="text" name="attributes[${label}][values]" value="${values}" required placeholder="Value(s)" class="form-control-premium attr-val-input" style="padding: 10px 14px; font-size: 13px;">
            </div>
            <div>
                <label style="font-size: 9px; font-weight: 900; color: #94a3b8; display: block; margin-bottom: 4px; text-transform: uppercase;">Surcharge ($)</label>
                <input type="number" step="0.01" name="attributes[${label}][price]" value="${price}" placeholder="0.00" class="form-control-premium attr-price-input" style="padding: 10px 14px; font-size: 13px;">
            </div>
            <button type="button" onclick="removeAttributeRow('${rowId}')" style="background: #fff; border: 1.5px solid #fee2e2; color: #ef4444; width: 36px; height: 36px; border-radius: 12px; cursor: pointer; align-self: flex-end; display: flex; align-items: center; justify-content: center; font-size: 20px; transition: 0.2s;" onmouseover="this.style.background='#ef4444'; this.style.color='#fff';">×</button>
        `;
        container.appendChild(row);
    }

    function syncAttrNames(input) {
        const row = input.closest('.attr-row');
        const key = input.value;
        row.querySelector('.attr-val-input').name = `attributes[${key}][values]`;
        row.querySelector('.attr-price-input').name = `attributes[${key}][price]`;
    }

    function addSpecificAttribute(label, defaultValue, defaultPrice = 0) {
        addAttributeRow(label, defaultValue, defaultPrice);
    }

    function removeAttributeRow(id) {
        document.getElementById(id).remove();
    }

    // Populate existing attributes
    window.addEventListener('DOMContentLoaded', (event) => {
        let attributes = {!! json_encode($product->attributes) !!};
        if (attributes && typeof attributes === 'object') {
            Object.entries(attributes).forEach(([key, data]) => {
                // Defensive: If it's a double-encoded string
                if (typeof data === 'string' && (data.startsWith('{') || data.startsWith('['))) {
                    try { data = JSON.parse(data); } catch(e) {}
                }

                if (typeof data === 'string') {
                    addAttributeRow(key, data, 0);
                } else {
                    addAttributeRow(key, data.values || '', data.price || 0);
                }
            });
        }
    });

    // No manual submit listener needed with nested array inputs
</script>
@endsection
