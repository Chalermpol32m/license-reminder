<?php

namespace App\Http\Controllers;

use App\Models\License;

class LicenseController extends Controller
{

public function gallery()
{

$licenses = License::latest()->get();

return view('licenses.gallery',compact('licenses'));

}

}