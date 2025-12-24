
@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h3 align='center'>All Products</h3>
   <div class="d-flex justify-content-between align-items-center mb-3">
    <!-- Left side: Add Category button -->
    <a href="{{ route('product.create') }}" class="btn btn-primary btn-sm">+ Add Product</a>

    <!-- Right side: Search form -->
     <form method="GET" action="{{ route('product.index') }}" class="form-inline mb-3">
    <select name="category_id" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
        <option value="">Search Category</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" 
                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>

        <input type="text" name="search" value="{{ $search }}" 
                class="form-control form-control-sm mr-2"
               style="width: 150px;" placeholder="Search...">
                {{-- Filter by status (boolean) --}}
    
        <button type="submit" class="btn btn-primary btn-sm">Search</button>
    </form>
</div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
   

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                
                <th>Name</th>
                <th>SKU</th>
                <th>Alias</th>
                <th>Price</th>
                <th>Total orders</th>
                <th>Images</th>
                
                <th>Status</th>
                <th>Action</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                   
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->alias }}</td>
                    <td>{{ $product->price }}</td>


    <td>
        {{ $counts[$product->id]->total_orders ?? 0 }}
    </td>
               <td>
                       <div class="d-flex flex-wrap">
                            @foreach($product->images as $image)
                                <div class="text-center mr-2 mb-2" style="width:80px;">
                                    <img src="{{ asset('storage/products/'.$image->image_path) }}" 
                                         width="60" height="60" class="img-thumbnail mb-1">
                                    
                                    @if($image->is_primary)
                                        <span class="badge badge-success d-block mb-1">Primary</span>
                                    @else
                                        <form action="{{ route('product.setPrimaryImage', $image->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="badge badge-info border border-none d-inline-block mb-1">Set Primary</button>
                                        </form>
                                    @endif
                                    
                                    <!-- Optional: Delete Image -->
                                    <form action="{{ route('product.deleteImage', $image->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm py-0 px-2">Delete</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </td>
                    <td>
                        @if($product->status)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td><a href="{{ route('product.edit', $product->id) }}" class="btn btn-warning btn-sm py-0 px-2">Edit</a>

                    </td>
                    <td>
                         <form action="{{ route('product.destroy', $product->id) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm py-0 px-2" 
                        onclick="return confirm('Are you sure you want to delete this product?')">
                        Delete
                    </button>
                </form>
                    </td>
                    
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">No Products Found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-3">
    {{ $products->links() }}
    </div>
</div>
@endsection
