<tr>
    <td style="padding: 20px 24px; position: relative;">
        <input type="hidden" name="items[{{ $index }}][product_id]" class="item-product-id" value="{{ $item->product_id ?? '' }}">
        
        <div style="margin-bottom: 12px; position: relative;" class="search-container">
            <label style="font-size: 10px; font-weight: 800; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 6px;">PRODUCT / DESCRIPTION</label>
            <input type="text" name="items[{{ $index }}][name]" class="form-control item-name" placeholder="Search catalog or type custom description..." value="{{ $item->name ?? '' }}" required 
                oninput="searchProducts(this)" 
                onfocus="searchProducts(this)"
                onblur="hideSearchResults(this)"
                autocomplete="off"
                style="font-weight: 700; color: var(--text); background: var(--surface2); border-color: var(--border); border-radius: 10px;">
            
            {{-- Dynamic Search Results --}}
            <div class="search-results-dropdown" style="display: none;"></div>
        </div>
        
        <div style="display: grid; grid-template-columns: auto 1fr; gap: 16px; align-items: flex-end;">
            {{-- Professional Size Tool --}}
            <div style="background: #fff; border: 1.5px solid var(--border); border-radius: 12px; padding: 6px 12px; display: flex; align-items: center; gap: 8px; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);">
                <div style="text-align: center;">
                    <div style="font-size: 9px; font-weight: 800; color: var(--muted); margin-bottom: 2px;">WIDTH</div>
                    <input type="text" name="items[{{ $index }}][width]" class="form-control-minimal item-width" placeholder="W" value="{{ $item->width ?? '' }}" style="width: 48px; text-align: center; border: none; background: transparent; font-size: 15px; font-weight: 900; color: var(--accent); padding: 0;">
                </div>
                <div style="color: var(--border); font-weight: 300; font-size: 20px; line-height: 1;">×</div>
                <div style="text-align: center;">
                    <div style="font-size: 9px; font-weight: 800; color: var(--muted); margin-bottom: 2px;">HEIGHT</div>
                    <input type="text" name="items[{{ $index }}][height]" class="form-control-minimal item-height" placeholder="H" value="{{ $item->height ?? '' }}" style="width: 48px; text-align: center; border: none; background: transparent; font-size: 15px; font-weight: 900; color: var(--accent); padding: 0;">
                </div>
                <div style="font-size: 10px; font-weight: 800; color: var(--muted); padding-left: 4px; border-left: 1px solid var(--border);">IN</div>
            </div>
            
            <div style="flex: 1;">
                <label style="font-size: 10px; font-weight: 800; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 6px;">INSTALLATION NOTES</label>
                <input type="text" name="items[{{ $index }}][notes]" class="form-control item-notes" placeholder="e.g. Inside Mount / Blackout Fabric" value="{{ $item->notes ?? '' }}" style="font-size: 12px; height: 42px; border-radius: 10px; border-style: dashed;">
            </div>
        </div>
    </td>
    
    <td style="padding: 20px 12px; text-align: center;">
        <label style="font-size: 10px; font-weight: 800; color: var(--muted); text-transform: uppercase; display: block; margin-bottom: 10px;">QTY</label>
        <div style="display: inline-flex; align-items: center; background: var(--surface2); border-radius: 10px; padding: 4px; border: 1.5px solid var(--border);">
            <input type="number" name="items[{{ $index }}][quantity]" class="form-control-minimal item-qty" value="{{ $item->quantity ?? 1 }}" min="1" step="1" oninput="calculateTotals()" required style="width: 45px; text-align: center; border: none; background: transparent; font-size: 15px; font-weight: 800; padding: 0;">
        </div>
    </td>
    
    <td style="padding: 20px 12px;">
        <label style="font-size: 10px; font-weight: 800; color: var(--muted); text-transform: uppercase; display: block; margin-bottom: 10px; text-align: center;">UNIT RATE</label>
        <div style="position: relative; display: flex; align-items: center;">
            <span style="position: absolute; left: 14px; color: var(--muted); font-weight: 800; font-size: 14px;">$</span>
            <input type="number" name="items[{{ $index }}][price]" class="form-control item-price" value="{{ $item->unit_price ?? 0 }}" min="0" step="0.01" oninput="calculateTotals()" required style="padding-left: 28px; font-weight: 800; font-size: 15px; border-radius: 10px; background: var(--surface2); height: 45px;">
        </div>
    </td>
    
    <td style="padding: 20px 24px; text-align: right;">
        <label style="font-size: 10px; font-weight: 800; color: var(--muted); text-transform: uppercase; display: block; margin-bottom: 10px;">TOTAL</label>
        <div class="item-row-total" style="font-size: 18px; font-weight: 900; color: var(--text); letter-spacing: -0.5px;">
            ${{ number_format(($item->unit_price ?? 0) * ($item->quantity ?? 1), 2) }}
        </div>
    </td>
    
    <td style="padding: 20px 16px; text-align: center; width: 60px; vertical-align: bottom; padding-bottom: 32px;">
        <button type="button" onclick="removeRow(this)" style="width: 32px; height: 32px; border-radius: 8px; background: #fee2e2; border: 1.5px solid #fecaca; color: #ef4444; cursor: pointer; display: flex; align-items: center; justify-content:center; transition: all .2s; font-size: 14px;" onmouseover="this.style.background='#ef4444'; this.style.color='white'; this.style.borderColor='#ef4444';" onmouseout="this.style.background='#fee2e2'; this.style.color='#ef4444'; this.style.borderColor='#fecaca';">
            ✕
        </button>
    </td>
</tr>

<style>
    .form-control-minimal:focus { outline: none; }
    .search-results-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1.5px solid var(--border);
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        z-index: 1000;
        margin-top: 8px;
        max-height: 250px;
        overflow-y: auto;
    }
    .search-result-item {
        padding: 12px 16px;
        cursor: pointer;
        border-bottom: 1px solid var(--surface2);
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.2s;
    }
    .search-result-item:hover {
        background: var(--surface2);
    }
    .search-result-item:last-child {
        border-bottom: none;
    }
    .search-result-name {
        font-weight: 700;
        color: var(--text);
        font-size: 13px;
    }
    .search-result-price {
        font-weight: 800;
        color: var(--accent);
        font-size: 13px;
    }
    .search-result-category {
        font-size: 10px;
        font-weight: 800;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
    }
</style>
