<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use App\Models\DriverLicense;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
    if (config('app.env') === 'production') {
        URL::forceScheme('https');
    }

    View::composer('*', function ($view) {

if(!Auth::check()){
return;
}

$licenses = DriverLicense::where('user_id', Auth::id())->get();

foreach ($licenses as $license) {

$days = Carbon::today()->diffInDays(Carbon::parse($license->expire_date), false)+1;
$license->days_left = $days;

}

$expired = $licenses->where('days_left','<',0)->count();

$alert3 = $licenses->where('days_left','>=',0)
->where('days_left','<=',3)->count();

$alert7 = $licenses->where('days_left','>',3)
->where('days_left','<=',7)->count();

$alert15 = $licenses->where('days_left','>',7)
->where('days_left','<=',15)->count();

$view->with([
'expired' => $expired,
'alert3' => $alert3,
'alert7' => $alert7,
'alert15' => $alert15
]);

});

}
}
