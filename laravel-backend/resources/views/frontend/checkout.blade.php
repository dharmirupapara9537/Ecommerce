@extends('frontend.layouts.app')

@section('content')
<div class="container mt-5">
    <h3>Checkout</h3>

   
    <form id="payment-form" action="{{ route('checkout.process') }}" method="POST">
        @csrf

        {{-- Customer Details --}}
        <input type="text" name="name" placeholder="Full Name" class="form-control mb-2" required>
        <input type="email" name="email" placeholder="Email" class="form-control mb-2" required>
        <input type="text" name="phone" placeholder="Phone" class="form-control mb-2" required>
        <input type="text" name="address" placeholder="Address" class="form-control mb-2" required>

        <div class="row mb-2">
            <div class="col-md-3"><input type="text" name="city" placeholder="City" class="form-control" required></div>
            <div class="col-md-3"><input type="text" name="state" placeholder="State" class="form-control" required></div>
            <div class="col-md-3"><input type="text" name="postal_code" placeholder="Postal Code" class="form-control" required></div>
            <div class="col-md-3"><input type="text" name="country" placeholder="Country (IN)" class="form-control" required></div>
        </div>

        <hr>

        {{-- Payment Method --}}
        <div class="row mb-2">
            <div class="col-md-3"><h5>Payment Method</h5></div>
            <div class="col-md-3">
                <input type="radio" name="payment_method" id="cod" value="cod" checked>
                <label for="cod">Cash on Delivery</label>
            </div>
            <div class="col-md-3">
                <input type="radio" name="payment_method" id="card" value="card">
                <label for="card">Card Payment</label>
            </div>
        </div>

        <hr>

        {{-- Stripe Card Element --}}
        <div id="card-details" style="display:none;">
            <div id="card-element" class="form-control mb-2"></div>
            <div id="card-errors" class="text-danger mt-2" role="alert"></div>
        </div>

        {{-- Hidden input for Stripe PaymentMethod --}}
        <input type="hidden" name="payment_method_id" id="payment_method_id">

        <button type="submit" class="btn btn-success w-100">Place Order</button>
    </form>
</div>

{{-- Stripe JS --}}
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe("{{ config('services.stripe.key') }}");
    const elements = stripe.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#card-element');

    const codRadio = document.getElementById('cod');
    const cardRadio = document.getElementById('card');
    const cardDetails = document.getElementById('card-details');

    function toggleCardDetails() {
        cardDetails.style.display = cardRadio.checked ? 'block' : 'none';
    }

    codRadio.addEventListener('change', toggleCardDetails);
    cardRadio.addEventListener('change', toggleCardDetails);
    toggleCardDetails();

    const form = document.getElementById('payment-form');
    form.addEventListener('submit', async (e) => {
        if(cardRadio.checked){
            e.preventDefault();

            const {paymentMethod, error} = await stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
                billing_details: {
                    name: form.querySelector('input[name="name"]').value,
                    email: form.querySelector('input[name="email"]').value,
                }
            });

            if(error){
                document.getElementById('card-errors').textContent = error.message;
            } else {
                // Set hidden input and submit
                document.getElementById('payment_method_id').value = paymentMethod.id;
                form.submit();
            }
        }
    });
</script>
@endsection
