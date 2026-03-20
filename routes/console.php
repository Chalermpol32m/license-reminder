<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| Here you may define all of your Closure based console commands.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


/*
|--------------------------------------------------------------------------
| Scheduler
|--------------------------------------------------------------------------
|
| ตั้งเวลาให้ระบบตรวจสอบใบขับขี่ที่ใกล้หมดอายุ
| ทุกวันเวลา 09:00
|
*/

Schedule::command('check:license-expire')
    ->dailyAt('09:00')
    ->timezone('Asia/Bangkok');