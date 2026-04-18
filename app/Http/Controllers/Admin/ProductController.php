<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\MasterAttribute;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index() { 
        return view('admin.products.index', [
            'products' => Product::latest()->get(),
            'masterAttributes' => MasterAttribute::where('is_active', true)->get()
        ]); 
    }

    public function create()
    {
        return view('admin.products.create', [
            'masterAttributes' => MasterAttribute::where('is_active', true)->get()
        ]);
    }

    public function store(Request $request)
    {
        Product::create($request->only(['name','category','pricing_type','unit_price','description','attributes']));
        return redirect()->route('admin.products.index')->with('success','Product added successfully to the catalog.');
    }

    public function edit($id)
    {
        return view('admin.products.edit', [
            'product' => Product::findOrFail($id),
            'masterAttributes' => MasterAttribute::where('is_active', true)->get()
        ]);
    }

    public function update(Request $request, $id)
    {
        Product::findOrFail($id)->update($request->only(['name','category','pricing_type','unit_price','description','attributes']));
        return redirect()->route('admin.products.index')->with('success','Product updated successfully.');
    }

    public function destroy($id) 
    { 
        Product::destroy($id); 
        return back()->with('success','Product removed from catalog.'); 
    }

    public function apiSearch(Request $request)
    {
        $query = $request->get('q');
        if (!$query) return response()->json([]);

        $products = Product::where('name', 'LIKE', "%{$query}%")
            ->orWhere('category', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get();

        return response()->json($products);
    }
}
