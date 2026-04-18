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

    public function testEmail(Request $request)
    {
        $request->validate([
            'email'           => 'required|email',
            'mail_host'       => 'required',
            'mail_port'       => 'required',
            'mail_username'   => 'required',
            'mail_password'   => 'required',
            'mail_encryption' => 'required',
            'mail_from_address' => 'required|email'
        ]);

        try {
            // Temporarily override mail configuration for this request
            config([
                'mail.mailers.smtp.host'       => $request->mail_host,
                'mail.mailers.smtp.port'       => $request->mail_port,
                'mail.mailers.smtp.username'   => $request->mail_username,
                'mail.mailers.smtp.password'   => $request->mail_password,
                'mail.mailers.smtp.encryption' => $request->mail_encryption,
                'mail.from.address'            => $request->mail_from_address,
                'mail.from.name'               => $request->mail_from_name ?? config('app.name'),
            ]);

            \Illuminate\Support\Facades\Mail::raw("✅ SMTP Connectivity Test Successful!\n\nThis is a test message from your ModuShade CRM Global Settings panel. If you are reading this, your email configuration is active and working correctly.", function($message) use ($request) {
                $message->to($request->email)
                        ->subject("ModuShade CRM: Connection Test Successful")
                        ->from($request->mail_from_address, $request->mail_from_name ?? config('app.name'));
            });

            return response()->json(['success' => true, 'message' => 'Connection test successful! Check your inbox.']);
        } catch (\Exception $e) {
            \Log::error('SMTP Test Failed: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Connection failed: ' . $e->getMessage()
            ], 400);
        }
    }
}
