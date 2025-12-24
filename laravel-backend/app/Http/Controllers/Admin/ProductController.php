<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        $search = $request->input('search');
        $products = Product::with('primaryImage')->get();
         $products =  Product::with(['images', 'categories']); 
         // Category filter
    if ($request->filled('category_id')) {
        $products->whereHas('categories', function($q) use ($request) {
            $q->where('categories.id', $request->category_id);
        });

   }
    // Search by product 
 $counts = OrderItem::select(
            'product_id',
            DB::raw('COUNT(product_id) as total_orders')
        )
        ->groupBy('product_id')
        ->get()
        ->keyBy('product_id'); // IMPORTANT

  if ($request->filled('search')) {
        $search = $request->search;

        $products->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('sku', 'like', "%{$search}%")
              ->orWhere('price', $search); // exact match for price
        });
    }
    $products = $products->paginate(10)->appends($request->all());
        
         $categories = Category::all();
        return view('admin.product.index', compact('products', 'search','categories','counts'));
    }


    /**
     * Show the form for creating a new resource.
     */
  public function create()
{
    $categories = Category::all(); // fetch categories from DB
    return view('admin.product.create', compact('categories'));
}


    /**
     * Store a newly created resource in storage.
     */
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
         //  Create product
         $product = Product::create([
        'name' => $request->name,
        'sku' => $request->sku,
        'alias' => $alias,        // ✅ must specify column name
        'price' => $request->price,
        'regular_price' => $request->regular_price,
        'status' => $request->status,
    ]);
      // $product = Product::create($request->only(['name','sku',$alias,'price','regular_price','status']));

    // Insert into pivot (product_id + category_id)
    $product->categories()->attach($request->category_id);

    
    // Handle product images
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $index => $file) {
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('storage/products'), $filename);

            $product->images()->create([
                'image_path'      => $filename,
                'is_primary' => $index === 0 ? 1 : 0,
            ]);
        }
    }

    return redirect()->route('product.index')
                     ->with('success','Product Added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
       public function edit($id)
    {
        $product = Product::with('images', 'categories')->findOrFail($id);
        $categories = Category::all();

        return view('admin.product.edit', compact('product', 'categories'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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
        'images'       => 'nullable|array',
        'images.*'     => 'image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

        $alias = Str::slug($request->alias);

    // Make alias unique (ignore current product)
    $count = Product::where('alias', 'like', $alias.'%')
                    ->where('id', '!=', $product->id)
                    ->count();

    if ($count > 0) {
        $alias .= '-'.($count + 1);
    }

    //Update Product Fields
    $product->update([
        'name'          => $request->name,
        'sku'           => $request->sku,
        'alias'         => $alias,
        'price'         => $request->price,
        'regular_price' => $request->regular_price,
        'status'        => $request->status,
    ]);
    
     //  Sync Category (pivot table)
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
    return redirect()->route('product.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
    $product->delete(); //  soft delete

    return redirect()->route('product.index')->with('success', 'Product deleted successfully.');
    }

    // Set primary
public function setPrimaryImage($imageId)
{
    $image = ProductImage::findOrFail($imageId);
    ProductImage::where('product_id', $image->product_id)->update(['is_primary' => 0]);
    $image->is_primary = 1;
    $image->save();

    return back()->with('success', 'Primary image updated.');
}

// Delete image
public function deleteImage($imageId)
{
    $image = ProductImage::findOrFail($imageId);
$filePath = 'products/' . $image->image_path;
    // Delete file from storage
     if (Storage::disk('public')->exists($filePath)) {
        Storage::disk('public')->delete($filePath);
    }


    $image->delete();

    return back()->with('success', 'Image deleted.');
}
}
