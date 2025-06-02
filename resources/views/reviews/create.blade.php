@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Write a Review</h1>
        <p class="mt-1 text-gray-600">for {{ $product->title }}</p>
    </div>

    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
        <div class="p-6">
            <form action="{{ route('reviews.store', $product) }}" method="POST">
                @csrf
                
                @if($order)
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                @endif

                <div class="mb-6">
                    <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
                    <div class="mt-1 flex items-center" id="star-rating">
                        @for ($i = 1; $i <= 5; $i++)
                            <button type="button" data-rating="{{ $i }}" class="star-btn p-1 focus:outline-none">
                                <svg class="w-8 h-8 text-gray-300 hover:text-yellow-400" 
                                     fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </button>
                        @endfor
                        <input type="hidden" name="rating" id="rating" value="{{ old('rating', 5) }}">
                    </div>
                    @error('rating')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror                </div>

                <div class="mb-6">
                    <label for="comment" class="block text-sm font-medium text-gray-700">Your Review</label>
                    <div class="mt-1">
                        <textarea id="comment" name="comment" rows="4" 
                                  class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full border-gray-300 rounded-md"
                                  placeholder="Share your experience with this product">{{ old('comment') }}</textarea>
                    </div>
                    @error('comment')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end">
                    <a href="{{ url()->previous() }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                        Submit Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stars = document.querySelectorAll('.star-btn');
        const ratingInput = document.getElementById('rating');
        
        // Initialize with the current rating value
        updateStars(parseInt(ratingInput.value) || 5);
        
        // Add click event to each star
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = parseInt(this.getAttribute('data-rating'));
                ratingInput.value = rating;
                updateStars(rating);
            });
        });
        
        function updateStars(rating) {
            stars.forEach(star => {
                const starRating = parseInt(star.getAttribute('data-rating'));
                const starIcon = star.querySelector('svg');
                
                if (starRating <= rating) {
                    starIcon.classList.remove('text-gray-300');
                    starIcon.classList.add('text-yellow-400');
                } else {
                    starIcon.classList.remove('text-yellow-400');
                    starIcon.classList.add('text-gray-300');
                }
            });
        }
    });
</script>
@endsection
