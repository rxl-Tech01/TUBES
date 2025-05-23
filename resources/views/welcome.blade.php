<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cake Haven - Premium Cakes & Pastries</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <!-- Inline Styles for Chocolate Theme -->
    <style>
        :root {
            --brown-dark: #4A2C2A; /* Deep chocolate */
            --brown-medium: #6B4E31; /* Medium brown */
            --cream-light: #FFF8E7; /* Creamy background */
            --gold-accent: #D4A017; /* Subtle gold for highlights */
            --text-dark: #2D1E1B; /* Near-black for text */
        }

        body {
            background-color: var(--cream-light);
            color: var(--text-dark);
            font-family: 'Instrument Sans', sans-serif;
        }

        .nav-link {
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .nav-link:hover {
            background-color: var(--brown-medium);
            color: var(--cream-light);
            border-color: var(--brown-dark);
        }

        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .btn-primary {
            background-color: var(--brown-dark);
            color: var(--cream-light);
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover:not([disabled]) {
            background-color: var(--brown-medium);
        }

        .btn-primary:disabled {
            background-color: #a0a0a0;
            cursor: not-allowed;
        }
    </style>
</head>
<body class="flex flex-col min-h-screen p-4 sm:p-6 lg:p-8 items-center">
    <header class="w-full max-w-7xl mx-auto mb-8">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl sm:text-3xl font-bold text-brown-dark">Cake Haven</h1>
            @if (Route::has('login'))
                <nav class="flex items-center gap-4 text-sm">
                    @auth
                        <a
                            href="{{ url('/dashboard') }}"
                            class="nav-link px-5 py-2 border border-brown-dark rounded-md bg-cream-light text-brown-dark hover:bg-brown-medium hover:text-cream-light"
                        >
                            Dashboard
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="nav-link px-5 py-2 border border-transparent rounded-md text-brown-dark hover:bg-brown-medium hover:text-cream-light"
                        >
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                class="nav-link px-5 py-2 border border-brown-dark rounded-md bg-black text-brown-dark hover:bg-brown-medium hover:text-cream-light"
                            >
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </div>
    </header>

    <main class="flex flex-col items-center w-full max-w-7xl mx-auto grow">
        <section class="text-center mb-12">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-brown-dark mb-4">
                Welcome to Cake Haven
            </h1>
            <p class="text-lg sm:text-xl text-brown-medium max-w-3xl mx-auto">
                Savor the artistry of our handcrafted cakes and pastries, made with the finest ingredients and a passion for perfection.
            </p>
        </section>

        <section class="flex-col-3 m-2 gap-6 w-full">
            @forelse($products as $product)
                <div class="product-card bg-cream-light rounded-lg shadow-md overflow-hidden">
                    <img
                        src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/600x400?text=' . urlencode($product->name) }}"
                        alt="{{ $product->name }}"
                        class="w-full h-56 object-cover"
                    >
                    <div class="p-6 ">
                        <h3 class="text-xl font-semibold text-brown-dark">{{ $product->name }}</h3>
                        <p class="text-brown-medium mb-3">{{ Str::limit($product->description, 60) }}</p>
                        <div class="flex items-center justify-between mb-4">
                            <p class="text-brown-dark font-bold">${{ number_format($product->price, 2) }}</p>
                            @if($product->stock > 0)
                                <span class="text-green-700 text-sm">In Stock ({{ $product->stock }})</span>
                            @else
                                <span class="text-red-700 text-sm">Out of Stock</span>
                            @endif
                        </div>
                        <a
                            href="{{ route('products.show', $product) }}"
                            class="btn-primary inline-block w-full text-center px-4 py-2 rounded-md {{ $product->stock == 0 ? 'opacity-50' : '' }}"
                            {{ $product->stock == 0 ? 'disabled' : '' }}
                        >
                            {{ $product->stock > 0 ? 'View Details' : 'Out of Stock' }}
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-10">
                    <p class="text-brown-medium text-lg">No cakes available right now.</p>
                    <p class="text-brown-medium text-sm mt-2">Check back soon for our latest creations!</p>
                </div>
            @endforelse
        </section>

        @if($products->count() > 0)
            <section class="mt-10">
                <a
                    href="{{ route('products.index') }}"
                    class="btn-primary inline-block px-6 py-3 rounded-lg font-medium"
                >
                    View All Cakes
                </a>
            </section>
        @endif
    </main>

    <footer class="w-full max-w-7xl mx-auto mt-12 py-6 text-center text-brown-medium text-sm">
        <p>© {{ date('Y') }} Cake Haven. Crafted with love.</p>
    </footer>
</body>
</html>