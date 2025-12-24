@extends('frontend.layouts.app')

@section('title', 'Home')

@section('content')
 @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
      <meta name="csrf-token" content="{{ csrf_token() }}">
         <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div class="container mt-5">
        <div class="mb-4">
        <a href="{{ url()->previous() }}" class="site-btn">
            <i class="fa fa-arrow-left"></i> Back
        </a>
    </div>
    <div class="row">
        <!-- Product Images -->
        <div class="col-md-6">
              @php
                        $primaryImage = $product->images->where('is_primary', 1)->first();
                    @endphp
            @if($product->images->count())
                <img src="{{ asset('storage/products/' . ($primaryImage->image_path ?? 'no-image.png')) }}" 
                     class="img-fluid rounded mb-3" 
                     alt="{{ $product->name }}">

                <div class="d-flex gap-2">
                    @foreach($product->images->where('is_primary', 0) as $img)
                        <img src="{{ asset('storage/products/' . $img->image_path) }}" 
                             class="img-thumbnail" 
                             style="width:60px; height:60px; object-fit:cover;">
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Product Details -->
        <div class="col-md-6">
            <h2>{{ $product->name }}</h2><br>

            <p><strong>Categories:</strong> 
                {{ $product->categories->count() ? $product->categories->pluck('name')->join(', ') : 'No category' }}
            </p>

            <p><strong>Price:</strong> ${{ number_format($product->price,2) }}</p>
            <p>{{ $product->description }}</p>
             <form action="{{ route('cart.add', $product->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-success">Add to Cart</button>
    </form>
                    


             
        </div>
    </div>
</div>

@endsection
