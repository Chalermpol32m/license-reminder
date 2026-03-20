<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::all();
        return view('vehicles.index', compact('vehicles'));
    }

    public function store(Request $request)
    {
        Vehicle::create([
            'plate_number' => $request->plate_number,
            'type' => $request->type,
        ]);

        return redirect()->back()->with('success', 'เพิ่มรถเรียบร้อย');
    }

    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $vehicle->update([
            'plate_number' => $request->plate_number,
            'type' => $request->type,
        ]);

        return redirect()->back()->with('success', 'แก้ไขเรียบร้อย');
    }
}