import React, { useEffect, useState } from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";
import { loadStripe } from "@stripe/stripe-js";
import {
  Elements,
  CardElement,
  useStripe,
  useElements,
} from "@stripe/react-stripe-js";

// ✅ Stripe Public Key (Publishable)
const stripePromise = loadStripe(
  "pk_test_51S8emw0r3p4y4fhD4doZAlXoajch7SZr2co7eeAgdLx0LC52nNE66H6pbpFvj6OxYCJmM2KQaCAjfVocUFGL2zMV00TCQZIpyL"
);

// ============================
// 💳 STRIPE CARD PAYMENT COMPONENT
// ============================
const CardPayment = ({ cart, formData, onPaymentSuccess }) => {
  const stripe = useStripe();
  const elements = useElements();

  const getTotal = () =>
    cart.reduce((sum, item) => sum + item.price * item.quantity, 0);

  const handleCardPayment = async (e) => {
    e.preventDefault();
    if (!stripe || !elements) return;

    try {
      // 1️⃣ Create PaymentIntent on Laravel backend
      const { data } = await axios.post(
        "http://127.0.0.1:8000/api/create-payment-intent",
        { amount: getTotal() } // Stripe needs amount in paise (for INR)
      );

      const clientSecret = data.clientSecret;

      // 2️⃣ Confirm card payment
      const result = await stripe.confirmCardPayment(clientSecret, {
        payment_method: {
          card: elements.getElement(CardElement),
          billing_details: {
            name: formData.name,
            email: formData.email,
          },
        },
      });

      if (result.error) {
        alert(result.error.message);
      } else if (result.paymentIntent.status === "succeeded") {
        // 3️⃣ Payment successful → parent callback
        onPaymentSuccess(result.paymentIntent.id);
      }
    } catch (error) {
      console.error(error);
   //   alert("❌ Payment failed. Try again.");
    }
  };

  return (
    <form onSubmit={handleCardPayment}>
      <div className="mb-3">
        <label>💳 Enter Card Details</label>
        <div className="border p-3 rounded">
          <CardElement options={{ style: { base: { fontSize: "16px" } } }} />
        </div>
      </div>
      <button
        type="submit"
        className="btn btn-primary w-100 mt-3"
        disabled={!stripe}
      >
        Pay ₹{getTotal()}
      </button>
    </form>
  );
};

// ============================
// 🧾 MAIN CHECKOUT PAGE
// ============================
const CheckoutPage = () => {
  const [cart, setCart] = useState([]);
  const [formData, setFormData] = useState({
    name: "",
    email: "",
    phone: "",
    address: "",
    city: "",
    state: "",
    postal_code: "",
    country: "",
  });
  const [paymentMethod, setPaymentMethod] = useState("COD");
  const navigate = useNavigate();

  // Load cart
  useEffect(() => {
    const storedCart = JSON.parse(localStorage.getItem("cart")) || [];
    if (storedCart.length === 0) navigate("/cart");
    setCart(storedCart);
  }, [navigate]);

  const handleChange = (e) =>
    setFormData({ ...formData, [e.target.name]: e.target.value });

  const getTotal = () =>
    cart.reduce((sum, item) => sum + item.price * item.quantity, 0);

  // 🟢 Handle COD Checkout
  const handleCheckout = async () => {
    try {
      const res = await axios.post("http://127.0.0.1:8000/api/checkout", {
        ...formData,
        cart,
        payment_method: "COD",
        transaction_id: null,
      });

      navigate("/thank-you", {
        state: {
          order: {
           amount: res.data.order.total_amount,
            payment_method: res.data.order.payment_method,
            order_number: res.data.order.order_number, 
          },
        },
      });

      localStorage.removeItem("cart");
    } catch (error) {
  if (error.response && error.response.status === 422) {
    console.log(error.response.data.errors); // validation errors
    alert("Validation failed. Check the form fields.");
  } else {
    console.error(error);
    alert("❌ Failed to place order!");
  }
}
  };

  // 🟣 Handle Stripe Payment Success
  const handleStripeSuccess = async (transactionId) => {
    try {
      const res = await axios.post("http://127.0.0.1:8000/api/checkout", {
        ...formData,
        cart,
        payment_method: "card",
        transaction_id: transactionId,
      });

      navigate("/thank-you", {
        state: {
          order: {
             amount: res.data.order.total_amount,
            payment_method: res.data.order.payment_method,
            transaction_id: transactionId,
            order_number: res.data.order.order_number,
          },
        },
      });

      localStorage.removeItem("cart");
    } catch (error) {
      console.error(error);
      alert("❌ Payment succeeded but order save failed!");
    }
  };

  return (
    <div className="container mt-5">
      <h4 className="mb-4">🧾 Checkout</h4>
      <div className="row">
        {/* ========================== */}
        {/* 🧍 Customer Details */}
        {/* ========================== */}
        <div className="col-md-8">
          <form>
          {[
  "name",
  "email",
  "phone",
  "address",
  "city",
  "state",
  "postal_code",
  "country",
].map((field) => (
  <div className="row mb-3 align-items-center" key={field}>
    <label className="col-md-3 col-form-label text-capitalize">
      {field.replace("_", " ")}
    </label>
    <div className="col-md-9">
      <input
        className="form-control "
        style={{ border: "1px solid black" }}
        name={field}
        value={formData[field]}
        onChange={handleChange}
        required={field !== "email"}
      />
    </div>
  </div>
))}

            {/* ========================== */}
            {/* 💰 Payment Method */}
            {/* ========================== */}
            <div className="mb-3">
              <label className="form-label">Payment Method</label>
              <div>
                <label className="me-3">
                  <input
                    type="radio"
                    value="COD"
                    checked={paymentMethod === "COD"}
                    onChange={(e) => setPaymentMethod(e.target.value)}
                  />{" "}
                  Cash on Delivery
                </label>
                <label>
                  <input
                    type="radio"
                    value="Card"
                    checked={paymentMethod === "Card"}
                    onChange={(e) => setPaymentMethod(e.target.value)}
                  />{" "}
                  Card Payment
                </label>
              </div>
            </div>
          </form>

          {paymentMethod === "COD" && (
            <button className="btn btn-success mt-3" onClick={handleCheckout}>
              Place Order (COD)
            </button>
          )}
        

        {/* ========================== */}
        {/* 💳 Stripe Payment */}
        {/* ========================== */}
        
          {paymentMethod === "Card" && (
            <Elements stripe={stripePromise}>
              <CardPayment
                cart={cart}
                formData={formData}
                onPaymentSuccess={handleStripeSuccess}
              />
            </Elements>
          )}
        </div>
      </div>

      
      <div>&nbsp;</div>
      </div>
  );
};

export default CheckoutPage;
