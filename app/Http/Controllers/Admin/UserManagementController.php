<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount('ads');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function ban(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Cannot ban an admin.');
        }

        $user->update(['is_banned' => !$user->is_banned]);

        $status = $user->is_banned ? 'banned' : 'unbanned';
        return back()->with('success', "User {$status}.");
    }
}
