<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryJob extends Model
{
    // อนุญาตให้ Laravel บันทึก field เหล่านี้
    protected $fillable = [
        'customer',
        'destination',
        'delivery_date',
        'driver_id',
        'vehicle_plate',
        'status',
        'distance'
    ];
    public function driver()
{
    return $this->belongsTo(\App\Models\User::class,'driver_id');
}
}