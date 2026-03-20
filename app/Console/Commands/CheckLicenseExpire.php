<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DriverLicense;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class CheckLicenseExpire extends Command
{
    protected $signature = 'check:license-expire';
    protected $description = 'ตรวจสอบใบขับขี่ที่ใกล้หมดอายุ';

    public function handle()
    {

     if (now()->format('H:i') !== '09:00') {
         return;
    }

        $licenses = DriverLicense::whereDate('expire_date', '<=', now()->addDays(15))->get();

        $this->info("=================================");
        $this->info("   License Expire Checker");
        $this->info("=================================");

        foreach ($licenses as $license) {

            $expire = Carbon::parse($license->expire_date);
            $days = (int) now()->startOfDay()->diffInDays($expire->startOfDay(), false);

            $this->info($license->driver_name . " เหลือ " . $days . " วัน");

            // ❌ หมดอายุแล้ว
            if ($days < 0 && !$license->notify_expired) {

                $this->error("❌ หมดอายุแล้ว");
                $this->sendLine($license, "ใบขับขี่หมดอายุแล้ว", $days);

                $license->update(['notify_expired' => true]);
            }

            // 🚨 หมดอายุวันนี้
            elseif ($days == 0 && !$license->notify_expired) {

                $this->error("🚨 หมดอายุวันนี้");
                $this->sendLine($license, "ใบขับขี่หมดอายุวันนี้", $days);

                $license->update(['notify_expired' => true]);
            }

            // ⚠ 3 วัน
            elseif ($days <= 3 && $days > 0 && !$license->notify_3) {

                $this->warn("⚠ เหลือ 3 วัน");
                $this->sendLine($license, "ใบขับขี่ใกล้หมดอายุภายใน 3 วัน", $days);

                $license->update(['notify_3' => true]);
            }

            // ⚠ 7 วัน
            elseif ($days <= 7 && $days > 3 && !$license->notify_7) {

                $this->warn("⚠ เหลือ 7 วัน");
                $this->sendLine($license, "ใบขับขี่ใกล้หมดอายุภายใน 7 วัน", $days);

                $license->update(['notify_7' => true]);
            }

            // ⚠ 15 วัน
            elseif ($days <= 15 && $days > 7 && !$license->notify_15) {

                $this->warn("⚠ เหลือ 15 วัน");
                $this->sendLine($license, "ใบขับขี่ใกล้หมดอายุภายใน 15 วัน", $days);

                $license->update(['notify_15' => true]);
            }
        }

        $this->info("=================================");
    }

   private function sendLine($license, $title, $days)
{
    Carbon::setLocale('th');

    $expireDate = Carbon::parse($license->expire_date)
        ->translatedFormat('d F Y');

    $message = "🚗 แจ้งเตือนใบขับขี่\n";
    $message .= "━━━━━━━━━━━━━━\n";
    $message .= "👤 คนขับ : {$license->driver_name}\n";
    $message .= "🚘 ทะเบียน : {$license->plate_number}\n";
    $message .= "📅 วันหมดอายุ : {$expireDate}\n";
    $message .= "⏳ เหลือเวลา : {$days} วัน\n";
    $message .= "━━━━━━━━━━━━━━\n";
    $message .= "⚠ {$title}\n";
    $message .= "📌 กรุณาดำเนินการต่ออายุ";

    $user = $license->user;

    // 🔥 DEBUG ตรงนี้
    $this->info("===== DEBUG LINE =====");
    $this->info("Driver: " . $license->driver_name);
    $this->info("User ID: " . ($user->line_user_id ?? 'NULL'));
    $this->info("Token: " . (config('services.line.token') ? 'OK' : 'NULL'));

    if (!$user || !$user->line_user_id) {
        $this->error("ไม่มี LINE user: {$license->driver_name}");
        return;
    }

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . config('services.line.token'),
        'Content-Type' => 'application/json',
    ])->post('https://api.line.me/v2/bot/message/push', [
        'to' => $user->line_user_id,
        'messages' => [
            [
                'type' => 'text',
                'text' => $message,
            ]
        ]
    ]);

    // 🔥 DEBUG response
    $this->info("Response: " . $response->body());

    if ($response->successful()) {
        $this->info("ส่ง LINE สำเร็จ: {$license->driver_name}");
    } else {
        $this->error("LINE ส่งไม่สำเร็จ");
    }
}
}
