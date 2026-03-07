<?php


use App\Http\Controllers\LicenseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DriverLicenseController;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware(['auth'])->group(function () {

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

require __DIR__.'/auth.php';