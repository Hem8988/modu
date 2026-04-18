@extends('layouts.admin')
@section('title', 'Project Estimator — ' . $lead->name)
@section('content')

<div class="builder-container" style="max-width: 1400px; margin: 0 auto; font-family: 'Outfit', sans-serif; padding: 20px;">
    {{-- Premium Glassmorphic Header --}}
    <div class="glass-header" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(20px); border-radius: 24px; border: 1px solid rgba(255, 255, 255, 0.4); padding: 32px; box-shadow: 0 20px 40px rgba(0,0,0,0.05); margin-bottom: 32px;">
        <div class="row g-4 align-items-center">
            <div class="col-12 col-md-9">
                <label style="font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1.5px; display: block; margin-bottom: 8px;">Billing Party</label>
                <div style="display: flex; align-items: center; gap: 12px; background: #fff; padding: 12px 20px; border-radius: 16px; border: 1px solid #e2e8f0;">
                    <div style="width: 40px; height: 40px; background: #2563eb; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #fff;">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div>
                        <div style="font-size: 16px; font-weight: 800; color: #0f172a;">{{ $lead->name }}</div>
                        <div style="font-size: 12px; font-weight: 600; color: #64748b;">{{ $lead->phone }}</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3" style="text-align: right;">
                <div style="font-size: 10px; font-weight: 800; color: #64748b; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 4px;">Quote Reference</div>
                <div style="font-size: 18px; font-weight: 900; color: #2563eb;">#{{ $quoteNumber }}</div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.quotations.save', $lead->id) }}" method="POST" id="quote-form">
        @csrf
        <input type="hidden" name="quote_id" value="{{ $existingQuote?->id ?? '' }}">
        <input type="hidden" name="quote_number" value="{{ $quoteNumber }}">

        <div class="row g-4">
            {{-- Main Workspace --}}
            <div class="col-12">
                <div style="background: #fff; border-radius: 24px; border: 1px solid #e2e8f0; overflow: visible !important; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);">
                    <div style="padding: 24px 32px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                        <h3 style="font-size: 18px; font-weight: 950; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 12px;">
                            <span style="background: #eab308; width: 6px; height: 20px; border-radius: 3px;"></span>
                            Product Specifications
                        </h3>
                        <div style="display: flex; gap: 12px;">
                            <button type="button" onclick="addItem()" class="btn btn-primary header-main-btn" style="background: #2563eb; border: none; padding: 12px 24px; border-radius: 12px; font-size: 14px; font-weight: 800; box-shadow: 0 8px 16px rgba(37,99,235,0.2);">
                                <i class="fas fa-plus"></i> Add Line Item
                            </button>
                        </div>
                    </div>

                    <div style="overflow: visible !important;">
                        <table id="items-table" style="width: 100%; border-collapse: separate; border-spacing: 0; overflow: visible !important;">
                            <thead>
                                <tr style="background: #f1f5f9;">
                                    <th style="padding: 16px 32px; text-align: left; width: 40%; font-size: 10px; font-weight: 900; color: #64748b; text-transform: uppercase; letter-spacing: 1px;">Description & Configuration</th>
                                    <th style="padding: 16px 12px; text-align: center; width: 10%; font-size: 10px; font-weight: 900; color: #64748b; text-transform: uppercase; letter-spacing: 1px;">Qty</th>
                                    <th style="padding: 16px 12px; text-align: center; width: 15%; font-size: 10px; font-weight: 900; color: #64748b; text-transform: uppercase; letter-spacing: 1px;">Unit Rate</th>
                                    <th style="padding: 16px 12px; text-align: center; width: 10%; font-size: 10px; font-weight: 900; color: #64748b; text-transform: uppercase; letter-spacing: 1px;">VAT %</th>
                                    <th style="padding: 16px 12px; text-align: center; width: 10%; font-size: 10px; font-weight: 900; color: #64748b; text-transform: uppercase; letter-spacing: 1px;">VAT Amt</th>
                                    <th style="padding: 16px 32px; text-align: right; width: 15%; font-size: 10px; font-weight: 900; color: #64748b; text-transform: uppercase; letter-spacing: 1px;">Line Total</th>
                                    <th style="width: 60px;"></th>
                                </tr>
                            </thead>
                            <tbody id="items-body">
                                @forelse($items as $index => $item)
                                    @include('admin.quotations.partials.builder-row', ['index' => $index, 'item' => $item])
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div id="no-items" style="padding: 100px 40px; text-align: center; display: {{ $items->count() ? 'none' : 'block' }}; background: #ffffff;">
                        <div style="width: 80px; height: 80px; background: #f1f5f9; border-radius: 24px; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; font-size: 32px; color: #94a3b8;">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <h4 style="font-size: 18px; font-weight: 800; color: #0f172a; margin-bottom: 8px;">Workspace is Empty</h4>
                        <p style="font-size: 14px; color: #64748b; margin-bottom: 32px; font-weight: 500;">Start by adding products from the catalog or creating a custom item.</p>
                        <button type="button" onclick="addItem()" class="btn btn-primary" style="background: #2563eb; border: none; padding: 12px 32px; border-radius: 12px; font-weight: 800; font-size: 14px;">
                            + Add First Line Item
                        </button>
                    </div>
                </div>

                {{-- Financial Summary Section --}}
                <div style="margin-top: 24px; background: #ffffff; border-radius: 20px; border: 1px solid #e2e8f0; box-shadow: 0 4px 15px rgba(0,0,0,0.02); overflow: visible !important;">
                    <div style="padding: 24px 32px; color: #0f172a;">
                        <h3 style="font-size: 10px; font-weight: 900; color: #94a3b8; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 20px;">Financial Summary</h3>
                        
                        <div class="row g-3 align-items-center financial-summary-row">
                            <div class="col-md-3">
                                <div style="background: #f8fafc; padding: 16px 20px; border-radius: 16px; border: 1px solid #f1f5f9;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                        <span style="font-size: 13px; color: #64748b; font-weight: 600;">Subtotal</span>
                                        <span id="summary-subtotal" style="font-size: 15px; font-weight: 800; font-family: monospace;">$0.00</span>
                                        <input type="hidden" name="subtotal" id="input-subtotal" value="0">
                                    </div>
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <span style="font-size: 13px; color: #64748b; font-weight: 600;">VAT Total</span>
                                        <span id="summary-vat" style="font-size: 15px; font-weight: 800; font-family: monospace;">$0.00</span>
                                        <input type="hidden" name="vat_amount" id="input-vat" value="0">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div style="background: #f8fafc; padding: 16px 20px; border-radius: 16px; border: 1px solid #f1f5f9;">
                                    <label style="font-size: 10px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 8px;">Discount</label>
                                    <input type="number" name="discount" id="input-discount" value="{{ $existingQuote?->discount ?? 0 }}" oninput="calculateTotals()" step="0.01" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; color: #0f172a; padding: 8px 12px; font-weight: 700; width: 100%; font-size: 14px;">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div style="background: #f8fafc; padding: 16px 24px; border-radius: 16px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; justify-content: center;">
                                    <div style="font-size: 9px; font-weight: 950; color: #2563eb; letter-spacing: 1.5px; margin-bottom: 2px; text-transform: uppercase;">Total Payable</div>
                                    <div id="summary-total" style="font-size: 28px; font-weight: 950; letter-spacing: -1px; font-family: 'Outfit'; color: #0f172a;">$0.00</div>
                                    <input type="hidden" name="total_amount" id="input-total" value="0">
                                </div>
                            </div>

                            <div class="col-md-3 text-end">
                                <button type="submit" class="btn btn-primary" style="padding: 16px 32px; border-radius: 14px; background: #2563eb; border: none; font-size: 14px; font-weight: 900; letter-spacing: 0.5px; box-shadow: 0 8px 16px rgba(37,99,235,0.25); width: 100%;">
                                    SAVE QUOTATION ➔
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Narration & Service Agreement --}}
                <div class="row g-4 mt-1">
                    <div class="col-12 col-xl-4">
                        <div style="background: #fff; padding: 20px; border-radius: 20px; border: 1px solid #e2e8f0; height: 100%;">
                            <label style="font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px; display: block; margin-bottom: 8px;">Narration / Scope of Work</label>
                            <textarea name="narration" rows="5" class="form-control" placeholder="Describe the project scope..." style="border-radius: 12px; border: 1px solid #e2e8f0; font-size: 13px; padding: 12px; height: calc(100% - 30px);">{{ $existingQuote?->narration ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="col-12 col-xl-8">
                        <div style="background: #fff; padding: 20px; border-radius: 20px; border: 1px solid #e2e8f0;">
                            <label style="font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px; display: block; margin-bottom: 8px;">Project-Specific Service Agreement</label>
                            <textarea name="terms_conditions" rows="8" class="form-control" placeholder="Legal terms for this project..." style="border-radius: 12px; border: 1px solid #e2e8f0; font-size: 13px; padding: 12px; font-weight: 600;">{{ $existingQuote?->terms_conditions ?? 'ModuShade – Service Agreement

This Agreement is made between ModuShade ("Company") and the undersigned client ("Customer").

---

1. Scope of Work

The Company agrees to supply and install blinds/shades as detailed in the approved quotation sent to the Customer.

---

2. Measurements & Responsibility

All measurements are based on on-site evaluation.
Customer is responsible for approving final specifications (fabric, color, operation, placement).

---

3. Payment Terms

- Deposit: ___% required to start production
- Balance: Due upon completion of installation

No order will be placed without deposit.

---

4. Production & Installation Timeline

Estimated lead time: 14–21 business days from deposit date.
Installation will be scheduled once products are ready.

---

5. Custom Order Policy

All products are custom-made.
👉 No cancellations or refunds after production has started.

---

6. Installation Conditions

Customer agrees to provide clear and safe access to installation areas.
Any delays due to site conditions may result in rescheduling.

---

7. Warranty

Warranty is limited to manufacturer defects only.
Does not cover misuse, damage, or improper handling.

---

8. Final Approval

By signing below, the Customer confirms:

- Approval of quotation
- Approval of product specifications
- Agreement to all terms above' }}</textarea>
                            <div style="font-size: 11px; color: #64748b; margin-top: 8px; font-weight: 500;">
                                <i class="fas fa-info-circle me-1"></i> These terms will be displayed specifically for this client on the digital signing portal.
                            </div>
                        </div>
                    </div>
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
    let currentSearchResults = [];
    let searchTimeout = null;

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
            // This case handles initial rendering of existing items
            // We'll rely on the server-side attributes for now or fetch if needed
            // But usually existing items come with their configurations already
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
        let totalVat = 0;

        document.querySelectorAll('#items-body tr').forEach(row => {
            const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
            const priceInput = row.querySelector('.item-price');
            let basePrice = parseFloat(priceInput.dataset.basePrice) || 0;
            
            // Calculate surcharges from technical attributes
            let surcharges = 0;
            row.querySelectorAll('.attr-select').forEach(select => {
                const selectedOpt = select.options[select.selectedIndex];
                if (selectedOpt && selectedOpt.dataset.price) {
                    surcharges += parseFloat(selectedOpt.dataset.price) || 0;
                }
            });

            // Support Manual Price Overrides
            // If the user is manually editing this field, we update the basePrice internally
            if (document.activeElement === priceInput) {
                const manualVal = parseFloat(priceInput.value) || 0;
                priceInput.dataset.basePrice = manualVal - surcharges;
            } else {
                // Otherwise, push the calculated price (Base + Surcharges) to the UI
                if (basePrice > 0 || surcharges > 0) {
                    const totalUnitPrice = basePrice + surcharges;
                    priceInput.value = totalUnitPrice.toFixed(2);
                }
            }

            const price = parseFloat(priceInput.value) || 0;
            const vatPercent = parseFloat(row.querySelector('.item-vat-percent').value) || 0;
            
            // Raw Line Total
            const lineSubtotal = qty * price;
            
            // VAT Calculation
            const lineVat = lineSubtotal * (vatPercent / 100);
            const lineTotal = lineSubtotal + lineVat;
            
            // Update UI for Line
            const vatAmtEl = row.querySelector('.item-vat-amount');
            if (vatAmtEl) vatAmtEl.value = lineVat.toFixed(2);
            
            const totalEl = row.querySelector('.item-row-total');
            if (totalEl) totalEl.innerText = '$' + lineTotal.toLocaleString(undefined, {minimumFractionDigits: 2});
            
            subtotal += lineSubtotal;
            totalVat += lineVat;
        });

        const discount = parseFloat(document.getElementById('input-discount').value) || 0;
        
        const grandTotal = subtotal + totalVat - discount;

        // Update Summary Displays
        document.getElementById('summary-subtotal').innerText = '$' + subtotal.toLocaleString(undefined, {minimumFractionDigits: 2});
        document.getElementById('summary-vat').innerText = '$' + totalVat.toLocaleString(undefined, {minimumFractionDigits: 2});
        document.getElementById('summary-total').innerText = '$' + grandTotal.toLocaleString(undefined, {minimumFractionDigits: 2});

        // Update Hidden Inputs
        document.getElementById('input-subtotal').value = subtotal.toFixed(2);
        document.getElementById('input-vat').value = totalVat.toFixed(2);
        document.getElementById('input-total').value = grandTotal.toFixed(2);
    }
    function renderTechnicalAttributes(row, product) {
        if (!row || !product) return;
        
        const wrapper = row.querySelector('.attributes-wrapper');
        const grid = row.querySelector('.attributes-grid');
        if (!wrapper || !grid) return;
        
        grid.innerHTML = '';
        const parsedAttributes = getParsedAttributes(product);
        
        if (Object.keys(parsedAttributes).length === 0) {
            wrapper.style.display = 'none';
            return;
        }

        // Detect index from name attribute safely
        const productIdInput = row.querySelector('.item-product-id');
        if (!productIdInput) return;
        const indexMatch = productIdInput.name.match(/\[(\d+)\]/);
        const index = indexMatch ? indexMatch[1] : 0;

        Object.entries(parsedAttributes).forEach(([label, data]) => {
            const container = document.createElement('div');
            
            const labelEl = document.createElement('label');
            labelEl.style.cssText = 'font-size: 9px; font-weight: 800; color: #94a3b8; text-transform: uppercase; display: block; margin-bottom: 4px;';
            labelEl.innerText = label;
            
            const select = document.createElement('select');
            select.name = `items[${index}][options][${label}]`;
            select.className = 'form-control attr-select';
            select.style.cssText = 'height: 40px; font-size: 12px; padding: 0 12px; border-radius: 10px; background: #ffffff; border: 1px solid #cbd5e1; font-weight: 700; width: 100%; transition: all 0.2s;';
            select.onfocus = () => { select.style.borderColor = '#2563eb'; select.style.boxShadow = '0 0 0 4px rgba(37,99,235,0.1)'; };
            select.onblur = () => { select.style.borderColor = '#cbd5e1'; select.style.boxShadow = 'none'; };
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

    // Helper to normalize attributes
    function getParsedAttributes(product) {
        if (!product.attributes) return {};
        if (typeof product.attributes === 'object') return product.attributes;
        try { return JSON.parse(product.attributes); } catch(e) { return {}; }
    }

    /**
     * Real-Time Search Logic
     */
    function searchProducts(input) {
        const query = input.value.trim();
        const container = input.closest('.search-container');
        if (!container) return;
        const resultsContainer = container.querySelector('.search-results-dropdown');
        if (!resultsContainer) return;
        
        if (query.length < 2) {
            resultsContainer.style.display = 'none';
            return;
        }

        // debounce
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(async () => {
            console.log('Searching for:', query);
            resultsContainer.innerHTML = '<div style="padding: 16px; text-align: center; color: #64748b;"><i class="fas fa-spinner fa-spin"></i> Searching...</div>';
            resultsContainer.style.display = 'block';

            try {
                const url = `{{ route('admin.products.search.api') }}?q=${encodeURIComponent(query)}`;
                const response = await fetch(url);
                const products = await response.json();
                
                // CRITICAL: Always update currentSearchResults
                currentSearchResults = products; 

                if (products && products.length > 0) {
                    let html = '';
                    products.forEach(p => {
                        html += `
                            <div class="search-result-item" onmousedown="selectProduct(this, ${p.id})">
                                <div style="flex: 1;">
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
                    resultsContainer.innerHTML = '<div style="padding: 16px; text-align: center; color: #64748b;">No products found</div>';
                }
            } catch (error) {
                console.error('Search AJAX error:', error);
                resultsContainer.innerHTML = '<div style="padding: 16px; text-align: center; color: #ef4444;">Search failed</div>';
            }
        }, 300);
    }

    function selectProduct(element, id) {
        const product = currentSearchResults.find(p => p.id == id);
        if (!product) {
            console.error('Product not found in results:', id);
            return;
        }

        const row = element.closest('tr');
        if (!row) return;

        // Set Basic Info
        const nameInput = row.querySelector('.item-name');
        const priceInput = row.querySelector('.item-price');
        const idInput = row.querySelector('.item-product-id');

        if (nameInput) nameInput.value = product.name;
        if (priceInput) {
            priceInput.value = product.unit_price;
            priceInput.dataset.basePrice = product.unit_price;
        }
        if (idInput) idInput.value = product.id;
        
        // Load configurations
        renderTechnicalAttributes(row, product);

        // Close dropdown
        const dropdown = element.closest('.search-results-dropdown');
        if (dropdown) dropdown.style.display = 'none';
        
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
    .form-control:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 4px rgba(37,99,235,0.1); }
    th { color: #64748b !important; padding: 16px 24px !important; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800; font-size: 10px; }
    td { transition: background 0.2s; }
    tr:hover td { background: rgba(37,99,235,0.01); }

    /* Professional Search Dropdown */
    .search-container { position: relative; }
    .search-results-dropdown {
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        width: 100%;
        min-width: 450px;
        max-height: 400px;
        overflow-y: auto !important;
        background: #ffffff !important;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
        z-index: 999999 !important;
        display: none;
        pointer-events: auto;
        animation: slideDown 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .search-container { 
        position: relative; 
        z-index: 5;
    }
    
    .search-container:focus-within {
        z-index: 999999 !important;
    }

    td { 
        overflow: visible !important; 
        position: relative;
    }
    
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px) scale(0.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .search-result-item {
        padding: 16px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        transition: all 0.2s;
        border-bottom: 1px solid #f1f5f9;
        gap: 20px;
    }

    .search-result-item:last-child { border-bottom: none; }

    .search-result-item:hover {
        background: #f8fafc;
        padding-left: 28px;
    }

    .search-result-item:hover .search-result-name {
        color: #2563eb;
    }

    .search-result-category {
        font-size: 10px;
        font-weight: 900;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        display: block;
        margin-bottom: 4px;
    }

    .search-result-name {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }

    .search-result-price {
        font-size: 14px;
        font-weight: 950;
        color: #10b981;
        background: #ecfdf5;
        padding: 6px 14px;
        border-radius: 10px;
        white-space: nowrap;
    }
    /* Responsive Adjustments */
    @media (max-width: 991.98px) {
        .glass-header { padding: 20px !important; margin-bottom: 20px !important; }
        .glass-header .row { flex-direction: column; text-align: left !important; }
        .glass-header .col-md-3 { text-align: left !important; margin-top: 16px; }

        .builder-container { padding: 10px !important; }

        #items-table thead { display: none; }
        #items-table, #items-body, .builder-row, .builder-row td { display: block; width: 100% !important; }
        
        .header-main-btn { width: 100% !important; margin-top: 12px; }
        .builder-row {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            margin-bottom: 24px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .builder-row td {
            padding: 12px 0 !important;
            border-bottom: 1px solid #f1f5f9;
            text-align: left !important;
        }

        .builder-row td:last-child { border-bottom: none; }

        .builder-row td::before {
            content: attr(data-label);
            display: block;
            font-size: 10px;
            font-weight: 900;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .builder-row td.col-actions::before { display: none; }
        .builder-row td.col-actions { text-align: left !important; padding-top: 20px !important; }

        .item-qty, .item-price, .item-vat-percent, .item-vat-amount { 
            width: 100% !important; 
            max-width: 100% !important;
            text-align: left !important;
            padding-left: 16px !important;
            margin: 0 !important;
        }
        
        .col-qty input, .col-rate div, .col-vat-pct div, .col-vat-amt input {
            max-width: 100% !important;
            margin: 0 !important;
        }

        .item-price { padding-left: 32px !important; }
        .item-row-total { padding-top: 5px !important; text-align: left !important; font-size: 24px !important; }
        
        .attributes-grid { grid-template-columns: 1fr 1fr !important; gap: 15px !important; }
        
        .search-results-dropdown {
            min-width: 100% !important;
            width: 100% !important;
        }

        .financial-summary-row > div {
            margin-bottom: 15px;
        }
        .financial-summary-row button {
            margin-top: 10px;
        }
    }

    @media (max-width: 767.98px) {
        .attributes-grid { grid-template-columns: 1fr !important; }
        #summary-total { font-size: 32px !important; }
    }
</style>
@endsection
