<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class SettingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Cache settings to avoid database overhead on every page load
        // But for this industrial CRM, we'll fetch once per request
        if (Schema::hasTable('settings')) {
            $settings = Setting::getAll();
            View::share('globalSettings', $settings);
        }
    }
}
