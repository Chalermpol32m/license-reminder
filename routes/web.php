<?php


use App\Http\Controllers\LicenseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DriverLicenseController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\DeliveryJobController;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware(['auth'])->group(function () {

    Route::post('/jobs/auto-assign',[DeliveryJobController::class,'autoAssign']);
    
    Route::get('/dashboard', [DriverLicenseController::class, 'dashboard'])
        ->name('dashboard');

   Route::get('/gallery', [DriverLicenseController::class, 'gallery'])
    ->name('gallery');

    // resource route
    Route::resource('licenses', DriverLicenseController::class);

});

Route::post('/webhook', function (\Illuminate\Http\Request $request) {
    \Log::info($request->all());
    return response()->json(['status' => 'ok']);
});

/*
|--------------------------------------------------------------------------
| Transport Jobs
|--------------------------------------------------------------------------
*/
Route::get('/jobs',[DeliveryJobController::class,'index']);
Route::post('/jobs/store',[DeliveryJobController::class,'store']);

Route::get('/create-user', function () {

    User::create([
        'name' => 'admin',
        'email' => 'admin@email.com',
        'password' => Hash::make('12345678'),
    ]);

    return "User created";
});

require __DIR__.'/auth.php';