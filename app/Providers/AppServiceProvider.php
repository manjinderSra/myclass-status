<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Helpers\SubscriptionHelper;

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
        // Add Blade directive for feature check
        Blade::directive('hasFeature', function ($expression) {
            // Check if $expression contains a comma, which would indicate school_id is being passed
            if (strpos($expression, ',') !== false) {
                return "<?php if (\\App\\Helpers\\SubscriptionHelper::hasFeature({$expression})): ?>";
            } else {
                // No school_id passed, so use the $schoolId from the page scope if available
                return "<?php if (isset(\$schoolId) && \\App\\Helpers\\SubscriptionHelper::hasFeature({$expression}, \$schoolId)): ?>";
            }
        });

        Blade::directive('endhasFeature', function () {
            return "<?php endif; ?>";
        });

        // Add Blade directive for user limit check
        Blade::directive('withinUserLimit', function ($expression) {
            // Check if $expression contains a comma, which would indicate school_id is being passed
            if (strpos($expression, ',') !== false) {
                return "<?php if (\\App\\Helpers\\SubscriptionHelper::isWithinUserLimit({$expression})): ?>";
            } else {
                // No school_id passed, so use the $schoolId from the page scope if available
                return "<?php if (isset(\$schoolId) && \\App\\Helpers\\SubscriptionHelper::isWithinUserLimit({$expression}, \$schoolId)): ?>";
            }
        });

        Blade::directive('endwithinUserLimit', function () {
            return "<?php endif; ?>";
        });
    }
}
