<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Installation;
use App\Models\Lead;
use Illuminate\Http\Request;

class InstallationController extends Controller
{
    public function index()
    {
        $installations = Installation::with('lead')->latest()->paginate(15);
        
        $stats = [
            'pending'   => Installation::where('status', 'scheduled')->count(),
            'active'    => Installation::where('status', 'in_progress')->count(),
            'today'     => Installation::whereDate('date', today())->count(),
            'completed' => Installation::where('status', 'completed')->count(),
        ];

        return view('admin.installations.index', compact('installations', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate(['lead_id' => 'required', 'date' => 'required', 'team' => 'required']);
        Installation::create($request->all());
        return back()->with('success', '✓ Installation scheduled for dispatch.');
    }

    public function update(Request $request, $id)
    {
        $install = Installation::findOrFail($id);
        $install->update($request->only(['status', 'notes', 'team']));
        return back()->with('success', '✓ Installation status synchronized.');
    }

    public function destroy($id)
    {
        Installation::destroy($id);
        return back()->with('success', '✓ Installation record archived.');
    }
}
