<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Inertia\Middleware;

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

            // اللغة الحالية
            'locale' => App::getLocale(),

            // اتجاه الصفحة (يمكن تعديله حسب اللغة)
            'dir' => App::getLocale() === 'ar' ? 'rtl' : 'ltr',

        ]);
    }
}