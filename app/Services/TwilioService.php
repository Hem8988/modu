<?php
namespace App\Services;

class TwilioService
{
    protected string $sid;
    protected string $token;
    protected string $from;

    public function __construct()
    {
        // 🕒 Fetch from DB settings first, then fallback to config (.env)
        $settings = \App\Models\Setting::getAll();
        
        $this->sid   = $settings['twilio_sid'] ?? config('services.twilio.sid') ?? '';
        $this->token = $settings['twilio_token'] ?? config('services.twilio.token') ?? '';
        $this->from  = $settings['twilio_from'] ?? config('services.twilio.from') ?? '';
    }

    public function send(string $to, string $message): bool
    {
        if (empty($this->sid) || empty($this->token) || empty($this->from)) return false;

        $url  = "https://api.twilio.com/2010-04-01/Accounts/{$this->sid}/Messages.json";
        $data = ['From' => $this->from, 'To' => $to, 'Body' => $message];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->sid}:{$this->token}");
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $code === 201;
    }
}
