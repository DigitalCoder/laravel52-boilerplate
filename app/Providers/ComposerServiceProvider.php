<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\RankingSubmission;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Auth $auth, Request $request)
    {
        view()->composer('layouts.partials.google-analytics', function ($view) {
            // Set the Google Analytics property ID
            $propertyId = env('GOOGLE_ANALYTICS_PROPERTY', null);
            $view->with('analyticsPropertyId', $propertyId);

            // Set custom dimensions here

        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
