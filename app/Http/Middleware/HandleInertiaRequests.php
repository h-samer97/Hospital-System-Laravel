<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Inertia\Middleware;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [

            'auth' => [
                'user'  => $request->user(),
                'admin' => $request->user('admins'),
            ],
           
            'flash' => $request->session()->get('flash'),

            // ✅ اللغة الحالية
            'locale' => App::getLocale(),

            // ✅ اتجاه الصفحة مباشرة من إعدادات mcamara
            'dir' => LaravelLocalization::getCurrentLocaleDirection(),

            // ✅ اللغات المدعومة من config/laravellocalization.php
            'supportedLocales' => collect(LaravelLocalization::getSupportedLocales())
                ->map(fn($props, $code) => [
                    'code'   => $code,
                    'native' => $props['native'],
                    'dir'    => $props['dir'] ?? 'ltr',
                    'url'    => LaravelLocalization::getLocalizedURL($code, null, [], true),
                ])
                ->values()
                ->toArray(),

        ]);
    }
}