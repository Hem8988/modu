<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::getAll();
        $masterAttributes = \App\Models\MasterAttribute::all();
        return view('admin.settings.index', compact('settings', 'masterAttributes'));
    }

    public function update(Request $request)
    {
        \Log::info('Settings Update Request:', $request->all());
        $data = $request->except('_token');
        
        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', '✓ Global settings have been updated and synchronized.');
    }
}
