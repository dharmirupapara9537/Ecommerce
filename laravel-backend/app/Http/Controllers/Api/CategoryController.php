<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
      public function __construct()
    {
        $this->middleware(['auth:api', 'admin'])->only([
            'store',
            'update',
            'destroy',
        ]);
    }
    // Fetch all
   public function index(Request $request)
{
    $search = $request->query();

    $query = Category::query();

  if ($request->search) {
        $query->where('name', 'like', "%{$request->search}%");
    }

    if ($request->status !== null && $request->status !== '') {
        $query->where('status', $request->status);
    }


    $categories = $query->paginate(5); // 5 per page

    return response()->json($categories);
}

    // Create
    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255|unique:categories',
            'status' => 'required|boolean',
            'image'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

         $imageName = null;
        if ($request->hasFile('image')) {
             $imageName = time().'.'.$request->file('image')->extension();
            $request->file('image')->storeAs('categories', $imageName, 'public');
        }

        $category = Category::create([
            'name'   => $request->name,
            'status' => $request->status,
            'image'  => $imageName,
        ]);

        return response()->json([
            'message' => 'Category created successfully',
            'data' => [
                'id' => $category->id,
                'name' => $category->name,
                'image' => $category->image ? url('storage/categories/' . $category->image) : null,
                'status' => $category->status,
            ],
        ], 201);
    }

public function show($id)
{
    $category = Category::find($id);
    if (!$category) {
        return response()->json(['message' => 'Category not found'], 404);
    }
    return response()->json($category);
}
public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'status' => 'required|boolean',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $category = Category::find($id);
    if (!$category) return response()->json(['message' => 'Category not found'], 404);

    $category->name = $request->name;
    $category->status = $request->status;

    if ($request->hasFile('image')) {
        if ($category->image && file_exists(storage_path('app/public/categories/'.$category->image))) {
            unlink(storage_path('app/public/categories/'.$category->image));
        }
        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->storeAs('categories', $imageName, 'public');
        $category->image = $imageName;
    }

    $category->save();

    return response()->json(['message' => 'Category updated successfully', 'category' => $category]);
}


    // Delete
    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }


        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}
