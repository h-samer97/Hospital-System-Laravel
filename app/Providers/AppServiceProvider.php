<?php

namespace App\Providers;

use App\Interfaces\ISections;
use App\Models\PaymentAccount;
use App\Models\Receipt;
use App\Models\SingleInvoices;
use App\Observers\PatientFinancialObserver;
use App\Repositories\SectionsRepositories;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;


class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        
    }

    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
        RateLimiter::for('financial', function(Request $request) {
        return Limit::perMinute(3)
        ->by($request->user()?->id ?? $request->ip())
        ->response(fn() => response()->json([
                'message' => 'Too many requests [429]. Please wait.'
        ], 429));
    });

    $observer = new PatientFinancialObserver();
    SingleInvoices::observe($observer);
    Receipt::observe($observer);
    PaymentAccount::observe($observer);


    }
}
