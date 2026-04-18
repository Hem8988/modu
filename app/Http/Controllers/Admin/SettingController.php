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

    public function testSms(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'sid' => 'required',
            'token' => 'required',
            'from' => 'required'
        ]);

        $twilio = new \App\Services\TwilioService();
        $twilio->setCredentials($request->sid, $request->token, $request->from);

        $sent = $twilio->send($request->phone, "✅ Twilio connectivity test from ModuShade CRM was successful.");

        if ($sent) {
            return response()->json(['success' => true, 'message' => 'Connection test successful! Check your phone.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Connection failed. Please check your credentials and numbers.'], 400);
        }
    }
}
