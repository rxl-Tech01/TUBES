<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $product->name }} - Cake Haven</title>

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
                    <a href="{{ route('products.index') }}" class="hover:text-brown-800 transition-colors">Products</a>
                    <span>/</span>
                    <span class="text-brown-800">{{ $product->name }}</span>
                </div>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Product Image -->
                <div class="space-y-4">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <img 
                            src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/800x600?text=' . urlencode($product->name) }}" 
                            alt="{{ $product->name }}" 
                            class="w-full h-96 object-cover"
                        >
                    </div>
                </div>

                <!-- Product Info -->
                <div class="space-y-6">
                    <div>
                        <h1 class="text-3xl sm:text-4xl font-bold text-brown-900 mb-4">{{ $product->name }}</h1>
                        <p class="text-lg text-brown-700 leading-relaxed">{{ $product->description }}</p>
                    </div>

                    <div class="flex items-center space-x-4">
                        <p class="text-3xl font-bold text-brown-900">${{ number_format($product->price, 2) }}</p>
                        @if($product->stock > 0)
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                In Stock ({{ $product->stock }} available)
                            </span>
                        @else
                            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">
                                Out of Stock
                            </span>
                        @endif
                    </div>

                    @if($product->stock > 0)
                        <div class="space-y-4">
                            <div class="flex items-center space-x-4">
                                <label for="quantity" class="text-brown-700 font-medium">Quantity:</label>
                                <select 
                                    id="quantity" 
                                    name="quantity" 
                                    class="border border-brown-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brown-500 focus:border-brown-500"
                                >
                                    @for($i = 1; $i <= min(10, $product->stock); $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="flex space-x-4">
                                <button 
                                    type="button"
                                    class="flex-1 bg-brown-600 text-white px-6 py-3 rounded-lg hover:bg-brown-700 transition-colors font-medium"
                                    onclick="addToCart({{ $product->id }})"
                                >
                                    Add to Cart
                                </button>
                                <button 
                                    type="button"
                                    class="flex-1 bg-amber-600 text-white px-6 py-3 rounded-lg hover:bg-amber-700 transition-colors font-medium"
                                    onclick="buyNow({{ $product->id }})"
                                >
                                    Buy Now
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-red-700">This product is currently out of stock. Please check back later!</p>
                        </div>
                    @endif

                    <!-- Product Features -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-brown-900 mb-4">Product Features</h3>
                        <ul class="space-y-2 text-brown-700">
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Freshly baked daily
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Premium quality ingredients
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Perfect for special occasions
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Same-day delivery available
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </main>

        <footer class="bg-white border-t border-amber-200 mt-16">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-center text-brown-700 text-sm">
                <p>&copy; {{ date('Y') }} Cake Haven. All rights reserved.</p>
            </div>
        </footer>

        <script>
            function addToCart(productId) {
                const quantity = document.getElementById('quantity').value;
                // Add your cart functionality here
                alert(`Added ${quantity} item(s) to cart!`);
            }

            function buyNow(productId) {
                const quantity = document.getElementById('quantity').value;
                // Add your buy now functionality here
                alert(`Proceeding to checkout with ${quantity} item(s)!`);
            }
        </script>
    </body>
</html>