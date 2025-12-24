@extends('frontend.layouts.app')

@section('title', 'Home')

@section('content')
<div class="container mt-4">
     <div class="mb-4">
        <a href="{{ url('/') }}" class="site-btn">
            <i class="fa fa-arrow-left"></i> Back
        </a>
    </div>
    <h2 class="mb-4">{{ $category->name }} Products</h2>
    <div class="row">

        @forelse($products as $product)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="product__item">

                    {{-- Primary Image --}}
                    @php
                        $primaryImage = $product->images->where('is_primary', 1)->first();
                    @endphp

                   
                        <img src="{{ asset('storage/products/' . ($primaryImage->image_path ?? 'no-image.png')) }}" 
                             alt="{{ $product->name }}" style="display: block; width: 100%;height: 300px;">
                   

                    {{-- Product Info --}}
                    <div class="product__item__text text-center mt-1">
                        <h6>{{ $product->name }}</h6>
                        <h5>${{ $product->price }}</h5>
                      <a href="{{ route('frontend.product.show', $product->alias) }}" class="btn btn-primary btn-sm">View Product</a>
                    </div>

                    {{-- Optional: thumbnails --}}
                    <div class="d-flex justify-content-center mt-2">
                        @foreach($product->images->where('is_primary', 0) as $img)
                            <img src="{{ asset('storage/products/'.$img->image_path) }}" 
                                 style="width:50px; height:50px; object-fit:cover; margin-right:5px;">
                        @endforeach
                    </div>

                </div>
            </div>
        @empty
            <p>No products found in this category.</p>
        @endforelse

    </div>
</div>
<!-- Pagination -->
 @if ($products->hasPages() || true) {{-- force show --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $products->links() }}
    </div>
@endif

@endsection
