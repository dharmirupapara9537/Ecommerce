<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Str;
use App\Models\Rating;

class ProductController extends Controller
{
     public function __construct()
    {
        $this->middleware(['auth:api', 'admin'])->only([
            'store',
            'update',
            'destroy',
        ]);
    }
    /* public function clientsindex()
    {
        // Public client view — only active products
        $products = Product::with(['categories', 'images' => function ($q) {
            $q->where('is_primary', 1);
        }])->where('status', 1)->get();

        return response()->json($products);
    }*/
   public function clientsindex(Request $request)
{
$query = Product::with(['categories', 'images', 'ratings'])
        ->where('status', 1);
    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    $products = $query->get()->map(function ($product) {
        $product->primary_image = $product->images->where('is_primary', 1)->first();
          $product->average_rating = $product->averageRating();
        $product->ratings_count = $product->ratings->count();
        return $product;
    });

    return response()->json($products);
}

public function searchProducts(Request $request)
{
    $search = $request->query('search');

    if (!$search || trim($search) === '') {
        return response()->json([
            'message' => 'Search term is required.'
        ], 400);
    }

    $products = Product::with(['categories', 'images', 'ratings'])
        ->where('status', 1)
        ->where(function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('alias', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
        })
        ->get()
        ->map(function ($product) {

            // 🖼 Primary Image
            $product->primary_image = $product->images
                ->where('is_primary', 1)
                ->first();

            // ⭐ Rating Data
            $product->average_rating = $product->averageRating();
            $product->ratings_count = $product->ratings->count();

            return $product;
        });

    return response()->json($products);
}


    // List products with filters
    public function index(Request $request)
    {
        $query = Product::with(['categories', 'images']);

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        if ($request->status !== null && $request->status !== "") {
            $query->where('status', $request->status);
        }
          if ($request->has('category_id') && $request->category_id != '') {
        $query->whereHas('categories', function($q) use ($request) {
            $q->where('categories.id', $request->category_id);
        });
    }

        

        $products = $query->orderBy('id', 'desc')->paginate(5);
        return response()->json($products);
    }

    // Show single product
  // Show single product with ratings
public function show($id)
{
    $product = Product::with(['categories', 'images', 'ratings'])->findOrFail($id);

    return response()->json([
        'product' => $product,
        'average_rating' => (float) $product->averageRating()
    ]);
}


    // Create product
    public function store(Request $request)
    {
        $request->validate([
        'name'        => 'required|string|max:255',
        'sku'         => 'required|unique:products',
        'alias'       => 'required|string|max:255|unique:products,alias',
        'price'       => 'required|numeric',
        'regular_price'=> 'nullable|numeric',
        'status'      => 'required|boolean',
        'category_id' => 'required|exists:categories,id',
        'images'      => 'nullable|array',
        'images.*'    => 'image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);
 $alias = Str::slug($request->alias);
          // Make alias unique
        $count = Product::where('alias', 'like', $alias.'%')->count();
        if ($count > 0) {
            $alias .= '-'.($count + 1);
        }
            // Create product
        $product = Product::create([
            'name' => $request->name,
            'sku' => $request->sku,
            'alias' => $alias,
            'price' => $request->price,
            'regular_price' => $request->regular_price,
            'status' => $request->status,
        ]);


        // Attach categories
        $product->categories()->attach($request->category_id);

        
    // Handle product images
   if ($request->hasFile('images')) {
        $newPrimary = $request->input('primary_image');
        foreach ($request->file('images') as $index => $file) {
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('storage/products'), $filename);
         //   $isPrimary = ($newPrimary == $file->getClientOriginalName()) ? true : false;
  // if ($isPrimary) {
             //   ProductImage::where('product_id', $product->id)->update(['is_primary' => false]);
           // }

            $product->images()->create([
                'image_path'      => $filename,
                'is_primary' => $index === 0 ? 1 : 0,
            ]);
        }
    }

        
    return response()->json(['message' => 'Product added successfully']);
    }

    // Update product
    public function update(Request $request, $id)
{
     $product = Product::findOrFail($id);
         $request->validate([
        'name'         => 'required|string|max:255',
        'sku'          => 'required|unique:products,sku,' . $product->id,
        'alias'        => 'required|string|max:255|unique:products,alias,' . $product->id,
        'price'        => 'required|numeric',
        'regular_price'=> 'nullable|numeric',
        'status'       => 'required|boolean',
        'category_id'  => 'required|exists:categories,id',
       // 'images'       => 'nullable|array',
      //  'images.*'     => 'image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

$product->update([
        'name'          => $request->name,
        'sku'           => $request->sku,
        'alias'         => $request->alias,
        'price'         => $request->price,
        'regular_price' => $request->regular_price,
        'status'        => $request->status,
    ]);
    
$product->categories()->sync([$request->category_id]);
    // Handle New Images
      if ($request->hasFile('images')) {
        $newPrimary = $request->input('primary_image');
        foreach ($request->file('images') as $index => $file) {
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('storage/products'), $filename);
            $isPrimary = ($newPrimary == $file->getClientOriginalName()) ? true : false;
   if ($isPrimary) {
                ProductImage::where('product_id', $product->id)->update(['is_primary' => false]);
            }

            $product->images()->create([
                'image_path'      => $filename,
                'is_primary' => $isPrimary,
            ]);
        }
    }
    return response()->json(['message' => 'Product updated successfully','product'=>$product]);
}

    // Delete product (soft delete)
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['message'=>'Product deleted successfully']);
    }

    // Delete single image
    public function deleteImage($id)
    {
        $image = ProductImage::findOrFail($id);
            // Prepend folder path
    $filePath = 'products/' . $image->image_path;

    // Check and delete from storage
    if (\Storage::disk('public')->exists($filePath)) {
        \Storage::disk('public')->delete($filePath);
    }

    
        $image->delete();
        return response()->json(['message'=>'Image deleted successfully']);
    }

    // Set primary image
    public function setPrimaryImage($id)
    {
        $image = ProductImage::findOrFail($id);
        $image->product->images()->update(['is_primary'=>0]);
        $image->is_primary = 1;
        $image->save();
        return response()->json(['message'=>'Primary image set successfully']);
 
    }


    public function rateProduct(Request $request, $id)
{
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'review' => 'nullable|string|max:1000',
        'guest_name' => 'required_without:user_id|string|max:255'
    ]);

    $product = Product::findOrFail($id);

    $rating = $product->ratings()->create([
        'user_id' => auth()->id() ?? null,
        'guest_name' => $request->guest_name,
        'rating' => $request->rating,
        'review' => $request->review
    ]);

    return response()->json([
        'message' => 'Rating submitted successfully',
        'rating' => $rating,
        'average_rating' => $product->averageRating()
    ]);
}
// DELETE /api/products/{product}/reviews/{review}
public function destroyRating($productId, $ratingId)
{
    $rating = Rating::where('product_id', $productId)->find($ratingId);

    if (!$rating) {
        return response()->json(['message' => 'Rating not found'], 404);
    }

    $rating->delete();

    return response()->json(['message' => 'Rating deleted successfully']);
}

}
