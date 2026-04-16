@extends('layouts.admin')

@section('content')
<div class="container-fluid px-0 mb-5">
    
    {{-- Header Section --}}
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-5">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="fs-4">📦</span>
                <span class="text-warning fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 2px;">Product Registry</span>
            </div>
            <h1 class="fw-bolder text-dark mb-0" style="font-size: 2.5rem; letter-spacing: -2px;">Master Catalog</h1>
            <p class="text-secondary mt-2 mb-0 fw-semibold">Manage your dynamic product range and technical configurations.</p>
        </div>
        <div>
            <a href="{{ route('admin.products.create') }}" class="btn btn-warning text-white fw-bold d-inline-flex align-items-center gap-2 px-4 py-3 rounded-4 shadow-sm" style="transition: all 0.3s; border: 1px solid rgba(255,255,255,0.1);">
                <span>✨</span> Register New Product
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-3 fw-bold rounded-4 p-4 mb-5 shadow-sm border-0 bg-success bg-opacity-10 text-success">
            <i class="fas fa-check-circle fs-4"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    {{-- Product Table --}}
    <div class="card border-0 rounded-4 shadow-sm overflow-hidden bg-white">
        <div class="table-responsive mb-0 border-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="py-3 px-4 text-secondary opacity-75 small text-uppercase">Essential Details</th>
                        <th class="py-3 px-4 text-secondary opacity-75 small text-uppercase">Classification</th>
                        <th class="py-3 px-4 text-secondary opacity-75 small text-uppercase">Pricing Model</th>
                        <th class="py-3 px-4 text-secondary opacity-75 small text-uppercase">Config / Options</th>
                        <th class="py-3 px-4 text-secondary opacity-75 small text-uppercase text-center">Unit Value</th>
                        <th class="py-3 px-4 text-secondary opacity-75 small text-uppercase text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                    <tr>
                        <td class="p-4 border-bottom">
                            <div class="fw-bolder fs-6 text-dark" style="letter-spacing: -0.5px;">{{ $p->name }}</div>
                            <div class="small text-secondary mt-1 fw-semibold">{{ Str::limit($p->description, 60) }}</div>
                        </td>
                        <td class="p-4 border-bottom">
                            <span class="badge bg-light text-secondary border px-3 py-2 text-uppercase fw-bold">
                                {{ $p->category ?: 'General' }}
                            </span>
                        </td>
                        <td class="p-4 border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle" style="width: 8px; height: 8px; background-color: {{ $p->pricing_type == 'fixed' ? '#0d6efd' : '#198754' }}"></div>
                                <span class="fw-bold text-capitalize" style="font-size: 0.85rem;">{{ $p->pricing_type }}</span>
                            </div>
                        </td>
                        <td class="p-4 border-bottom">
                            @if($p->attributes && count($p->attributes) > 0)
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($p->attributes as $key => $data)
                                        <span class="badge bg-white border border-warning text-warning text-uppercase fw-bolder" style="font-size: 0.6rem;">
                                            {{ $key }}
                                            @if(is_array($data) && isset($data['price']) && $data['price'] > 0)
                                                <span class="text-success fw-bolder"> (+${{ $data['price'] }})</span>
                                            @endif
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-secondary opacity-50 small">—</span>
                            @endif
                        </td>
                        <td class="p-4 text-center border-bottom">
                            <div class="fw-bolder text-success fs-5">${{ number_format($p->unit_price, 2) }}</div>
                        </td>
                        <td class="p-4 text-end border-bottom">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.products.edit', $p->id) }}" class="btn btn-sm btn-light border rounded-3 d-flex align-items-center justify-content-center text-secondary product-action-btn" title="Edit Product" style="width: 36px; height: 36px;"><i class="fas fa-pencil-alt"></i></a>
                                <form action="{{ route('admin.products.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Archive this product?')" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border rounded-3 d-flex align-items-center justify-content-center text-danger product-delete-btn" title="Delete Product" style="width: 36px; height: 36px;"><i class="fas fa-times"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-5 text-center">
                            <div class="fs-1 opacity-25 mb-3" style="filter: grayscale(1);">📦</div>
                            <h4 class="fw-bolder text-dark mb-1">Catalog is Empty</h4>
                            <p class="text-secondary small">Start adding products to enable your sales team.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .product-action-btn { transition: all 0.2s; }
    .product-action-btn:hover { border-color: #ffc107 !important; color: #ffc107 !important; transform: translateY(-2px); box-shadow: 0 5px 15px -5px rgba(255, 193, 7, 0.4); }
    .product-delete-btn { transition: all 0.2s; }
    .product-delete-btn:hover { border-color: #dc3545 !important; color: #dc3545 !important; transform: translateY(-2px); box-shadow: 0 5px 15px -5px rgba(220, 53, 69, 0.4); }
</style>
@endsection
