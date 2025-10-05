<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display the authenticated admin's profile.
     */
    public function index()
    {
        // Retrieve the authenticated admin user using the 'admin' guard.
        $admin = Auth::guard('admin')->user();

        // Return the profile view and pass the admin user data to it.
        return view('admin.profile.index', compact('admin'));
    }
}
