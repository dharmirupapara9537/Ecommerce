
@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h3>Add Category</h3>

    {{-- Add Category Form --}}
    <form action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        

        <div class="row mb-3">
            <label for="name" class="col-sm-2 col-form-label">Category Name</label>
            <div class="col-sm-9">
            <input type="text" id="name" name="name"
                   value="{{ old('name') }}" class="form-control " >
                      @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
        </div>
</div>
        <div class="row mb-3">
            <label for="image" class="col-sm-2 col-form-label">Category Image</label>
            <div class="col-sm-9">
            <input type="file" id="image" name="image" class="form-control">
             @error('image')
               <span class="text-danger">{{ $message }}</span>
               @enderror
        </div>
</div>
        <div class="row mb-3">
            <label for="status" class="col-sm-2 col-form-label">Status</label>
            <div class="col-sm-9">
            <select id="status" name="status" class="form-control">
                <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
</div>
 <div class="row">
                            <div class="offset-sm-2 col-sm-9">
                               <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('category.index') }}" class="btn btn-secondary">Back</a>
                            </div>
                        </div>
        
    </form>
</div>
@endsection
