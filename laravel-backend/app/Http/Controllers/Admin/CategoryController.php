<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       $search = $request->input('search');
        $status = $request->input('status');
        $categories = Category::query()
       ->when($search, fn($query) => $query->where('name','like',"%{$search}%"))
        ->when($status !== null && $status !== '', fn($query) => $query->where('status', $status))
        ->orderBy('id', 'desc')
        ->paginate(2)   // change number of items per page
        ->withQueryString(); // keep search in pagination links

        return view('admin.category.index', compact('categories', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate form
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'status' => 'required|boolean',
        ]);
         // Save category
        $category = new Category();
        $category->name = $request->name;
        $category->status = $request->status;
        // Handle image upload
        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->file('image')->extension();
            $request->file('image')->storeAs('categories', $imageName, 'public');

            // save only filename in DB
            $category->image = $imageName;
        }

        $category->save();
        return redirect()->route('category.index')->with('success', 'Category created!');

        //return redirect()->back()->with('success', 'Category added successfully!');
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
   public function edit(Category $category)
{
    return view('admin.category.edit', compact('category'));
}



    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, $id)
{
    //  Validation
    $request->validate([
        'name' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'status' => 'required|boolean',
    ]);

    //  Find category
    $category = Category::findOrFail($id);

    //  Update name
    $category->name = $request->name;
    $category->status = $request->status;
    //  Handle image update
    if ($request->hasFile('image')) {
        if ($category->image && Storage::disk('public')->exists('categories/'.$category->image)) {
                Storage::disk('public')->delete('categories/'.$category->image);
                }
                if ($request->hasFile('image')) {
                    $imageName = time().'.'.$request->file('image')->extension();
                    $request->file('image')->storeAs('categories', $imageName, 'public');
                    $category->image = $imageName;
                }
    }
    //  Save updates
    $category->save();

    // Redirect back to category list
    return redirect()->route('category.index')->with('success', 'Category updated successfully!');
}
 /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {       
        $category->delete(); //  soft delete
        return redirect()->route('category.index')
                     ->with('success', 'Category moved to trash!');
    }

}
