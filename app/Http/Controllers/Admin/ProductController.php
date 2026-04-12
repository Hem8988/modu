<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()    { return view('admin.products.index', ['products' => Product::all()]); }
    public function store(Request $request)
    {
        Product::create($request->only(['name','category','pricing_type','unit_price','description']));
        return back()->with('success','Product added.');
    }
    public function update(Request $request, $id)
    {
        Product::findOrFail($id)->update($request->only(['name','category','pricing_type','unit_price','description']));
        return back()->with('success','Product updated.');
    }
    public function destroy($id) { Product::destroy($id); return back()->with('success','Product deleted.'); }
}
