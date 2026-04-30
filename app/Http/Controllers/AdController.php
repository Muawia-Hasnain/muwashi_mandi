<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\AdImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdController extends Controller
{
    /**
     * Browse all approved ads with filters.
     */
    public function index(Request $request)
    {
        $query = Ad::active()->with(['images', 'user', 'district', 'tehsil']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('district_id')) {
            $query->where('district_id', $request->district_id);
        }
        if ($request->filled('tehsil_id')) {
            $query->where('tehsil_id', $request->tehsil_id);
        }
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('breed', 'like', "%{$search}%");
            });
        }
        if ($request->filled('ad_type')) {
            $query->where('ad_type', $request->ad_type);
        }

        // Prioritize active Featured ads, then active Boosted ads
        $now = now();
        $query->orderByRaw("CASE WHEN is_featured = 1 AND featured_expires_at > ? THEN 1 ELSE 0 END DESC", [$now])
              ->orderByRaw("CASE WHEN is_boosted = 1 AND boost_expires_at > ? THEN 1 ELSE 0 END DESC", [$now]);

        $sortBy = $request->get('sort', 'latest');
        match ($sortBy) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'oldest' => $query->orderBy('created_at', 'asc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        $ads = $query->paginate(12)->withQueryString();
        $districts = \App\Models\District::orderBy('name')->get();
        $categories = \App\Models\Category::where('is_active', true)->get();

        return view('ads.index', compact('ads', 'districts', 'categories'));
    }

    /**
     * Show single ad detail.
     */
    public function show($id, $slug = null)
    {
        $ad = Ad::findOrFail($id);

        // SEO: Permanent redirect if the slug is incorrect or missing
        if ($slug !== $ad->slug) {
            return redirect()->route('ads.show', ['id' => $ad->id, 'slug' => $ad->slug], 301);
        }

        // Allow owner or admin to see non-approved ads
        if ($ad->status !== 'approved' && (!Auth::check() || (Auth::id() !== $ad->user_id && !Auth::user()->isAdmin()))) {
            abort(404);
        }

        $ad->increment('views_count');
        $ad->load(['images', 'user']);

        $relatedAds = Ad::active()
            ->where('category_id', $ad->category_id)
            ->where('id', '!=', $ad->id)
            ->with('images')
            ->take(4)
            ->get();

        return view('ads.show', compact('ad', 'relatedAds'));
    }

    /**
     * Show create ad form.
     */
    public function create()
    {
        $districts = \App\Models\District::orderBy('name')->get();
        $categories = \App\Models\Category::where('is_active', true)->get();
        return view('ads.create', compact('districts', 'categories'));
    }

    /**
     * Store new ad.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:2000',
            'price' => 'required|numeric|min:0|max:99999999.99',
            'category_id' => 'required|exists:categories,id',
            'breed' => 'nullable|string|max:100',
            'age_info' => 'nullable|string|max:100',
            'district_id' => 'required|exists:districts,id',
            'tehsil_id' => 'required|exists:tehsils,id',
            'village' => 'nullable|string|max:100',
            'images' => 'required|array|min:1|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'ad_type' => 'required|in:for_sale,qurbani,ijtamai_hissa',
            'org_name' => 'nullable|string|max:100',
            'cnic_number' => 'nullable|string|max:15',
            'total_hisse' => 'nullable|integer|min:1|max:20',
        ]);

        $user = Auth::user();
        $isPaidAd = $user->hasExceededFreeAdsLimit();
        
        $adData = $validated;
        $adData['status'] = $isPaidAd ? 'payment_pending' : 'pending';
        
        if ($adData['ad_type'] === 'ijtamai_hissa') {
            $category = \App\Models\Category::find($adData['category_id']);
            $type = $category ? $category->slug : 'other';
            $maxHisse = in_array($type, ['goat', 'sheep']) ? 1 : 7;

            if ($request->filled('total_hisse') && $request->total_hisse > $maxHisse) {
                return back()->withInput()->withErrors(['total_hisse' => "Maximum hissas allowed for this animal is {$maxHisse}."]);
            }

            if (!$request->filled('total_hisse')) {
                $adData['total_hisse'] = $maxHisse;
            }
        } else {
            $adData['total_hisse'] = 1; // Not applicable
            $adData['org_name'] = null;
        }
        
        $ad = $user->ads()->create($adData);

        // Handle image uploads with compression
        foreach ($request->file('images') as $index => $imageFile) {
            $filename = uniqid('ad_') . '.webp';
            $path = 'ads/' . $filename;
            
            // Read, Resize, and Compress
            $img = \Intervention\Image\Laravel\Facades\Image::read($imageFile);
            
            // Scale down if too large (max 800px width)
            if ($img->width() > 800) {
                $img->scale(width: 800);
            }

            // Save as WebP with 80% quality
            $encoded = $img->toWebp(80);
            \Illuminate\Support\Facades\Storage::disk('public')->put($path, (string) $encoded);

            $ad->images()->create([
                'image_path' => $path,
                'is_primary' => $index === 0,
                'sort_order' => $index,
            ]);
        }

        if ($isPaidAd) {
            return redirect()->route('payments.create', ['ad_id' => $ad->id, 'type' => 'ad_post'])
                             ->with('info', __('Free limit exceeded msg') ?? 'Free limit exceeded. Please upload payment screenshot (Rs. 50) to activate your ad.');
        }

        return redirect()->route('ads.mine')->with('success', 'Ad posted! It will be visible after admin approval.');
    }

    /**
     * Show edit form.
     */
    public function edit(Ad $ad)
    {
        \Illuminate\Support\Facades\Gate::authorize('update', $ad);
        $ad->load('images');
        $districts = \App\Models\District::orderBy('name')->get();
        $categories = \App\Models\Category::where('is_active', true)->get();
        return view('ads.edit', compact('ad', 'districts', 'categories'));
    }

    /**
     * Update ad.
     */
    public function update(Request $request, Ad $ad)
    {
        \Illuminate\Support\Facades\Gate::authorize('update', $ad);

        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:2000',
            'price' => 'required|numeric|min:0|max:99999999.99',
            'category_id' => 'required|exists:categories,id',
            'breed' => 'nullable|string|max:100',
            'age_info' => 'nullable|string|max:100',
            'district_id' => 'required|exists:districts,id',
            'tehsil_id' => 'required|exists:tehsils,id',
            'village' => 'nullable|string|max:100',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'integer|exists:ad_images,id',
            'ad_type' => 'required|in:for_sale,qurbani,ijtamai_hissa',
            'org_name' => 'nullable|string|max:100',
            'cnic_number' => 'nullable|string|max:15',
            'total_hisse' => 'nullable|integer|min:' . max(1, $ad->booked_hisse) . '|max:20',
        ]);

        $adData = $validated;
        
        if ($adData['ad_type'] === 'ijtamai_hissa') {
            $category = \App\Models\Category::find($adData['category_id']);
            $type = $category ? $category->slug : 'other';
            $maxHisse = in_array($type, ['goat', 'sheep']) ? 1 : 7;

            if ($request->filled('total_hisse') && $request->total_hisse > $maxHisse) {
                return back()->withInput()->withErrors(['total_hisse' => "Maximum hissas allowed for this animal is {$maxHisse}."]);
            }

            if (!$request->filled('total_hisse')) {
                $adData['total_hisse'] = $maxHisse;
            }
        } else {
            $adData['total_hisse'] = 1;
            $adData['org_name'] = null;
        }

        $ad->update($adData);
        
        // Only set to pending if it was approved or rejected.
        // If it was payment_pending, keep it that way.
        if (in_array($ad->status, ['approved', 'rejected', 'expired'])) {
            $ad->update(['status' => 'pending']);
        }

        // Remove selected images
        if ($request->filled('remove_images')) {
            $imagesToRemove = AdImage::whereIn('id', $request->remove_images)
                ->where('ad_id', $ad->id)
                ->get();

            foreach ($imagesToRemove as $img) {
                Storage::disk('public')->delete($img->image_path);
                $img->delete();
            }
        }

        // Add new images with compression
        if ($request->hasFile('images')) {
            $currentCount = $ad->images()->count();
            foreach ($request->file('images') as $index => $imageFile) {
                if ($currentCount + $index >= 5) break;
                
                $filename = uniqid('ad_') . '.webp';
                $path = 'ads/' . $filename;

                $img = \Intervention\Image\Laravel\Facades\Image::read($imageFile);
                if ($img->width() > 800) {
                    $img->scale(width: 800);
                }
                $encoded = $img->toWebp(80);
                \Illuminate\Support\Facades\Storage::disk('public')->put($path, (string) $encoded);

                $ad->images()->create([
                    'image_path' => $path,
                    'is_primary' => $currentCount === 0 && $index === 0,
                    'sort_order' => $currentCount + $index,
                ]);
            }
        }

        return redirect()->route('ads.mine')->with('success', 'Ad updated!');
    }

    /**
     * Delete ad.
     */
    public function destroy(Ad $ad)
    {
        \Illuminate\Support\Facades\Gate::authorize('delete', $ad);

        foreach ($ad->images as $img) {
            Storage::disk('public')->delete($img->image_path);
        }

        $ad->delete();

        return redirect()->route('ads.mine')->with('success', 'Ad deleted successfully.');
    }

    /**
     * Show logged-in user's ads.
     */
    public function myAds()
    {
        $ads = Auth::user()->ads()->with('images')->latest()->paginate(12);
        return view('ads.my-ads', compact('ads'));
    }

    /**
     * Mark ad as sold.
     */
    public function markAsSold(Ad $ad)
    {
        \Illuminate\Support\Facades\Gate::authorize('update', $ad);

        $ad->update(['status' => 'sold']);

        return back()->with('success', 'Congratulations! Your animal is marked as SOLD.');
    }

    /**
     * Return phone number (AJAX, auth only).
     */
    public function showPhone(Ad $ad)
    {
        return response()->json(['phone' => $ad->user->phone]);
    }
}
