<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer('layouts.admin', function ($view) {
            $view->with('sidebarCounts', [
                'Enquiries'      => \App\Models\Enquiry::count(),
                'New Leads'      => \App\Models\Lead::whereIn('status', ['new', 'new_lead'])->count(),
                'Contacted'      => \App\Models\Lead::whereIn('status', ['contacted'])->count(),
                'Appointments'   => \App\Models\Lead::whereIn('status', ['site_visit_scheduled', 'measurement_done', 'appointment'])->count(),
                'Quotation Sent' => \App\Models\Lead::whereIn('status', ['quotation_sent', 'quote_sent'])->count(),
                'Invoice Sent'   => \App\Models\Lead::whereIn('status', ['invoice_sent', 'proforma_sent'])->count(),
                'Negotiations'   => \App\Models\Lead::whereIn('status', ['negotiation', 'discussion'])->count(),
                'Converted'      => \App\Models\Lead::whereIn('status', ['deal_won', 'won', 'completed', 'converted'])->count(),
                'Lost'           => \App\Models\Lead::whereIn('status', ['lost'])->count(),
            ]);
        });
    }
}
