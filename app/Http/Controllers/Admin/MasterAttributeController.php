<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterAttribute;
use Illuminate\Http\Request;

class MasterAttributeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'default_values' => 'nullable|string',
            'default_price' => 'nullable|numeric|min:0',
        ]);

        MasterAttribute::create($request->all());

        return back()->with('success', 'Master Attribute added successfully.');
    }

    public function update(Request $request, $id)
    {
        $attr = MasterAttribute::findOrFail($id);
        $attr->update($request->all());

        return back()->with('success', 'Master Attribute updated successfully.');
    }

    public function destroy($id)
    {
        MasterAttribute::destroy($id);
        return back()->with('success', 'Master Attribute deleted.');
    }
}
