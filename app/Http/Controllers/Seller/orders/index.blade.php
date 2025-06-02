@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6">
    <h1 class="text-2xl font-bold mb-6">Orders Received</h1>

    @if ($orders->count())
        <table class="min-w-full bg-white shadow rounded">
            <thead>
                <tr>
                    <th class="py-2 px-4">Order ID</th>
                    <th class="py-2 px-4">Buyer Name</th>
                    <th class="py-2 px-4">Total Price</th>
                    <th class="py-2 px-4">Order Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr class="border-t">
                    <td class="py-2 px-4">{{ $order->id }}</td>
                    <td class="py-2 px-4">{{ $order->user->name ?? 'Unknown' }}</td>
                    <td class="py-2 px-4">${{ number_format($order->total_price, 2) }}</td>
                    <td class="py-2 px-4">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No orders yet.</p>
    @endif
</div>
@endsection
