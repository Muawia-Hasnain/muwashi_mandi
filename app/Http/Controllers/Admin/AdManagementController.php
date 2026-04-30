<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Ad::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $ads = $query->latest()->paginate(15);

        return view('admin.ads.index', compact('ads'));
    }

    public function approve(Ad $ad)
    {
        $ad->update([
            'status' => 'approved', 
            'rejection_reason' => null,
            'expires_at' => now()->addDays(30)
        ]);
        return back()->with('success', 'Ad approved.');
    }

    public function reject(Request $request, Ad $ad)
    {
        $request->validate(['rejection_reason' => 'nullable|string|max:255']);

        $ad->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason ?? 'Rejected by admin.',
        ]);

        return back()->with('success', 'Ad rejected.');
    }

    public function destroy(Ad $ad)
    {
        foreach ($ad->images as $img) {
            Storage::disk('public')->delete($img->image_path);
        }
        $ad->delete();
        return back()->with('success', 'Ad deleted.');
    }
}
