import React from "react";
import { useLocation, useNavigate } from "react-router-dom";

const ThankYouPage = () => {
  const { state } = useLocation();
  const navigate = useNavigate();
  const order = state?.order;

  return (
    <div className="container text-center mt-5">
      <h2 className="text-success">🎉 Thank You for Your Order!</h2>
      {order ? (
        <>
          <p>
            <strong>Order Number:</strong> {order.order_number}
          </p>
          <p>
            <strong>Total Amount:</strong> ₹{order.amount}
          </p>
          <p>
            <strong>Payment Method:</strong> {order.payment_method}
          </p>
          {order.transaction_id && (
            <p>
              <strong>Transaction ID:</strong> {order.transaction_id}
            </p>
          )}
        </>
      ) : (
        <p>No order details available.</p>
      )}
      <button className="btn btn-primary mt-3" onClick={() => navigate("/")}>
        Continue Shopping
      </button>
    </div>
  );
};

export default ThankYouPage;
