<tr>
    <td style="padding: 24px 32px; vertical-align: top;">
        <input type="hidden" name="items[{{ $index }}][product_id]" class="item-product-id" value="{{ $item->product_id ?? '' }}">
        
        <div style="position: relative;" class="search-container">
            <input type="text" name="items[{{ $index }}][name]" class="form-control item-name" placeholder="Search catalog or type custom description..." value="{{ $item->product_name ?? ($item->name ?? '') }}" required 
                oninput="searchProducts(this)" 
                onfocus="searchProducts(this)"
                onblur="hideSearchResults(this)"
                autocomplete="off"
                style="font-weight: 700; color: #0f172a; border-radius: 12px; height: 50px; padding: 0 16px;">
            <div class="search-results-dropdown" style="display: none;"></div>
        </div>

        {{-- Configuration Toggle / Attributes --}}
        <div class="attributes-wrapper" style="display: none; margin-top: 12px; padding: 16px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 12px;">
            <div style="font-size: 9px; font-weight: 900; color: #b89b5e; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px;">Configuration Settings</div>
            <div class="attributes-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 12px;"></div>
        </div>
    </td>
    
    <td style="padding: 24px 12px; text-align: center; vertical-align: top;">
        <input type="number" name="items[{{ $index }}][quantity]" class="form-control item-qty" value="{{ $item->quantity ?? 1 }}" min="1" step="1" oninput="calculateTotals()" required style="width: 70px; text-align: center; border-radius: 12px; height: 50px; font-weight: 800;">
    </td>
    
    <td style="padding: 24px 12px; vertical-align: top;">
        <div style="position: relative; display: flex; align-items: center;">
            <span style="position: absolute; left: 14px; color: #94a3b8; font-weight: 800; pointer-events: none;">$</span>
            <input type="number" name="items[{{ $index }}][price]" class="form-control item-price" value="{{ $item->unit_price ?? 0 }}" min="0" step="0.01" oninput="calculateTotals()" required style="padding-left: 28px; font-weight: 800; border-radius: 12px; height: 50px;">
        </div>
    </td>

    <td style="padding: 24px 12px; vertical-align: top;">
        <div style="position: relative; display: flex; align-items: center;">
            <input type="number" name="items[{{ $index }}][vat_percentage]" class="form-control item-vat-percent" value="{{ $item->vat_percentage ?? 0 }}" min="0" step="0.01" oninput="calculateTotals()" placeholder="0" style="padding-right: 28px; font-weight: 800; border-radius: 12px; height: 50px; text-align: center;">
            <span style="position: absolute; right: 14px; color: #94a3b8; font-weight: 800; font-size: 12px;">%</span>
        </div>
    </td>

    <td style="padding: 24px 12px; vertical-align: top;">
        <input type="number" name="items[{{ $index }}][vat_amount]" class="form-control item-vat-amount" value="{{ $item->vat_amount ?? 0 }}" readonly style="font-weight: 700; border-radius: 12px; height: 50px; background: #f1f5f9; border: none; text-align: center; color: #64748b;">
    </td>
    
    <td style="padding: 24px 32px; text-align: right; vertical-align: top;">
        <div class="item-row-total" style="font-size: 20px; font-weight: 950; color: #0f172a; padding-top: 10px;">
            ${{ number_format(($item->subtotal ?? 0), 2) }}
        </div>
    </td>
    
    <td style="padding: 24px 16px; text-align: center; width: 60px; vertical-align: top;">
        <button type="button" onclick="removeRow(this)" style="width: 40px; height: 40px; border-radius: 12px; background: #fff5f5; border: 1px solid #fee2e2; color: #ef4444; transition: all 0.2s; display: flex; align-items: center; justify-content: center;" onmouseover="this.style.background='#ef4444'; this.style.color='#fff';" onmouseout="this.style.background='#fff5f5'; this.style.color='#ef4444';">
            <i class="fas fa-trash-alt"></i>
        </button>
    </td>
</tr>


