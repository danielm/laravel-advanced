<?php

namespace App\Providers;

use App\Models\Rating;
use Illuminate\Support\Facades\View;

use App\Http\Resources\RatingResource;
use Illuminate\Support\ServiceProvider;

class RatingProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('home', function(\Illuminate\View\View  $view){
            $ratings = Rating::latest()->with(['user', 'rateable'])->take(10)->get();

            $view->with('ratings', $ratings);
        });
    }
}
