@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6">
    <h1 class="text-2xl font-bold mb-6">Site Settings</h1>
    
    @if (session('success'))
        <div class="bg-green-200 text-green-800 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="bg-white shadow-md rounded p-6">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-6">
                <h2 class="text-lg font-semibold mb-4">Brand Colors</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="navbar_color" class="block text-sm font-medium text-gray-700 mb-2">
                            Navbar Color
                        </label>
                        <div class="flex items-center">
                            <input type="color" id="navbar_color" name="navbar_color" 
                                value="{{ $settings['navbar_color'] }}" 
                                class="h-10 w-16 p-0 border-0" />
                            <input type="text" 
                                value="{{ $settings['navbar_color'] }}" 
                                class="ml-2 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" 
                                aria-label="Navbar color hex value"
                                onchange="document.getElementById('navbar_color').value = this.value" />
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Main navigation bar color</p>
                    </div>
                    
                    <div>
                        <label for="button_color" class="block text-sm font-medium text-gray-700 mb-2">
                            Primary Button Color
                        </label>
                        <div class="flex items-center">
                            <input type="color" id="button_color" name="button_color" 
                                value="{{ $settings['button_color'] }}" 
                                class="h-10 w-16 p-0 border-0" />
                            <input type="text" 
                                value="{{ $settings['button_color'] }}" 
                                class="ml-2 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" 
                                aria-label="Button color hex value"
                                onchange="document.getElementById('button_color').value = this.value" />
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Color for primary buttons</p>
                    </div>
                    
                    <div>
                        <label for="accent_color" class="block text-sm font-medium text-gray-700 mb-2">
                            Accent Color
                        </label>
                        <div class="flex items-center">
                            <input type="color" id="accent_color" name="accent_color" 
                                value="{{ $settings['accent_color'] }}" 
                                class="h-10 w-16 p-0 border-0" />
                            <input type="text" 
                                value="{{ $settings['accent_color'] }}" 
                                class="ml-2 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" 
                                aria-label="Accent color hex value"
                                onchange="document.getElementById('accent_color').value = this.value" />
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Secondary color for highlights and accents</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
    
    <div class="mt-8">
        <h2 class="text-lg font-semibold mb-4">Preview</h2>
        <div class="bg-white shadow-md rounded p-6">
            <div class="mb-4">
                <div class="h-16 rounded" style="background-color: {{ $settings['navbar_color'] }};">
                    <div class="flex items-center h-full px-4">
                        <span class="text-white font-medium">Navigation Bar</span>
                    </div>
                </div>
            </div>
            
            <div class="mb-4 flex space-x-4">
                <button class="px-4 py-2 rounded text-white" 
                    style="background-color: {{ $settings['button_color'] }};">
                    Primary Button
                </button>
                
                <button class="px-4 py-2 rounded text-white" 
                    style="background-color: {{ $settings['accent_color'] }};">
                    Accent Button
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
