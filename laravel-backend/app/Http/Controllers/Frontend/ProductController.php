<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
class ProductController extends Controller
{
    public function show($alias)
    {
        $product = Product::with('categories', 'images')
        ->where('alias', $alias)
        ->firstOrFail();

    return view('frontend.productdetail', compact('product'));
    }
    // Display the search page
    public function searchPage()
    {
        return view('frontend.search'); // Blade file
    }

    // AJAX live search
    public function ajaxSearch(Request $request)
    {
        $query = $request->input('query');

        if(!$query) {
            return response()->json([]);
        }

        $products = Product::with('primaryImage') // Assumes relation for main image
            ->where('name', 'LIKE', "%{$query}%")
            ->take(10)
            ->get();

        $results = $products->map(function($product){
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->primaryImage 
                            ? asset('storage/products/'.$product->primaryImage->image_path)
                            : 'https://via.placeholder.com/150'
            ];
        });

        return response()->json($results);
    }
     public function products(Request $request)
   {
             $products = Product::with('images') // eager load images
                 ->where('status', 1)
                 ->paginate(12);
   
        return view('frontend.products', compact('products'));
   }
}

