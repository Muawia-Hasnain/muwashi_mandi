<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['user', 'ad'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->paginate(15);
        return view('admin.payments.index', compact('payments'));
    }

    public function approve(Payment $payment)
    {
        $payment->update(['status' => 'approved']);

        $ad = $payment->ad;
        if ($ad) {
            if ($payment->type === 'ad_post' || $payment->type === 'renewal') {
                $ad->update([
                    'status' => 'approved',
                    'expires_at' => now()->addDays(30),
                ]);
            } elseif ($payment->type === 'boost') {
                $ad->update([
                    'is_boosted' => true,
                    'boost_expires_at' => now()->addDays(7),
                ]);
            } elseif ($payment->type === 'featured') {
                $ad->update([
                    'is_featured' => true,
                    'featured_expires_at' => now()->addDays(20),
                ]);
            }
        }

        return back()->with('success', 'Payment approved and action applied!');
    }

    public function reject(Request $request, Payment $payment)
    {
        $payment->update([
            'status' => 'rejected',
            'admin_note' => $request->admin_note,
        ]);

        return back()->with('success', 'Payment rejected.');
    }
}
