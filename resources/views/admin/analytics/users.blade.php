@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold mb-2">User Analytics</h1>
        <p class="text-gray-600">Comprehensive data about users, buyers, and sellers</p>
    </div>

    <div class="flex flex-wrap -mx-3 mb-6">
        <!-- User Summary Card -->
        <div class="w-full md:w-1/3 px-3 mb-6">
            <div class="bg-white rounded-lg shadow p-6 h-full">
                <h3 class="text-lg font-medium mb-4 text-gray-900">User Summary</h3>
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <p class="text-gray-500 text-sm">Active Users (30 days)</p>
                        <p class="text-2xl font-bold">{{ $activeUsers }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Monthly Growth</p>
                        <p class="text-2xl font-bold {{ $userGrowth >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $userGrowth >= 0 ? '+' : '' }}{{ number_format($userGrowth, 1) }}%
                        </p>
                    </div>
                </div>
                
                <div class="w-full bg-gray-100 h-1 mb-4"></div>
                
                <div class="text-sm">
                    <p class="font-medium">User Registration Trends</p>
                    <div class="h-40 mt-3">
                        <!-- Chart would be rendered here with JavaScript -->
                        <div id="new-users-chart" class="w-full h-full"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- User Distribution Card -->
        <div class="w-full md:w-1/3 px-3 mb-6">
            <div class="bg-white rounded-lg shadow p-6 h-full">
                <h3 class="text-lg font-medium mb-4 text-gray-900">User Distribution</h3>
                
                @if($usersByRole->isEmpty())
                    <p class="text-gray-500">No user role data available.</p>
                @else
                    <div class="h-48 mb-4">
                        <!-- Chart would be rendered here with JavaScript -->
                        <div id="user-roles-chart" class="w-full h-full"></div>
                    </div>
                    
                    <div class="overflow-y-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider py-2">Role</th>
                                    <th class="text-right text-xs font-medium text-gray-500 uppercase tracking-wider py-2">Count</th>
                                    <th class="text-right text-xs font-medium text-gray-500 uppercase tracking-wider py-2">Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalUsers = $usersByRole->sum('count');
                                @endphp
                                
                                @foreach($usersByRole as $roleData)
                                    <tr>
                                        <td class="py-2 text-sm font-medium text-gray-900">{{ ucfirst($roleData->role) }}</td>
                                        <td class="py-2 text-sm text-gray-500 text-right">{{ $roleData->count }}</td>
                                        <td class="py-2 text-sm text-gray-500 text-right">
                                            {{ number_format(($roleData->count / $totalUsers) * 100, 1) }}%
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Top Buyers Card -->
        <div class="w-full md:w-1/3 px-3 mb-6">
            <div class="bg-white rounded-lg shadow p-6 h-full">
                <h3 class="text-lg font-medium mb-4 text-gray-900">Top Buyers</h3>
                
                @if($topBuyers->isEmpty())
                    <p class="text-gray-500">No buyer data available yet.</p>
                @else
                    <div class="overflow-y-auto max-h-64">
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider py-2">Buyer</th>
                                    <th class="text-right text-xs font-medium text-gray-500 uppercase tracking-wider py-2">Orders</th>
                                    <th class="text-right text-xs font-medium text-gray-500 uppercase tracking-wider py-2">Spent</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topBuyers as $buyer)
                                    <tr>
                                        <td class="py-2 text-sm font-medium text-gray-900">{{ $buyer->name }}</td>
                                        <td class="py-2 text-sm text-gray-500 text-right">{{ $buyer->order_count }}</td>
                                        <td class="py-2 text-sm text-gray-500 text-right">${{ number_format($buyer->total_spent, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="flex flex-wrap -mx-3">
        <!-- Top Sellers -->
        <div class="w-full px-3 mb-6">
            <div class="bg-white rounded-lg shadow p-6 h-full">
                <h3 class="text-lg font-medium mb-4 text-gray-900">Top Sellers by Revenue</h3>
                
                @if($topSellers->isEmpty())
                    <p class="text-gray-500">No seller data available yet.</p>
                @else
                    <div class="h-64 mb-6">
                        <!-- Chart would be rendered here with JavaScript -->
                        <div id="top-sellers-chart" class="w-full h-full"></div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider py-3">Seller</th>
                                    <th class="text-right text-xs font-medium text-gray-500 uppercase tracking-wider py-3">Orders</th>
                                    <th class="text-right text-xs font-medium text-gray-500 uppercase tracking-wider py-3">Total Revenue</th>
                                    <th class="text-right text-xs font-medium text-gray-500 uppercase tracking-wider py-3">Avg. per Order</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($topSellers as $seller)
                                    <tr>
                                        <td class="py-3 text-sm font-medium text-gray-900">{{ $seller->name }}</td>
                                        <td class="py-3 text-sm text-gray-500 text-right">{{ $seller->orders_count }}</td>
                                        <td class="py-3 text-sm text-gray-500 text-right">${{ number_format($seller->total_revenue, 2) }}</td>
                                        <td class="py-3 text-sm text-gray-500 text-right">
                                            ${{ $seller->orders_count > 0 ? number_format($seller->total_revenue / $seller->orders_count, 2) : '0.00' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Only initialize charts if data is available
        @if(isset($newUsers) && count($newUsers) > 0)
        // New Users Chart
        const newUsersCtx = document.getElementById('new-users-chart').getContext('2d');
        new Chart(newUsersCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($newUsers->pluck('formatted_date')->toArray()) !!},
                datasets: [{
                    label: 'New Users',
                    data: {!! json_encode($newUsers->pluck('count')->toArray()) !!},
                    backgroundColor: 'rgba(16, 185, 129, 0.2)',
                    borderColor: 'rgb(16, 185, 129)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
        @endif
        
        @if(isset($usersByRole) && count($usersByRole) > 0)
        // User Roles Chart
        const userRolesCtx = document.getElementById('user-roles-chart').getContext('2d');
        new Chart(userRolesCtx, {
            type: 'pie',
            data: {
                labels: {!! json_encode($usersByRole->pluck('role')->map(function($role) { return ucfirst($role); })->toArray()) !!},
                datasets: [{
                    data: {!! json_encode($usersByRole->pluck('count')->toArray()) !!},
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                    ],
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
        @endif
        
        @if(isset($topSellers) && count($topSellers) > 0)
        // Top Sellers Chart
        const topSellersCtx = document.getElementById('top-sellers-chart').getContext('2d');
        new Chart(topSellersCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($topSellers->pluck('name')->toArray()) !!},
                datasets: [{
                    label: 'Revenue',
                    data: {!! json_encode($topSellers->pluck('total_revenue')->toArray()) !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value;
                            }
                        }
                    }
                }
            }
        });
        @endif
    });
</script>
@endpush
@endsection
