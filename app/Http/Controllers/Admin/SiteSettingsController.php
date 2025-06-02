<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SiteSettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'navbar_color' => SiteSetting::get('navbar_color', '#3b82f6'),
            'button_color' => SiteSetting::get('button_color', '#3b82f6'),
            'accent_color' => SiteSetting::get('accent_color', '#10b981'),
        ];
        
        return view('admin.settings.index', compact('settings'));
    }
    
    public function update(Request $request)
    {
        $validated = $request->validate([
            'navbar_color' => 'required|string|max:7', // Hex color
            'button_color' => 'required|string|max:7',
            'accent_color' => 'required|string|max:7',
        ]);
        
        foreach ($validated as $key => $value) {
            SiteSetting::set($key, $value);
        }
        
        return redirect()->route('admin.settings.index')->with('success', 'Site settings updated successfully');
    }
}
