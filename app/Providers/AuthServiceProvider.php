<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\PaymentAccount;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
class AuthServiceProvider extends ServiceProvider
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
        Gate::define('payment.viewany', function(Admin $admin) {
            return true;
        });
        Gate::define('payment.manage', function(Admin $admin) {
            return true;
        });
        Gate::define('payment.delete', function(Admin $admin, PaymentAccount $payment) {
            return $payment->created_at->diffInHours(now()) < 24;
        });
    }
}
