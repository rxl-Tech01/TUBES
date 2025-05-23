<?php

// app/Http/Controllers/ProductController.php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $products = Product::all();
        return view('welcome', compact('products'));
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Display products for the main page
     */
    public function home()
    {
        // Get featured products or limit to 6 for homepage
        $products = Product::take(6)->get();
        return view('welcome', compact('products'));
    }
}