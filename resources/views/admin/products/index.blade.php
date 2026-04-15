@extends('layouts.admin')

@section('content')
<div style="max-width: 1400px; margin: 0 auto; padding: 40px;">
    
    {{-- Header Section --}}
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 48px;">
        <div>
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                <span style="font-size: 24px;">📦</span>
                <span style="font-size: 11px; font-weight: 900; color: var(--gold); text-transform: uppercase; letter-spacing: 2px;">Product Registry</span>
            </div>
            <h1 style="font-size: 42px; font-weight: 900; color: var(--text); letter-spacing: -2px; line-height: 1; margin: 0;">Master Catalog</h1>
            <p style="color: var(--muted); font-size: 16px; margin-top: 12px; font-weight: 500;">Manage your dynamic product range and technical configurations.</p>
        </div>
        <div>
            <a href="{{ route('admin.products.create') }}" 
               style="background: var(--gold); color: #fff; padding: 16px 32px; border-radius: 16px; font-weight: 800; text-decoration: none; display: inline-flex; align-items: center; gap: 12px; box-shadow: 0 10px 20px -5px rgba(184, 155, 94, 0.4); transition: all 0.3s; font-size: 14px; border: 1px solid rgba(255,255,255,0.1);">
                <span>✨</span> Register New Product
            </a>
        </div>
    </div>

    @if(session('success'))
        <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #065f46; padding: 20px 24px; border-radius: 20px; margin-bottom: 40px; font-weight: 700; display: flex; align-items: center; gap: 12px; animation: slideDown 0.4s ease-out;">
            <span style="font-size: 20px;">✓</span>
            {{ session('success') }}
        </div>
    @endif

    {{-- Product Table --}}
    <div style="background: #fff; border: 1px solid var(--border); border-radius: 32px; box-shadow: 0 20px 40px -15px rgba(0,0,0,0.03); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #fafafa;">
                    <th style="padding: 18px 24px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--muted); border-bottom: 2px solid var(--border);">Essential Details</th>
                    <th style="padding: 18px 24px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--muted); border-bottom: 2px solid var(--border);">Classification</th>
                    <th style="padding: 18px 24px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--muted); border-bottom: 2px solid var(--border);">Pricing Model</th>
                    <th style="padding: 18px 24px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--muted); border-bottom: 2px solid var(--border);">Config / Options</th>
                    <th style="padding: 18px 24px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--muted); border-bottom: 2px solid var(--border); text-align: center;">Unit Value</th>
                    <th style="padding: 18px 24px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: var(--muted); border-bottom: 2px solid var(--border); text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $p)
                <tr>
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border);">
                        <div style="font-weight: 900; font-size: 16px; color: var(--text); letter-spacing: -0.5px;">{{ $p->name }}</div>
                        <div style="font-size: 13px; color: var(--muted); margin-top: 4px; font-weight: 500;">{{ Str::limit($p->description, 60) }}</div>
                    </td>
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border);">
                        <span style="background: var(--surface2); color: var(--muted); padding: 6px 14px; border-radius: 10px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">
                            {{ $p->category ?: 'General' }}
                        </span>
                    </td>
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border);">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="width: 8px; height: 8px; border-radius: 50%; background: {{ $p->pricing_type == 'fixed' ? 'var(--accent)' : 'var(--success)' }}"></div>
                            <span style="font-weight: 700; font-size: 13px; text-transform: capitalize;">{{ $p->pricing_type }}</span>
                        </div>
                    </td>
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border);">
                        @if($p->attributes && count($p->attributes) > 0)
                            <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                @foreach($p->attributes as $key => $data)
                                    <span style="font-size: 9px; font-weight: 800; background: #fff; color: var(--gold); border: 1px solid var(--gold); padding: 2px 6px; border-radius: 4px; text-transform: uppercase;">
                                        {{ $key }}
                                        @if(is_array($data) && isset($data['price']) && $data['price'] > 0)
                                            <span style="color: var(--success); font-weight: 900;"> (+${{ $data['price'] }})</span>
                                        @endif
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <span style="font-size: 11px; color: var(--muted); opacity: 0.5;">—</span>
                        @endif
                    </td>
                    <td style="padding: 20px 24px; text-align: center; border-bottom: 1px solid var(--border);">
                        <div style="font-weight: 900; color: var(--success); font-size: 16px;">${{ number_format($p->unit_price, 2) }}</div>
                    </td>
                    <td style="padding: 20px 24px; text-align: right; border-bottom: 1px solid var(--border);">
                        <div style="display: flex; justify-content: flex-end; gap: 8px;">
                            <a href="{{ route('admin.products.edit', $p->id) }}" class="btn-action" title="Edit Product">✎</a>
                            <form action="{{ route('admin.products.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Archive this product?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" title="Delete Product">✕</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 80px 40px; text-align: center;">
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

<style>
    .btn-action { width: 36px; height: 36px; border-radius: 12px; border: 1.5px solid var(--border); background: #fff; cursor: pointer; color: var(--muted); display: flex; align-items: center; justify-content: center; transition: all .2s; text-decoration: none; font-size: 15px; }
    .btn-action:hover { border-color: var(--gold); color: var(--gold); transform: translateY(-2px); box-shadow: 0 5px 15px -5px rgba(184, 155, 94, 0.4); }
    .btn-delete:hover { border-color: var(--danger); color: var(--danger); box-shadow: 0 5px 15px -5px rgba(239, 68, 68, 0.4); }
    
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    tr:hover td { background: #fafafa; }
</style>
@endsection
