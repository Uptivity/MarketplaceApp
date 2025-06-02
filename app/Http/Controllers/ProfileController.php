<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $profile = Auth::user()->profile;

        return view('profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'business_name' => 'nullable|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'business_license_number' => 'nullable|string|max:255',
            'incorporation_details' => 'nullable|string',
        ]);

        $profile = Auth::user()->profile;
        $profile->update($request->all());

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}
