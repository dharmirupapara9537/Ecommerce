@extends('frontend.layouts.app')

@section('title', 'Home')

@section('content')
<!-- Categories Section Begin -->
@if(isset($categories) && $categories->count())
<section class="categories">
    <div class="container">
        <div class="categories__slider owl-carousel">
            @foreach($categories as $category)
                <div class="categories__item set-bg"
                     data-setbg="{{ asset('storage/categories/'.$category->image) }}">
                    <h5>
                        <a href="{{ route('frontend.category.show', $category->id) }}" >{{ $category->name }}</a>
                    </h5>
                    
                </div>
            @endforeach
        </div>
    
     
    <div class="slider-radio-buttons text-center mt-3">
            @foreach($categories as $category)
                <input type="radio" name="categoryRadio" id="cat{{ $category->id }}">
                <label for="cat{{ $category->id }}"></label>
            @endforeach
        </div>
    </div>
</section>

@endif
<!-- Categories Section End -->
@endsection