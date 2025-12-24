@extends('frontend.layouts.app')

@section('content')
<div class="container">
    <h4>Stripe Payment</h4>

    <form id="stripe-payment-form">
        <div class="mb-3">
            <label>Cardholder Name</label>
            <input type="text" id="card-holder-name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Card Number</label>
            <div id="card-number" class="form-control"></div>
        </div>

        <div class="row">
            <div class="col mb-3">
                <label>Expiry</label>
                <div id="card-expiry" class="form-control"></div>
            </div>
            <div class="col mb-3">
                <label>CVC</label>
                <div id="card-cvc" class="form-control"></div>
            </div>
        </div>

        <button type="submit" class="btn btn-success btn-lg">Pay Now</button>
    </form>

    <div id="payment-result" class="mt-2 text-danger"></div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
const stripe = Stripe("{{ env('STRIPE_KEY') }}");
const elements = stripe.elements();
const style = { base: { fontSize: '16px', color: '#32325d' } };

// Stripe Elements
const cardNumber = elements.create('cardNumber', { style });
const cardExpiry = elements.create('cardExpiry', { style });
const cardCvc = elements.create('cardCvc', { style });
cardNumber.mount('#card-number');
cardExpiry.mount('#card-expiry');
cardCvc.mount('#card-cvc');

// Handle Stripe Payment
document.getElementById('stripe-payment-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const cardHolderName = document.getElementById('card-holder-name').value;

    const { paymentMethod, error } = await stripe.createPaymentMethod({
        type: 'card',
        card: cardNumber,
        billing_details: { name: cardHolderName }
    });

    if(error){
        document.getElementById('payment-result').innerText = error.message;
        return;
    }

    fetch("{{ route('checkout.payment.process') }}", {
        method:'POST',
        headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':'{{ csrf_token() }}'
        },
        body: JSON.stringify({
            payment_method: paymentMethod.id,
            cart: @json($cart),
            // Add customer details if needed
        })
    })
    .then(res=>res.json())
    .then(data=>{
        if(data.success){
            document.getElementById('payment-result').innerText = '✅ Payment Successful! Order ID: '+data.order_id;
        } else {
            document.getElementById('payment-result').innerText = '❌ Payment Failed!';
        }
    });
});
</script>
@endsection
