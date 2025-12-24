
@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h3 align='center'>All Categories</h3>
   <div class="d-flex justify-content-between align-items-center mb-3">
    <!-- Left side: Add Category button -->
    <a href="{{ route('category.create') }}" class="btn btn-primary btn-sm">+ Add Category</a>

    <!-- Right side: Search form -->
    <form action="{{ route('category.index') }}" method="GET" class="d-flex">
        <input type="text" name="search" value="{{ $search }}" 
               class="form-control form-control-sm me-2" 
               style="width: 150px;" placeholder="Search...">
                {{-- Filter by status (boolean) --}}
    <select name="status" class="form-control form-control-sm me-2" style="width:120px;">
        <option value="">All Status</option>
        <option value="1" {{ request('status')  === '1' ? 'selected' : '' }}>Active</option>
        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
    </select>
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
                <th>Image</th>
                <th>Status</th>
                <th>Action</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
                <tr>
                   
                    <td>{{ $category->name }}</td>
                    <td>
                        
                        @if($category->image)
                            <img src="{{ asset('storage/categories/'.$category->image) }}" width="80">
                        @else
                            <span>No Image</span>
                        @endif
                    </td>
                    <td>
                        @if($category->status)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td><a href="{{ route('category.edit', $category->id) }}" class="btn btn-warning btn-sm py-0 px-2">Edit</a>

                    </td>
                    <td>
                         <form action="{{ route('category.destroy', $category->id) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm py-0 px-2" 
                        onclick="return confirm('Are you sure you want to delete this category?')">
                        Delete
                    </button>
                </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No Categories Found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-3">
    {{ $categories->links() }}
    </div>
</div>
@endsection
