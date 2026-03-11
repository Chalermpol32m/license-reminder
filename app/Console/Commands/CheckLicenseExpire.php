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

        $licenses = DriverLicense::whereDate('expire_date','<=', now()->addDays(15))->get();

        $this->info("=================================");
        $this->info("   License Expire Checker");
        $this->info("=================================");

        foreach ($licenses as $license) {

            $expire = Carbon::parse($license->expire_date);
            $days = now()->diffInDays($expire, false)+1;

            $this->info($license->driver_name . " เหลือ " . $days . " วัน");

            // แจ้งเตือนช่วง 15 วัน
            if ($days == 15 && $days > 7) {

                $this->warn("⚠ แจ้งเตือนใกล้หมดอายุ : " . $license->driver_name);
                $this->sendLine($license, "ใบขับขี่ใกล้หมดอายุภายใน 15 วัน", $days);

            }

            // แจ้งเตือนช่วง 7 วัน
            elseif ($days == 7 && $days > 3) {

                $this->warn("⚠ แจ้งเตือนใกล้หมดอายุ : " . $license->driver_name);
                $this->sendLine($license, "ใบขับขี่ใกล้หมดอายุภายใน 7 วัน", $days);

            }

            // แจ้งเตือนช่วง 3 วัน
            elseif ($days == 3 && $days > 0) {

                $this->error("🚨 ใกล้หมดอายุ : " . $license->driver_name);
                $this->sendLine($license, "ใบขับขี่ใกล้หมดอายุภายใน 3 วัน", $days);

            }

            // หมดอายุวันนี้
            elseif ($days == 0) {

                $this->error("🚨 หมดอายุวันนี้ : " . $license->driver_name);
                $this->sendLine($license, "ใบขับขี่หมดอายุวันนี้", $days);

            }

            // หมดอายุแล้ว
            elseif ($days < 0) {

                $this->error("❌ หมดอายุแล้ว : " . $license->driver_name);
                $this->sendLine($license, "ใบขับขี่หมดอายุแล้ว", $days);

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

        Http::withHeaders([
            'Authorization' => 'Bearer ' . env('LINE_NOTIFY_TOKEN'),
        ])->asForm()->post('https://notify-api.line.me/api/notify', [
            'message' => $message
        ]);

   if ($response->successful()) {
    $this->info("ส่ง LINE สำเร็จ: {$license->driver_name}");
} else {
    $this->error("LINE ส่งไม่สำเร็จ");
}
    }
    }