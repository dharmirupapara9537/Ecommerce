@extends('frontend.layouts.app')

@section('content')
<div class="container mt-5">
    <h3>Stripe Test Payment (No DB)</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form id="payment-form" action="{{ route('stripe.test') }}" method="POST">
        @csrf
        <input type="text" name="name" placeholder="Full Name" class="form-control mb-2" required>
        <input type="email" name="email" placeholder="Email" class="form-control mb-2" required>

        <div id="card-details" class="mb-2">
            <div id="card-element" class="form-control"></div>
            <div id="card-errors" class="text-danger mt-2" role="alert"></div>
        </div>

        <button type="submit" class="btn btn-success w-100">Pay ₹500</button>
    </form>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe("{{ config('services.stripe.key') }}");
    const elements = stripe.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#card-element');

    const form = document.getElementById('payment-form');
    form.addEventListener('submit', async (e) => {
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
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'payment_method_id';
            hiddenInput.value = paymentMethod.id;
            form.appendChild(hiddenInput);
            form.submit();
        }
    });
</script>
@endsection
