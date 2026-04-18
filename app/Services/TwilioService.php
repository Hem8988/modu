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

    public function setCredentials(string $sid, string $token, string $from): self
    {
        $this->sid = $sid;
        $this->token = $token;
        $this->from = $from;
        return $this;
    }

    public function send(string $to, string $message): bool
    {
        $to = $this->sanitize($to);
        if (empty($this->sid) || empty($this->token) || empty($this->from) || empty($to)) {
            \Log::warning("Twilio skipped: Missing SID, Token, From, or Cleaned Recipient ($to)");
            return false;
        }

        $url  = "https://api.twilio.com/2010-04-01/Accounts/{$this->sid}/Messages.json";
        $data = ['From' => $this->from, 'To' => $to, 'Body' => $message];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->sid}:{$this->token}");
        $response = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($code !== 201) {
            \Log::error("Twilio Send Failed [Status $code] to $to: " . $response);
            return false;
        }
        
        return true;
    }

    private function sanitize(string $number): string
    {
        // Remove everything except digits and the plus sign
        $cleaned = preg_replace('/[^\d+]/', '', $number);
        
        // Ensure it starts with at least a '+' or a leading digit
        return $cleaned;
    }
}
