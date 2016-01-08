<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Dates
        Blade::directive('datetime', function ($expression) {
            return "<?php echo with{$expression}->format('m/d/Y H:i'); ?>";
        });

        Blade::directive('isodate', function ($expression) {
            return "<?php echo with{$expression}->format('c'); ?>";
        });

        Blade::directive('shorttime', function ($expression) {
            return "<?php echo with{$expression}->format('g:i A'); ?>";
        });

        Blade::directive('verbosedate', function ($expression) {
            return "<?php echo with{$expression}->format('D, F jS, Y'); ?>";
        });

        Blade::directive('verboseshortdate', function ($expression) {
            return "<?php echo with{$expression}->format('l, F jS'); ?>";
        });

        /*  |--------------------------------------------------------------------------
            | Extend blade so we can define a variable
            | <code>
            | @define $variable = "whatever"
            | </code>
            |-------------------------------------------------------------------------- */
        Blade::extend(function ($value) {
            return preg_replace('/\@define(.+)/', '<?php ${1}; ?>', $value);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
