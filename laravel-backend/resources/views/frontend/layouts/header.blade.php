<!-- Header Section Begin -->
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
                        html += `<div class="col-lg-10 col-md-4 col-sm-6">
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

<header class="header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-3 col-lg-2">
                <div class="header__logo">
                    <a href="{{ url('/') }}"><img src="{{ asset('img/logo.jpg') }}"  alt=""></a>
                </div>
            </div>
            <div class="col-xl-6 col-lg-7">
                <nav class="header__menu">
                    <ul>
                        <li class=""><a href="{{ url('/') }}">Home</a></li>
                   
                        <li class=""><a href="{{ route('frontend.products') }}">Products</a></li>
                    </ul>
                </nav>
<div class="header__search position-relative">
    <input type="text" id="search-input" class="form-control" placeholder="Search products...">
</div>
<div class="container">
    <div id="search-results" class="row g-3 mt-2"></div>
</div>



            </div>
            <div class="col-lg-3">
                <div class="header__right">
                  
                    <ul class="header__right__widget">
                         <a href="{{ route('cart.index') }}" class="btn btn-sm btn-primary">
    <i class="fa fa-shopping-cart"></i> Cart
    @if(session('cart'))
        <span class="badge bg-danger">{{ count(session('cart')) }}</span>
    @endif
</a>
                    </ul>
                   
                </div>
            </div>
        </div>
        <div class="canvas__open">
            <i class="fa fa-bars"></i>
        </div>
    </div>
</header>
<!-- Header Section End -->
