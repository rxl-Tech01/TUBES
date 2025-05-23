<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>All Products - Cake Haven</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-amber-50 text-brown-800 min-h-screen">
        <header class="bg-white shadow-sm border-b border-amber-200">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-brown-900 hover:text-brown-700 transition-colors">
                        Cake Haven
                    </a>
                    
                    @if (Route::has('login'))
                        <nav class="flex items-center gap-4 text-sm">
                            @auth
                                <a
                                    href="{{ url('/dashboard') }}"
                                    class="inline-block px-5 py-2 border border-brown-300 hover:border-brown-400 rounded-md text-brown-800 bg-amber-100 hover:bg-amber-200 transition-colors"
                                >
                                    Dashboard
                                </a>
                            @else
                                <a
                                    href="{{ route('login') }}"
                                    class="inline-block px-5 py-2 border border-transparent hover:border-brown-300 rounded-md text-brown-800 hover:bg-amber-100 transition-colors"
                                >
                                    Log in
                                </a>

                                @if (Route::has('register'))
                                    <a
                                        href="{{ route('register') }}"
                                        class="inline-block px-5 py-2 border border-brown-300 hover:border-brown-400 rounded-full text-brown-800 bg-amber-200 hover:bg-amber-300 transition-colors"
                                    >
                                        Register
                                    </a>
                                @endif
                            @endauth
                        </nav>
                    @endif
                </div>
            </div>
        </header>

        <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Breadcrumb -->
            <nav class="mb-8">
                <div class="flex items-center space-x-2 text-sm text-brown-600">
                    <a href="{{ route('home') }}" class="hover:text-brown-800 transition-colors">Home</a>
                    <span>/</span>
                    <span class="text-brown-800">Products</span>
                </div>
            </nav>

            <!-- Page Header -->
            <div class="text-center mb-10">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-brown-900 mb-4">
                    Our Delicious Products
                </h1>
                <p class="text-lg text-brown-700 max-w-2xl mx-auto">
                    Explore our complete collection of freshly baked cakes and pastries, made with love and the finest ingredients.
                </p>
            </div>

            <!-- Products Grid -->
            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($products as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                        <img 
                            src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/600x400?text=' . urlencode($product->name) }}" 
                            alt="{{ $product->name }}" 
                            class="w-full h-48 object-cover"
                        >
                        <div class="p-4">
                            <h3 class="text-xl font-semibold text-brown-900 mb-2">{{ $product->name }}</h3>
                            <p class="text-brown-600 mb-3 text-sm">{{ Str::limit($product->description, 80) }}</p>
                            
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-brown-800 font-bold text-lg">${{ number_format($product->price, 2) }}</p>
                                @if($product->stock > 0)
                                    <span class="text-green-600 text-xs bg-green-100 px-2 py-1 rounded-full">
                                        In Stock ({{ $product->stock }})
                                    </span>
                                @else
                                    <span class="text-red-600 text-xs bg-red-100 px-2 py-1 rounded-full">
                                        Out of Stock
                                    </span>
                                @endif
                            </div>
                            
                            <div class="space-y-2">
                                <a 
                                    href="{{ route('products.show', $product) }}" 
                                    class="inline-block w-full text-center px-4 py-2 bg-brown-600 text-white rounded-md hover:bg-brown-700 transition-colors text-sm"
                                >
                                    View Details
                                </a>
                                
                                @if($product->stock > 0)
                                    <button 
                                        type="button"
                                        onclick="quickAddToCart({{ $product->id }}, '{{ addslashes($product->name) }}')"
                                        class="w-full px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700 transition-colors text-sm"
                                    >
                                        Quick Add to Cart
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16">
                        <div class="bg-white rounded-lg shadow-md p-8 max-w-md mx-auto">
                            <h3 class="text-xl font-semibold text-brown-900 mb-4">No Products Available</h3>
                            <p class="text-brown-600 mb-4">We're currently updating our inventory. Please check back soon for our delicious cakes and pastries!</p>
                            <a href="{{ route('home') }}" class="inline-block px-6 py-2 bg-brown-600 text-white rounded-md hover:bg-brown-700 transition-colors">
                                Back to Home
                            </a>
                        </div>
                    </div>
                @endforelse
            </section>

            @if($products->count() > 0)
                <!-- Product Stats -->
                <div class="mt-12 bg-white rounded-lg shadow-md p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                        <div>
                            <h3 class="text-2xl font-bold text-brown-900">{{ $products->count() }}</h3>
                            <p class="text-brown-600">Total Products</p>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-brown-900">{{ $products->where('stock', '>', 0)->count() }}</h3>
                            <p class="text-brown-600">In Stock</p>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-brown-900">${{ number_format($products->min('price'), 2) }} - ${{ number_format($products->max('price'), 2) }}</h3>
                            <p class="text-brown-600">Price Range</p>
                        </div>
                    </div>
                </div>
            @endif
        </main>

        <footer class="bg-white border-t border-amber-200 mt-16">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-center text-brown-700 text-sm">
                <p>&copy; {{ date('Y') }} Cake Haven. All rights reserved.</p>
            </div>
        </footer>

        <script>
            function quickAddToCart(productId, productName) {
                // Add your cart functionality here
                alert(`Added "${productName}" to cart!`);
                
                // You can add AJAX call here to actually add to cart
                // Example:
                // fetch('/cart/add', {
                //     method: 'POST',
                //     headers: {
                //         'Content-Type': 'application/json',
                //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                //     },
                //     body: JSON.stringify({
                //         product_id: productId,
                //         quantity: 1
                //     })
                // })
                // .then(response => response.json())
                // .then(data => {
                //     if(data.success) {
                //         alert(`Added "${productName}" to cart!`);
                //     }
                // });
            }
        </script>