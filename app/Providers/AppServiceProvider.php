<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;

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
        View::composer('layouts.navigation', function ($view) {
            $apiKey = env('SMSGATEWAYHUB_API_KEY');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://www.smsgatewayhub.com/api/mt/GetBalance?APIKey=$apiKey");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            $balance = json_decode($response, true);
            $balanceParts = explode('|', $balance['Balance']);
            $transBalance = explode(':', $balanceParts[1]);
            $view->with('balance', $transBalance[1]);
        });
        Route::aliasMiddleware('admin', AdminMiddleware::class);
    }
}

?>
