
@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h3>Add Product</h3>

    {{-- Add product Form --}}
    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
         <div class="row mb-3">
    <label for="categories" class="col-sm-2 col-form-label">Select Category</label>
    <div class="col-sm-9">
   <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror">
        <option value="">-- Select Category --</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}"   {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
            </option>
        @endforeach
    </select>
    @error('category_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
</div>
</div>
        <div class="row mb-3">
            <label for="name" class="col-sm-2 col-form-label">Product Name</label>
            <div class="col-sm-9">
            <input type="text" id="name" name="name"
                   value="{{ old('name') }}" class="form-control " >
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
             </div>
        </div>
        <div class="row mb-3">
            <label for="name" class="col-sm-2 col-form-label">SKU</label>
            <div class="col-sm-9">
            <input type="text" id="sku" name="sku"
                   value="{{ old('sku') }}" class="form-control " >
                    @error('sku')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
             </div>
        </div>
            <div class="row mb-3">
            <label for="name" class="col-sm-2 col-form-label">Alias</label>
            <div class="col-sm-9">
            <input type="text" id="alias" name="alias"
                   value="{{ old('alias') }}" class="form-control " >
                    @error('alias')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
             </div>
        </div>
        <div class="row mb-3">
            <label for="name" class="col-sm-2 col-form-label">Price</label>
            <div class="col-sm-9">
            <input type="number" id="price" name="price"
                   value="{{ old('price') }}" class="form-control " >
                    @error('price')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
             </div>
        </div>
        <div class="row mb-3">
            <label for="name" class="col-sm-2 col-form-label">Regular Price</label>
            <div class="col-sm-9">
            <input type="number" id="regularprice" name="regular_price"
                   value="{{ old('regular_price') }}" class="form-control " >
                    @error('regular_price')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
             </div>
        </div>
        <div class="row mb-3">
            <label for="image" class="col-sm-2 col-form-label">Product Image</label>
            <div class="col-sm-9">
           <input type="file" name="images[]" id="images" class="form-control-file" multiple>
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
        <a href="{{ route('product.index') }}" class="btn btn-secondary">Back</a>
                            </div>
                        </div>
        
    </form>
</div>
@endsection
