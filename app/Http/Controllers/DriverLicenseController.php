<?php

namespace App\Http\Controllers;

use App\Models\DriverLicense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DriverLicenseController extends Controller
{

public function show($id)
{
    $license = DriverLicense::findOrFail($id);
    return view('licenses.show', compact('license'));
}

public function dashboard()
{
    $licenses = DriverLicense::where('user_id', Auth::id())
        ->orderBy('expire_date', 'asc')
        ->get();

    foreach ($licenses as $license) {
        $expireDate = Carbon::parse($license->expire_date);
        $today = Carbon::today();
        $license->days_left = $today->diffInDays($expireDate, false)+1;
    }

    $total = $licenses->count();
    $expired = $licenses->where('days_left','<',0)->count();

    $alert3 = $licenses->where('days_left','>=',0)->where('days_left','<=',3)->count();
    $alert7 = $licenses->where('days_left','>',3)->where('days_left','<=',7)->count();
    $alert15 = $licenses->where('days_left','>',7)->where('days_left','<=',15)->count();

    $totalNotify = $expired + $alert3 + $alert7 + $alert15;

    $urgentLicenses = $licenses->where('days_left','<=',3)->take(9);

    $monthly = DriverLicense::where('user_id', Auth::id())
        ->selectRaw('EXTRACT(MONTH FROM expire_date) as month, COUNT(*) as total')
        ->groupBy('month')
        ->orderBy('month')
        ->get();

    $trendLicenses = DriverLicense::where('user_id', Auth::id())
        ->whereDate('expire_date', '>=', Carbon::today())
        ->whereDate('expire_date', '<=', Carbon::today()->addDays(30))
        ->orderBy('expire_date')
        ->get();

    $thisMonthLicenses = DriverLicense::where('user_id', Auth::id())
        ->whereMonth('expire_date', Carbon::now()->month)
        ->get();

    return view('dashboard', compact(
        'licenses','total','expired','alert3','alert7','alert15',
        'totalNotify','urgentLicenses','monthly','trendLicenses','thisMonthLicenses'
    ));
}

public function index(Request $request)
{
    $query = DriverLicense::where('user_id', Auth::id());

    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('driver_name', 'like', "%{$request->search}%")
              ->orWhere('plate_number', 'like', "%{$request->search}%")
              ->orWhere('license_number', 'like', "%{$request->search}%");
        });
    }

    if ($request->status == 'danger') {
        $query->whereDate('expire_date', '<=', Carbon::today()->addDays(3));
    } elseif ($request->status == 'warning') {
        $query->whereBetween('expire_date', [
            Carbon::today()->addDays(4),
            Carbon::today()->addDays(15)
        ]);
    } elseif ($request->status == 'safe') {
        $query->whereDate('expire_date', '>', Carbon::today()->addDays(15));
    }

    $query->orderBy('expire_date', $request->sort == 'desc' ? 'desc' : 'asc');

    $licenses = $query->paginate(10)->withQueryString();

    foreach ($licenses as $license) {
        $daysLeft = Carbon::today()->diffInDays(Carbon::parse($license->expire_date), false)+1;

        $license->status = $daysLeft <= 3 ? 'danger' : ($daysLeft <= 15 ? 'warning' : 'safe');
        $license->days_left = $daysLeft;
    }

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
        'expire_date' => 'required',
        'license_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    $imagePath = null;

    if ($request->hasFile('license_image')) {
        $path = Storage::disk('cloudinary')
            ->put('licenses', $request->file('license_image'));

        $imagePath = Storage::disk('cloudinary')->url($path);
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

public function gallery()
{
    $licenses = DriverLicense::where('user_id', Auth::id())->latest()->get();
    return view('licenses.gallery', compact('licenses'));
}

// 🔥 ฟังก์ชันช่วยแปลง path Cloudinary (สำคัญ)
private function getCloudinaryPath($url)
{
    $path = parse_url($url, PHP_URL_PATH);
    $path = explode('/upload/', $path)[1] ?? null;
    return preg_replace('/^v\d+\//', '', $path);
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
            $oldPath = $this->getCloudinaryPath($license->license_image);
            if ($oldPath) {
                Storage::disk('cloudinary')->delete($oldPath);
            }
        }

        // 🔥 อัปใหม่
        $path = Storage::disk('cloudinary')
            ->put('licenses', $request->file('license_image'));

        $license->license_image = Storage::disk('cloudinary')->url($path);
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
    $license = DriverLicense::findOrFail($id);

    if ($license->license_image) {
        $path = $this->getCloudinaryPath($license->license_image);
        if ($path) {
            Storage::disk('cloudinary')->delete($path);
        }
    }

    $license->delete();

    return redirect()->route('licenses.index');
}

}