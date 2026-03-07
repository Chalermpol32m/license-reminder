<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Carbon\Carbon;

class DriverLicense extends Model
{
    use HasFactory;

protected $fillable = [
    'driver_name',
    'license_number',
    'plate_number',
    'expire_date',
    'license_image',
    'user_id'
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

   public function getDaysLeftAttribute()
{
    return (int) Carbon::now()->diffInDays($this->expire_date, false);
}

public function getStatusAttribute()
{
    $days = Carbon::now()->diffInDays($this->expire_date, false);

    if ($days <= 3) {
        return 'danger';   // 🔴 เหลือ 3 วัน
    }

    if ($days <= 7) {
        return 'warning';  // 🟡 เหลือ 7 วัน
    }

    if ($days > 15) {
        return 'safe';     // 🟢 เหลือมากกว่า 15 วัน
    }

    return 'warning';
}
}