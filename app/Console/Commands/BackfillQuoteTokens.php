<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Quote;
use Illuminate\Support\Str;

class BackfillQuoteTokens extends Command
{
    protected $signature = 'quotes:backfill-tokens';
    protected $description = 'Generate secure client tokens for existing quotations';

    public function handle()
    {
        $quotes = Quote::whereNull('client_token')->get();
        $this->info("Found " . $quotes->count() . " quotations missing tokens.");

        foreach($quotes as $q) {
            $q->update(['client_token' => Str::random(40)]);
            $this->line("✓ Generated token for Quote #{$q->quote_number}");
        }

        $this->info("Completed backfill successfully!");
    }
}
