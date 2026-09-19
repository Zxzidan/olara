<?php

namespace App\Providers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
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
        if (app()->environment('production')) {
            $flag = storage_path('framework/migrated_20260920');
            if (! file_exists($flag)) {
                try {
                    Artisan::call('migrate', ['--force' => true]);
                    @touch($flag);
                } catch (\Throwable $e) {
                    Log::warning('Auto migrate notice: '.$e->getMessage());
                }
            }
        }
    }
}
