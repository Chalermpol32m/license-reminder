<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeliveryJob;
use App\Models\User;

class DeliveryJobController extends Controller
{
    /**
     * แสดงรายการงานขนส่งทั้งหมด
     */
    public function index()
    {
        // ดึงข้อมูลทั้งหมดจาก table delivery_jobs
        $jobs = DeliveryJob::latest()->get();

        // ส่งข้อมูลไปแสดงใน view
        return view('jobs.index', compact('jobs'));
    }


    /**
     * บันทึกงานขนส่งใหม่
     */
   public function store(Request $request)
{
    $request->validate([
        'customer' => 'required',
        'destination' => 'required',
        'delivery_date' => 'required'
    ]);

    DeliveryJob::create([
        'customer' => $request->customer,
        'destination' => $request->destination,
        'delivery_date' => $request->delivery_date,
        'status' => 'pending'
    ]);

    return redirect('/jobs');
}
/**
 * จัดคนขับอัตโนมัติให้กับงานที่ยัง pending
 */
public function autoAssign()
{
    // 1) ดึงงานที่ยังไม่ได้จัดคนขับ
    $jobs = DeliveryJob::where('status','pending')->get();

    if ($jobs->isEmpty()) {
        return redirect('/jobs')->with('success','ไม่มีงานที่ต้องจัดรถ');
    }

    // 2) ดึงคนขับทั้งหมด เรียงจากคนที่วิ่งน้อยสุด
    // ถ้ามี role ให้กรอง ->where('role','driver')
    $drivers = User::orderBy('monthly_km','asc')->get();

    if ($drivers->isEmpty()) {
        return redirect('/jobs')->with('success','ยังไม่มีคนขับในระบบ');
    }

    // 3) วน assign งาน
    foreach ($jobs as $job) {

        // เลือกคนขับที่วิ่งน้อยสุด
        $driver = $drivers->shift();

        if (!$driver) break;

        // assign คนขับให้ job
        $job->update([
            'driver_id' => $driver->id,
            'status' => 'assigned'
        ]);

        // เพิ่ม km สมมติ (ในอนาคตจะมาจาก GPS)
        $driver->monthly_km += 50;
        $driver->save();

        // เอาคนขับกลับไปท้าย list
        $drivers->push($driver);
    }

    return redirect('/jobs')->with('success','จัดรถอัตโนมัติเรียบร้อย');
}
}
