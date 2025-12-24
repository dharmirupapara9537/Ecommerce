<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
class CategoryController extends Controller
{
   public function index()
    {
        $categories = Category::all();
        return view('frontend.index', compact('categories'));
    }
    // Show all products of a category
     public function show($id)
    {
        $category = Category::findOrFail($id); // single category
        
         $products = $category->products()
                 ->with('images') // eager load images
                 ->where('status', 1)
                 ->paginate(12);
   
        return view('frontend.category_products', compact('category', 'products'));
    }
  
}
