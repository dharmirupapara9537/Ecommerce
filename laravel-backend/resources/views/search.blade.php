@extends('frontend.layouts.app') <!-- Ashion layout -->

@section('content')
<div class="container my-4">
    <input type="text" id="search-input" class="form-control mb-3" placeholder="Search for products...">

    <div id="search-results" class="row g-3">
        <!-- AJAX results will appear here -->
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){

    $('#search-input').on('keyup', function(){
        let query = $(this).val().trim();

        if(query.length > 0){
            $.get("{{ route('products.ajaxSearch') }}", { query: query }, function(results){
                let html = '';
                if(results.length > 0){
                    results.forEach(function(item){
                        html += `<div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="product__item">
                                        <div class="product__item__pic">
                                            <img src="${item.image}" alt="${item.name}" class="img-fluid">
                                        </div>
                                        <div class="product__item__text mt-2">
                                            <h6>${item.name}</h6>
                                            <h5>₹${item.price}</h5>
                                        </div>
                                    </div>
                                 </div>`;
                    });
                } else {
                    html = '<div class="col-12"><p class="text-center">No products found</p></div>';
                }

                $('#search-results').html(html);
            });
        } else {
            $('#search-results').html('');
        }
    });

});
</script>
@endsection
