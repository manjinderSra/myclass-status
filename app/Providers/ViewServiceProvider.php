<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\School;

class ViewServiceProvider extends ServiceProvider
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
        // Share school data with school panel views
        View::composer('client.schoolPanel.layout.*', function ($view) {
            if (Auth::check() && Auth::user()->role === 'school') {
                $school = School::where('admin_id', Auth::id())->first();
                $view->with('schoolData', $school);
            }
        });
    }
} 