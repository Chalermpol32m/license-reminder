<?php

use App\Http\Controllers\LicenseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DriverLicenseController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\DeliveryJobController;
use Illuminate\Http\Request;
use App\Http\Controllers\VehicleController;

/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect('/dashboard');
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::post('/jobs/auto-assign', [DeliveryJobController::class, 'autoAssign']);
    
    Route::get('/dashboard', [DriverLicenseController::class, 'dashboard'])
        ->name('dashboard');

    Route::get('/gallery', [DriverLicenseController::class, 'gallery'])
        ->name('gallery');

    // resource route
    Route::resource('licenses', DriverLicenseController::class);
});

/*
|--------------------------------------------------------------------------
| Transport Jobs
|--------------------------------------------------------------------------
*/
Route::get('/jobs', [DeliveryJobController::class, 'index']);
Route::post('/jobs/store', [DeliveryJobController::class, 'store']);

/*
|--------------------------------------------------------------------------
| Create User (DEV ONLY)
|--------------------------------------------------------------------------
*/
Route::get('/create-user', function () {

    User::create([
        'name' => 'admin',
        'email' => 'admin@email.com',
        'password' => Hash::make('12345678'),
    ]);

    return "User created";
});

/*
|--------------------------------------------------------------------------
| 🔥 RUN SCHEDULE (สำคัญมาก)
|--------------------------------------------------------------------------
*/
Route::get('/run-schedule', function () {

    // 🔐 กันยิงมั่ว
    if (request('key') !== env('SCHEDULE_KEY')) {
        abort(403);
    }

    // 🚀 รัน schedule
    \Artisan::call('schedule:run');

    return 'OK';
});

// 🔹 route สำหรับ auto assign
Route::get('/jobs/auto-assign', [DeliveryJobController::class, 'autoAssign'])
    ->name('jobs.autoAssign');

Route::get('/vehicles', [VehicleController::class, 'index']);
Route::post('/vehicles/store', [VehicleController::class, 'store']);
Route::post('/vehicles/update/{id}', [VehicleController::class, 'update']);
Route::post('/jobs/import', [DeliveryJobController::class, 'import']);
/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';