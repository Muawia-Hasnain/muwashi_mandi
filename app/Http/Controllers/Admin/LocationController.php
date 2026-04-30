<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Tehsil;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $districts = District::withCount('tehsils')->orderBy('name')->get();
        $tehsils = Tehsil::with('district')->orderBy('name')->get();
        return view('admin.locations.index', compact('districts', 'tehsils'));
    }

    // Districts
    public function storeDistrict(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:districts,name|max:100']);
        District::create(['name' => $request->name]);
        return back()->with('success', 'District added successfully.');
    }

    public function destroyDistrict(District $district)
    {
        if ($district->tehsils()->count() > 0) {
            return back()->with('error', 'Cannot delete district that has tehsils.');
        }
        $district->delete();
        return back()->with('success', 'District deleted.');
    }

    // Tehsils
    public function storeTehsil(Request $request)
    {
        $request->validate([
            'district_id' => 'required|exists:districts,id',
            'name' => 'required|string|max:100',
        ]);
        
        Tehsil::create([
            'district_id' => $request->district_id,
            'name' => $request->name,
        ]);
        
        return back()->with('success', 'Tehsil added successfully.');
    }

    public function destroyTehsil(Tehsil $tehsil)
    {
        $tehsil->delete();
        return back()->with('success', 'Tehsil deleted.');
    }
}
