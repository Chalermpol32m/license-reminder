<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeliveryJob;
use App\Models\Driver;
use App\Imports\JobsImport;
use Maatwebsite\Excel\Facades\Excel;

class DeliveryJobController extends Controller
{
    /**
     * แสดงรายการงานขนส่งทั้งหมด
     */
    public function index()
{
    if (app()->isLocal()) {
        // เอาไว้ debug ตอน dev
        logger('ตอนนี้อยู่ LOCAL');
    }

    if (app()->isProduction()) {
        // production อาจจะ log เงียบๆ หรือไม่ทำอะไร
    }

    // โหลด driver มาด้วย (ลด query)
    $jobs = DeliveryJob::with('driver')->latest()->get();

    return view('jobs.index', compact('jobs'));
}

    
    /**
     * แสดงฟอร์มสร้างงาน
     */
    public function create()
    {
        $drivers = Driver::all();

        return view('jobs.create', compact('drivers'));
    }

    /**
     * บันทึกงานขนส่งใหม่
     */
    public function store(Request $request)
{
    // 🔴 STEP 7: VALIDATION (ใส่ตรงนี้เลย)
    $request->validate([
        'customer' => 'required',
        'destination' => 'required',
        'delivery_date' => 'required',

        // 🔹 distance ต้องเป็นตัวเลข และห้ามติดลบ
        'distance' => 'nullable|numeric|min:0'
    ]);

    // 🔹 ถ้า validation ผ่าน → จะมาทำต่อด้านล่าง
    DeliveryJob::create([
        'customer' => $request->customer,
        'destination' => $request->destination,
        'delivery_date' => $request->delivery_date,
        'driver_id' => $request->driver_id,
        'status' => 'pending',

        // 🔹 รับค่าระยะทางจาก form
        'distance' => $request->distance
    ]);

    // 🔹 redirect กลับพร้อมแจ้งเตือน
    return redirect('/jobs')->with('success', 'เพิ่มงานเรียบร้อย');
}

    /**
     * จัดคนขับอัตโนมัติ (ใช้ drivers table)
     */
   public function autoAssign()
{
    // 🔹 ดึง "งานที่ยังไม่ได้จัดคนขับ" (status = pending)
    $jobs = DeliveryJob::where('status', 'pending')->get();

    // 🔸 ถ้าไม่มีงานที่ต้องจัด → กลับไปหน้า jobs พร้อมแจ้งเตือน
    if ($jobs->isEmpty()) {
        return redirect('/jobs')->with('success', 'ไม่มีงานที่ต้องจัดรถ');
    }

    // 🔹 ดึง "คนขับทั้งหมด" จากตาราง drivers
    $drivers = Driver::all();

    // 🔸 ถ้ายังไม่มีคนขับในระบบ → แจ้ง error
    if ($drivers->isEmpty()) {
        return redirect('/jobs')->with('error', 'ยังไม่มีคนขับในระบบ');
    }

    // 🔁 วนลูปทีละงาน เพื่อ assign คนขับ
    foreach ($jobs as $index => $job) {

        // 🔹 ใช้ modulo (%) เพื่อ "วนคนขับ"
        // เช่น มี 3 คนขับ → 0,1,2,0,1,2,...
        $driver = $drivers[$index % $drivers->count()];

        // 🔹 อัปเดตงาน:
        // - ใส่ driver_id (คนขับ)
        // - เปลี่ยนสถานะเป็น assigned
        $job->update([
            'driver_id' => $driver->id,
            'status' => 'assigned'
        ]);
    }

    // ✅ เสร็จแล้ว → กลับไปหน้า jobs พร้อมแจ้งสำเร็จ
    return redirect('/jobs')->with('success', 'จัดคนขับอัตโนมัติเรียบร้อย');
}

//นำเข้างานจาก excel
public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv'
    ]);

    Excel::import(new JobsImport, $request->file('file'));

    return back()->with('success', 'นำเข้า Excel สำเร็จแล้ว 🔥');
}
}
