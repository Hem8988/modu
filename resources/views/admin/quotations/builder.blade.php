@extends('layouts.admin')
@section('title', 'Project Estimator — ' . $lead->name)
@section('content')

<div style="max-width: 100%; margin: 0 auto;">
    {{-- High-End Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 pb-4" style="border-bottom: 1.5px solid #e2e8f0; gap: 20px;">
        <div>
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                <span style="background: #2563eb; color: #ffffff; padding: 4px 12px; border-radius: 20px; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px;">Drafting Proposal</span>
                <span style="color: #64748b; font-size: 13px; font-weight: 600;">Client Snapshot</span>
            </div>
            <h1 style="font-size: 32px; font-weight: 950; color: #0f172a; letter-spacing: -1px; line-height: 1; margin-bottom: 12px;">{{ $lead->name }}</h1>
            <div style="display: flex; flex-wrap: wrap; gap: 16px; margin-top: 12px; font-size: 14px; font-weight: 600; color: #475569;">
                <span style="display: flex; align-items: center; gap: 6px;"><i class="fas fa-phone-alt" style="color:#2563eb;"></i> {{ $lead->phone }}</span>
                <span style="display: flex; align-items: center; gap: 6px;"><i class="fas fa-map-marker-alt" style="color:#2563eb;"></i> {{ $lead->city ?: 'Project Site' }}</span>
                <span style="display: flex; align-items: center; gap: 6px;"><i class="far fa-calendar-alt" style="color:#2563eb;"></i> {{ date('M d, Y') }}</span>
            </div>
        </div>
        
        <div style="text-align: right; background: #fff; padding: 16px 24px; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); min-width: 200px;">
            <div style="font-size: 10px; font-weight: 800; color: #64748b; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 6px;">Official Proposal ID</div>
            <div style="font-size: 22px; font-weight: 900; color: #2563eb; letter-spacing: -0.5px;">#{{ $quoteNumber }}</div>
        </div>
    </div>

    <form action="{{ route('admin.quotations.save', $lead->id) }}" method="POST" id="quote-form">
        @csrf
        <input type="hidden" name="quote_id" value="{{ $existingQuote->id ?? '' }}">
        <input type="hidden" name="quote_number" value="{{ $quoteNumber }}">

        <div class="row g-4 align-items-start">
            
            {{-- Primary Workspace --}}
            <div class="col-12 col-xl-8" style="display: flex; flex-direction: column; gap: 24px;">
                <div style="padding: 0; overflow: hidden; border: 1px solid #e2e8f0; border-radius: 20px; background: #fff; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);">
                    <div style="padding: 24px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                        <div>
                            <h3 style="font-size: 18px; font-weight: 900; color: #0f172a; display: flex; align-items: center; gap: 10px; margin: 0;">
                                <span style="background: #eab308; width: 6px; height: 18px; border-radius: 4px;"></span>
                                Project Line Items
                            </h3>
                            <p style="font-size: 13px; color: #64748b; font-weight: 500; margin-top: 4px; margin-bottom: 0;">Define custom dimensions and installation requirements.</p>
                        </div>
                        <button type="button" onclick="addItem()" class="btn btn-primary" style="background: #2563eb; border: none; padding: 10px 24px; border-radius: 10px; font-size: 14px; font-weight: 700; box-shadow: 0 4px 6px rgba(37,99,235,0.2);">
                            <i class="fas fa-plus"></i> Add Custom Unit
                        </button>
                    </div>

                    <div class="table-responsive" style="border:none; margin-bottom:0; width: 100%;">
                        <table id="items-table" style="width: 100%; border-collapse: separate; border-spacing: 0;">
                            <thead>
                                <tr style="background: var(--surface2);">
                                    <th style="padding: 16px 24px; text-align: left; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; border-bottom: 2px solid var(--border); color: var(--muted);">Specifications</th>
                                    <th style="padding: 16px 12px; text-align: center; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; border-bottom: 2px solid var(--border); color: var(--muted);">Qty</th>
                                    <th style="padding: 16px 12px; text-align: center; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; border-bottom: 2px solid var(--border); color: var(--muted);">Unit Rate</th>
                                    <th style="padding: 16px 24px; text-align: right; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; border-bottom: 2px solid var(--border); color: var(--muted);">Line Total</th>
                                    <th style="width: 60px; border-bottom: 2px solid var(--border);"></th>
                                </tr>
                            </thead>
                            <tbody id="items-body">
                                @forelse($items as $index => $item)
                                    @include('admin.quotations.partials.builder-row', ['index' => $index, 'item' => $item])
                                @empty
                                    {{-- Empty indicator handled by JS --}}
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div id="no-items" style="padding: 80px 40px; text-align: center; display: {{ $items->count() ? 'none' : 'block' }};">
                        <div style="font-size: 56px; margin-bottom: 20px; filter: grayscale(1); opacity: 0.3;">📋</div>
                        <h4 style="font-size: 20px; font-weight: 900; color: var(--text); letter-spacing: -0.5px;">Workspace Empty</h4>
                        <p style="color: var(--muted); font-size: 14px; font-weight: 600; max-width: 400px; margin: 8px auto 24px;">Start by adding your standard products from the catalog or creating a custom bespoke item.</p>
                        <button type="button" onclick="addItem()" class="btn btn-primary" style="padding: 12px 32px; border-radius: 12px; font-size: 14px;">
                            + Add Your First Item
                        </button>
                    </div>
                </div>
            </div>

            {{-- Sidebar Tools --}}
            <div class="col-12 col-xl-4 sidebar-tools-container" style="display: flex; flex-direction: column; gap: 24px;">
                <style>
                    @media (min-width: 1200px) {
                        .sidebar-tools-container { position: sticky; top: 20px; }
                    }
                </style>
                {{-- Quick Catalog --}}
                <div style="padding: 24px; border: 1px solid #e2e8f0; border-radius: 20px; background: #fff; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);">
                    <h3 style="font-size: 13px; font-weight: 900; margin-bottom: 20px; color: #0f172a; text-transform: uppercase; letter-spacing: 1px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-box-open" style="color: #2563eb;"></i> Product Catalog
                    </h3>
                    <div style="display: grid; gap: 10px;">
                        @foreach($products as $p)
                            <button type="button" onclick="addProduct('{{ $p->name }}', {{ $p->unit_price }}, {{ $p->id ?? 'null' }})" class="btn" style="width: 100%; justify-content: space-between; background: var(--surface2); border: 1px solid var(--border); border-radius: 12px; padding: 14px; transition: all .2s; font-size: 13.5px;" onmouseover="this.style.borderColor='var(--accent)'; this.style.transform='translateX(4px)'; this.style.background='#fff';" onmouseout="this.style.borderColor='var(--border)'; this.style.transform='translateX(0)'; this.style.background='var(--surface2)';">
                                <span style="font-weight: 700; color: var(--text);">{{ $p->name }}</span>
                                <span style="color: var(--accent); font-weight: 900;">${{ number_format($p->unit_price, 0) }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Financial Summary --}}
                <div style="background: #0f172a; border: none; padding: 32px; border-radius: 24px; color: #ffffff; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); position: relative; overflow: hidden;">
                    <div style="position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; background: #2563eb; filter: blur(100px); opacity: 0.4;"></div>
                    
                    <h3 style="font-size: 11px; font-weight: 900; margin-bottom: 32px; color: rgba(255,255,255,0.5); letter-spacing: 1.5px; text-transform: uppercase;">Financial Summary</h3>
                    
                    <div style="display: grid; gap: 20px; font-size: 15px; font-weight: 600;">
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 15px;">
                            <span style="opacity: 0.6;">Item Subtotal</span>
                            <span id="subtotal-display" style="font-family: monospace;">$0.00</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 15px;">
                            <span style="opacity: 0.6;">Tax (8.875%)</span>
                            <span id="tax-display" style="font-family: monospace;">$0.00</span>
                        </div>
                        <div style="margin-top: 15px;">
                            <div style="font-size: 10px; font-weight: 900; opacity: 0.4; margin-bottom: 6px; letter-spacing: 1px;">TOTAL PROPOSAL VALUE</div>
                            <div id="total-display" style="font-size: 42px; font-weight: 950; color: #fff; letter-spacing: -2px; line-height: 1;">$0.00</div>
                            <input type="hidden" name="total_amount" id="total-input" value="0">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 40px; padding: 18px; font-size: 15px; font-weight: 900; background: var(--accent); color: white; border-radius: 16px; box-shadow: 0 15px 30px rgba(37,99,235,0.4); text-transform: uppercase; letter-spacing: 1px;">
                        Secure Proposal ➔
                    </button>
                    
                    <p style="text-align: center; font-size: 11px; color: rgba(255,255,255,0.4); margin-top: 20px; font-weight: 600;">Final price subject to field measurement.</p>
                </div>
            </div>
        </div>
    </form>
</div>

<template id="row-template">
    @include('admin.quotations.partials.builder-row', ['index' => 'ID_PLACEHOLDER', 'item' => null])
</template>

<script>
    let rowIndex = {{ $items->count() }};
    const masterCatalog = {!! $products->toJson() !!};
    
    // Normalize attributes for JS usage
    masterCatalog.forEach(p => {
        if (typeof p.attributes === 'string') {
            try { p.attributes = JSON.parse(p.attributes); } catch(e) {}
        }
    });

    function addItem() {
        addRow({ name: '', price: 0, qty: 1, id: null });
    }

    function addProduct(name, price, id) {
        addRow({ name, price, qty: 1, id });
    }

    function addRow(data) {
        const body = document.getElementById('items-body');
        const noItems = document.getElementById('no-items');
        if (noItems) noItems.style.display = 'none';

        const template = document.getElementById('row-template').innerHTML;
        const html = template.replace(/ID_PLACEHOLDER/g, rowIndex);
        
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = '<table><tbody>' + html + '</tbody></table>';
        const row = tempDiv.querySelector('tr');
        
        row.querySelector('.item-name').value = data.name;
        row.querySelector('.item-price').value = data.price;
        row.querySelector('.item-qty').value = data.qty || 1;
        row.querySelector('.item-product-id').value = data.id || '';
        row.querySelector('.item-price').dataset.basePrice = data.price; // Keep original price
        
        if (data.id) {
            const product = masterCatalog.find(p => p.id == data.id);
            if (product) renderTechnicalAttributes(row, product);
        }

        body.appendChild(row);
        rowIndex++;
        calculateTotals();
    }

    function removeRow(btn) {
        btn.closest('tr').remove();
        calculateTotals();
        
        const body = document.getElementById('items-body');
        const noItems = document.getElementById('no-items');
        if (body.children.length === 0 && noItems) {
            noItems.style.display = 'block';
        }
    }

    function calculateTotals() {
        let subtotal = 0;
        document.querySelectorAll('#items-body tr').forEach(row => {
            const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
            
            // Real-time Unit Rate Calculation (Base + Surcharges)
            const basePrice = parseFloat(row.querySelector('.item-price').dataset.basePrice) || 0;
            let surcharges = 0;
            row.querySelectorAll('.attr-select').forEach(select => {
                const selectedOption = select.options[select.selectedIndex];
                surcharges += parseFloat(selectedOption.dataset.price) || 0;
            });
            
            const finalRate = basePrice + surcharges;
            row.querySelector('.item-price').value = finalRate.toFixed(2);
            
            const rowTotal = qty * finalRate;
            
            const totalEl = row.querySelector('.item-row-total');
            if (totalEl) {
                totalEl.innerText = '$' + rowTotal.toLocaleString(undefined, {minimumFractionDigits: 2});
            }
            subtotal += rowTotal;
        });

        const taxRate = 0.08875; // N.Y. Tax 
        const tax = subtotal * taxRate;
        const total = subtotal + tax;

        document.getElementById('subtotal-display').innerText = '$' + subtotal.toLocaleString(undefined, {minimumFractionDigits: 2});
        document.getElementById('tax-display').innerText = '$' + tax.toLocaleString(undefined, {minimumFractionDigits: 2});
        document.getElementById('total-display').innerText = '$' + total.toLocaleString(undefined, {minimumFractionDigits: 2});
        document.getElementById('total-input').value = total.toFixed(2);
    }

    function renderTechnicalAttributes(row, product) {
        const wrapper = row.querySelector('.attributes-wrapper');
        const grid = row.querySelector('.attributes-grid');
        grid.innerHTML = '';
        
        if (!product.attributes || Object.keys(product.attributes).length === 0) {
            wrapper.style.display = 'none';
            return;
        }

        const index = row.querySelector('.item-product-id').name.match(/\d+/)[0];

        Object.entries(product.attributes).forEach(([label, data]) => {
            const container = document.createElement('div');
            
            const labelEl = document.createElement('label');
            labelEl.style.cssText = 'font-size: 9px; font-weight: 800; color: #94a3b8; text-transform: uppercase; display: block; margin-bottom: 4px;';
            labelEl.innerText = label;
            
            const select = document.createElement('select');
            select.name = `items[${index}][options][${label}]`;
            select.className = 'form-control attr-select';
            select.style.cssText = 'height: 32px; font-size: 11px; padding: 4px 8px; border-radius: 8px; background: #fff; border: 1px solid #e2e8f0; font-weight: 700; width: 100%;';
            select.onchange = calculateTotals;

            const valuesStr = typeof data === 'string' ? data : (data.values || '');
            const surcharge = typeof data === 'object' ? (data.price || 0) : 0;
            const values = valuesStr.split(',').map(v => v.trim()).filter(v => v);
            
            values.unshift('Standard'); // Base option
            
            values.forEach((v, i) => {
                const opt = document.createElement('option');
                opt.value = v;
                opt.innerText = v;
                opt.dataset.price = (i > 0) ? surcharge : 0; 
                if (i > 0 && surcharge > 0) opt.innerText += ` (+$${surcharge})`;
                select.appendChild(opt);
            });

            container.appendChild(labelEl);
            container.appendChild(select);
            grid.appendChild(container);
        });

        wrapper.style.display = 'block';
    }

    /**
     * Real-Time Search Logic
     */
    function searchProducts(input) {
        const query = input.value.toLowerCase();
        const resultsContainer = input.closest('.search-container').querySelector('.search-results-dropdown');
        
        if (query.length < 2) {
            resultsContainer.style.display = 'none';
            return;
        }

        const matches = masterCatalog.filter(p => 
            p.name.toLowerCase().includes(query) || 
            (p.category && p.category.toLowerCase().includes(query))
        );

        if (matches.length > 0) {
            let html = '';
            matches.slice(0, 5).forEach(p => {
                html += `
                    <div class="search-result-item" onmousedown="selectProduct(this, ${p.id}, '${p.name.replace(/'/g, "\\'")}', ${p.unit_price})">
                        <div>
                            <span class="search-result-category">${p.category || 'Product'}</span>
                            <span class="search-result-name">${p.name}</span>
                        </div>
                        <span class="search-result-price">$${parseFloat(p.unit_price).toFixed(2)}</span>
                    </div>
                `;
            });
            resultsContainer.innerHTML = html;
            resultsContainer.style.display = 'block';
        } else {
            resultsContainer.style.display = 'none';
        }
    }

    function selectProduct(element, id, name, price) {
        const row = element.closest('tr');
        row.querySelector('.item-name').value = name;
        row.querySelector('.item-price').value = price;
        row.querySelector('.item-price').dataset.basePrice = price; // Sync base price
        row.querySelector('.item-product-id').value = id;
        
        // Load configurations
        const product = masterCatalog.find(p => p.id == id);
        if (product) renderTechnicalAttributes(row, product);

        element.closest('.search-results-dropdown').style.display = 'none';
        calculateTotals();
    }

    function hideSearchResults(input) {
        // Use timeout to allow onmousedown selection to trigger first
        setTimeout(() => {
            const resultsContainer = input.closest('.search-container').querySelector('.search-results-dropdown');
            resultsContainer.style.display = 'none';
        }, 200);
    }

    calculateTotals();
</script>

<style>
    .form-control:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 4px rgba(37,99,235,0.1); }
    th { color: var(--muted) !important; padding: 16px 24px !important; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800; font-size: 10px; }
    td { transition: background 0.2s; }
    tr:hover td { background: rgba(37,99,235,0.01); }
</style>
@endsection
