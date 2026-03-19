<?php

namespace App\Http\Controllers;

use App\Models\DriverLicense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class DriverLicenseController extends Controller
{
    public function show($id)
    {
        $license = DriverLicense::findOrFail($id);
        return view('licenses.show', compact('license'));
    }

    public function dashboard()
    {
        $today = now();
        $userId = Auth::id();

        $baseQuery = DriverLicense::where('user_id', $userId);

        // 🔥 โหลดข้อมูลหลัก (จำกัดเพื่อความเร็ว)
        $licenses = (clone $baseQuery)
            ->orderBy('expire_date')
            ->limit(50)
            ->get();

        // 🔥 คำนวณสถานะ
        $total = $licenses->count();

        $expired = $licenses->where('expire_date', '<', $today)->count();

        $alert3 = $licenses->whereBetween('expire_date', [
            $today,
            $today->copy()->addDays(3)
        ])->count();

        $alert7 = $licenses->whereBetween('expire_date', [
            $today->copy()->addDays(4),
            $today->copy()->addDays(7)
        ])->count();

        $alert15 = $licenses->whereBetween('expire_date', [
            $today->copy()->addDays(8),
            $today->copy()->addDays(15)
        ])->count();

        $totalNotify = $expired + $alert3 + $alert7 + $alert15;

        // 🔥 รายการเร่งด่วน
        $urgentLicenses = $licenses->filter(fn($l) => $l->days_left <= 3)->take(9);

        // 🔥 รายเดือน
        $monthly = (clone $baseQuery)
            ->selectRaw('EXTRACT(MONTH FROM expire_date) as month, COUNT(*) as total')            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // 🔥 30 วันข้างหน้า
        $trendLicenses = (clone $baseQuery)
            ->whereBetween('expire_date', [$today, $today->copy()->addDays(30)])
            ->orderBy('expire_date')
            ->limit(20)
            ->get();

        // 🔥 เดือนนี้
        $thisMonthLicenses = (clone $baseQuery)
            ->whereMonth('expire_date', $today->month)
            ->get();

        return view('dashboard', compact(
            'licenses',
            'total',
            'expired',
            'alert3',
            'alert7',
            'alert15',
            'totalNotify',
            'urgentLicenses',
            'monthly',
            'trendLicenses',
            'thisMonthLicenses'
        ));
    }

    public function index(Request $request)
    {
        $query = DriverLicense::where('user_id', Auth::id());

        // 🔍 ค้นหา
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('driver_name', 'like', "%{$request->search}%")
                  ->orWhere('plate_number', 'like', "%{$request->search}%")
                  ->orWhere('license_number', 'like', "%{$request->search}%");
            });
        }

        $today = now();

        // 🎯 filter
        if ($request->status == 'danger') {
            $query->whereDate('expire_date', '<=', $today->copy()->addDays(3));
        } elseif ($request->status == 'warning') {
            $query->whereBetween('expire_date', [
                $today->copy()->addDays(4),
                $today->copy()->addDays(15)
            ]);
        } elseif ($request->status == 'safe') {
            $query->whereDate('expire_date', '>', $today->copy()->addDays(15));
        }

        // 🔽 sort
        $query->orderBy('expire_date', $request->sort == 'desc' ? 'desc' : 'asc');

        // 📄 paginate
        $licenses = $query->paginate(10)->withQueryString();

        return view('licenses.index', compact('licenses'));
    }

    public function create()
    {
        return view('licenses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'driver_name' => 'required',
            'license_number' => 'required',
            'plate_number' => 'required',
            'expire_date' => 'required|date',
            'license_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $imagePath = null;

        if ($request->hasFile('license_image')) {
            $result = Cloudinary::uploadApi()->upload(
                $request->file('license_image')->getRealPath(),
                [
                    'folder' => 'licenses',
                    'transformation' => [
                        'width' => 800,
                        'crop' => 'scale'
                    ]
                ]
            );

            $imagePath = $result['secure_url'];
        }

        DriverLicense::create([
            'driver_name' => $request->driver_name,
            'license_number' => $request->license_number,
            'plate_number' => $request->plate_number,
            'expire_date' => $request->expire_date,
            'license_image' => $imagePath,
            'user_id' => Auth::id()
        ]);

        return redirect()->route('licenses.index');
    }

    public function edit($id)
    {
        $license = DriverLicense::where('user_id', Auth::id())->findOrFail($id);
        return view('licenses.edit', compact('license'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'driver_name' => 'required',
            'license_number' => 'required',
            'plate_number' => 'required',
            'expire_date' => 'required|date',
            'license_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $license = DriverLicense::where('user_id', Auth::id())->findOrFail($id);

        if ($request->hasFile('license_image')) {

            // 🔥 ลบรูปเก่า
            if ($license->license_image) {
                try {
                    $publicId = pathinfo(
                        parse_url($license->license_image, PHP_URL_PATH),
                        PATHINFO_FILENAME
                    );

                    Cloudinary::uploadApi()->destroy('licenses/' . $publicId);

                } catch (\Exception $e) {}
            }

            // 🔥 อัปโหลดใหม่
            $result = Cloudinary::uploadApi()->upload(
                $request->file('license_image')->getRealPath(),
                [
                    'folder' => 'licenses',
                    'transformation' => [
                        'width' => 800,
                        'crop' => 'scale'
                    ]
                ]
            );

            $license->license_image = $result['secure_url'];
        }

        $license->update([
            'driver_name' => $request->driver_name,
            'license_number' => $request->license_number,
            'plate_number' => $request->plate_number,
            'expire_date' => $request->expire_date,
        ]);

        return redirect()->route('licenses.index')->with('success', 'แก้ไขเรียบร้อย');
    }

    public function destroy($id)
    {
        $license = DriverLicense::where('user_id', Auth::id())->findOrFail($id);

        if ($license->license_image) {
            try {
                $publicId = pathinfo(
                    parse_url($license->license_image, PHP_URL_PATH),
                    PATHINFO_FILENAME
                );

                Cloudinary::uploadApi()->destroy('licenses/' . $publicId);
            } catch (\Exception $e) {}
        }

        $license->delete();

        return redirect()->route('licenses.index');
    }

    public function gallery()
    {
        $licenses = DriverLicense::where('user_id', Auth::id())
            ->latest()
            ->paginate(24);

        return view('licenses.gallery', compact('licenses'));
    }
}
