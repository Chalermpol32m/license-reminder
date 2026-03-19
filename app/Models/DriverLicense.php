<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Carbon\Carbon;

class DriverLicense extends Model
{
    use HasFactory;

    /**
     * ฟิลด์ที่อนุญาตให้บันทึกลง database ได้ (Mass Assignment)
     */
    protected $fillable = [
        'driver_name',
        'license_number',
        'plate_number',
        'expire_date',
        'license_image',
        'user_id'
    ];

    /**
     * แปลง expire_date เป็น Carbon อัตโนมัติ
     * 👉 ทำให้เราใช้ diffInDays ได้เลย ไม่ต้อง parse เอง
     * 👉 ช่วยลดโหลด CPU (เร็วขึ้น)
     */
    protected $casts = [
        'expire_date' => 'date',
    ];

    /**
     * ความสัมพันธ์: ใบขับขี่เป็นของ user คนไหน
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * คำนวณจำนวนวันที่เหลือก่อนหมดอายุ
     */
    public function getDaysLeftAttribute()
    {
        /**
         * ใช้ static เพื่อให้ "วันนี้" ถูกคำนวณแค่ครั้งเดียวต่อ request
         * 👉 ถ้ามี 100 records จะไม่ต้องเรียก Carbon 100 ครั้ง
         */
        static $today;

        $today ??= Carbon::today();

        /**
         * diffInDays(false)
         * - ถ้ายังไม่หมดอายุ → ได้ค่าบวก
         * - ถ้าหมดอายุแล้ว → ได้ค่าติดลบ
         *
         * +1 เพื่อให้นับรวม "วันนี้"
         * เช่น เหลือ 0 วัน → แสดงเป็น 1 วัน
         */
        return (int) $today->diffInDays($this->expire_date, false) + 1;
    }

    /**
     * กำหนดสถานะใบขับขี่ (ใช้ควบคุมสีใน UI)
     */
    public function getStatusAttribute()
    {
        /**
         * ใช้ค่าที่คำนวณไว้แล้ว
         * 👉 ไม่ต้องคำนวณซ้ำ (ประหยัด performance)
         */
        $days = $this->days_left;

        // 🔴 หมดอายุแล้ว
        if ($days < 0) return 'expired';

        // 🔴 เหลือไม่เกิน 3 วัน (อันตราย)
        if ($days <= 3) return 'danger';

        // 🟡 เหลือไม่เกิน 15 วัน (เตือน)
        if ($days <= 15) return 'warning';

        // 🟢 มากกว่า 15 วัน (ปลอดภัย)
        return 'safe';
    }
}

