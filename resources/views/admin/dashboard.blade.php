@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-4 mb-6 rounded-md">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    Some reports may show limited data until more orders are processed through the system. The reports will automatically populate as users place orders.
                </p>
            </div>
        </div>
    </div><!-- Admin Navigation Cards -->
    <div class="mb-8 grid grid-cols-1 md:grid-cols-4 gap-6">
        <a href="{{ route('admin.users.index') }}" class="bg-white rounded-lg shadow p-6 hover:bg-blue-50 transition-colors flex items-center">
            <div class="bg-blue-100 p-3 rounded-full mr-4">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-800">User Management</h2>
                <p class="text-gray-600">Manage website users</p>
            </div>
        </a>
        
        <a href="{{ route('admin.settings.index') }}" class="bg-white rounded-lg shadow p-6 hover:bg-blue-50 transition-colors flex items-center">
            <div class="bg-green-100 p-3 rounded-full mr-4">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Site Settings</h2>
                <p class="text-gray-600">Customize site appearance</p>
            </div>
        </a>
          <a href="{{ route('products.index') }}" class="bg-white rounded-lg shadow p-6 hover:bg-blue-50 transition-colors flex items-center">
            <div class="bg-purple-100 p-3 rounded-full mr-4">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Product Management</h2>
                <p class="text-gray-600">View and manage products</p>
            </div>
        </a>
        
        <a href="{{ route('admin.categories.index') }}" class="bg-white rounded-lg shadow p-6 hover:bg-blue-50 transition-colors flex items-center">
            <div class="bg-yellow-100 p-3 rounded-full mr-4">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Categories</h2>
                <p class="text-gray-600">Manage product categories</p>
            </div>
        </a>
    </div>
    
    <!-- Additional Admin Tools -->
    <div class="mb-8 grid grid-cols-1 md:grid-cols-4 gap-6">
        <a href="{{ route('admin.reviews.index') }}" class="bg-white rounded-lg shadow p-6 hover:bg-blue-50 transition-colors flex items-center">
            <div class="bg-amber-100 p-3 rounded-full mr-4">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Review Management</h2>
                <p class="text-gray-600">Approve and manage product reviews</p>
            </div>
        </a>
    </div>
    
    <!-- Reports Section -->
    <h2 class="text-xl font-semibold mb-4 text-gray-800">Reports & Analytics</h2>
    <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('admin.analytics.index') }}" class="bg-white rounded-lg shadow p-6 hover:bg-blue-50 transition-colors flex items-center">
            <div class="bg-blue-100 p-3 rounded-full mr-4">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Analytics Dashboard</h2>
                <p class="text-gray-600">Key metrics and performance insights</p>
            </div>
        </a>
        
        <a href="{{ route('admin.analytics.store') }}" class="bg-white rounded-lg shadow p-6 hover:bg-blue-50 transition-colors flex items-center">
            <div class="bg-green-100 p-3 rounded-full mr-4">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Store Analytics</h2>
                <p class="text-gray-600">Performance and sales metrics</p>
            </div>
        </a>
        
        <a href="{{ route('admin.analytics.users') }}" class="bg-white rounded-lg shadow p-6 hover:bg-blue-50 transition-colors flex items-center">
            <div class="bg-purple-100 p-3 rounded-full mr-4">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-800">User Analytics</h2>
                <p class="text-gray-600">Buyer and seller metrics</p>
            </div>
        </a>
    </div>

    <!-- Stats Overview -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Market Overview</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="text-sm text-gray-500 uppercase tracking-wide">Total Users</h3>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['totalUsers']) }}</p>
                <div class="mt-1 text-xs text-gray-500">
                    <span class="font-medium">{{ $stats['buyerCount'] }}</span> Buyers |
                    <span class="font-medium">{{ $stats['sellerCount'] }}</span> Sellers |
                    <span class="font-medium">{{ $stats['adminCount'] }}</span> Admins
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="text-sm text-gray-500 uppercase tracking-wide">Total Products</h3>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['totalProducts']) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="text-sm text-gray-500 uppercase tracking-wide">Total Orders</h3>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['totalOrders']) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="text-sm text-gray-500 uppercase tracking-wide">Total Sales</h3>
                <p class="text-2xl font-bold text-gray-800">${{ number_format($stats['totalSales'], 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Recent Users -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-800">Recent Users</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentUsers as $user)
                <div class="px-6 py-4">
                    <div class="flex justify-between">
                        <div>
                            <p class="font-medium text-gray-800">{{ $user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        </div>
                        <div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 
                                   ($user->role === 'seller' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                            <p class="text-xs text-gray-500 mt-1">{{ $user->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-4 text-gray-500">No recent users found.</div>
                @endforelse
                
                <div class="px-6 py-4 bg-gray-50">
                    <a href="{{ route('admin.users.index') }}" class="text-sm text-blue-600 hover:underline">View all users →</a>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-800">Recent Orders</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentOrders as $order)
                <div class="px-6 py-4">
                    <div class="flex justify-between">
                        <div>
                            <p class="font-medium text-gray-800">Order #{{ $order->id }}</p>
                            <p class="text-sm text-gray-500">{{ $order->user->name ?? 'Unknown User' }}</p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">${{ number_format($order->total_price, 2) }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $order->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-4 text-gray-500">No recent orders found.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
