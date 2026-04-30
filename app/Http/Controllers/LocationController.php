<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getTehsils($districtId)
    {
        $tehsils = \App\Models\Tehsil::where('district_id', $districtId)->orderBy('name')->get(['id', 'name']);
        return response()->json($tehsils);
    }
}
