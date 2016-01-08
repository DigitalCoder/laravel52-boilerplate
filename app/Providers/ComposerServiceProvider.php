<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Program;
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

            // Get details for this user
            $user = Auth::user();
            $userType = null;
            $userId = null;
            if ($user) {
                $userId = $user->id;
                $userType = ucwords($user->type);
            }
            $view->with('analyticsUserId', $userId);
            $view->with('analyticsUserType', $userType);

            // Get details for this program
            $programId = null;
            $programName = null;
            $program = Program::current();
            if ($program) {
                $programId = $program->id;
                $programName = $program->name;
            }
            $view->with('analyticsProgramId', $programId);
            $view->with('analyticsProgramName', $programName);

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
