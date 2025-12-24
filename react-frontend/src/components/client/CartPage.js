import React, { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";

const CartPage = () => {
  const [cart, setCart] = useState([]);
  const navigate = useNavigate();

  // Load cart from localStorage
  useEffect(() => {
    const storedCart = JSON.parse(localStorage.getItem("cart")) || [];
    setCart(storedCart);
  }, []);

  // Save cart to localStorage whenever it changes
  useEffect(() => {
    localStorage.setItem("cart", JSON.stringify(cart));
  }, [cart]);

  // Remove product from cart
  const handleRemove = (id) => {
    const updatedCart = cart.filter((item) => item.id !== id);
    setCart(updatedCart);
  };

  // Increase quantity
  const handleIncrease = (id) => {
    const updatedCart = cart.map((item) =>
      item.id === id ? { ...item, quantity: item.quantity + 1 } : item
    );
    setCart(updatedCart);
  };

  // Decrease quantity
  const handleDecrease = (id) => {
    const updatedCart = cart
      .map((item) =>
        item.id === id && item.quantity > 1
          ? { ...item, quantity: item.quantity - 1 }
          : item
      )
      .filter((item) => item.quantity > 0);
    setCart(updatedCart);
  };

  // Calculate total
  const getTotal = () => {
    return cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
  };

  // Empty cart UI
  if (cart.length === 0) {
    return (
      <div className="container mt-5 text-center">
        <h4>Your cart is empty 🛒</h4>
        <button className="btn btn-primary mt-3" onClick={() => navigate("/")}>
          Go Back to Products
        </button>
      </div>
    );
  }

  return (
    <div className="container mt-4">
      <h2 className="mb-4">🛍 Your Cart</h2>

      {cart.map((item) => (
        <div key={item.id} className="card mb-3 shadow-sm p-3">
          <div className="row align-items-center">
            <div className="col-md-2">
              <img
                src={`http://127.0.0.1:8000/storage/products/${item.primary_image?.image_path}`}
                alt={item.name}
                className="img-fluid rounded"
              />
            </div>

            <div className="col-md-4">
              <h5>{item.name}</h5>
              <p>₹{item.price}</p>
            </div>

            <div className="col-md-3 text-center">
              <div className="d-flex justify-content-center align-items-center gap-2">
                <button
                  className="btn btn-outline-secondary btn-sm"
                  onClick={() => handleDecrease(item.id)}
                >
                  –
                </button>
                <span className="fw-bold">{item.quantity}</span>
                <button
                  className="btn btn-outline-secondary btn-sm"
                  onClick={() => handleIncrease(item.id)}
                >
                  +
                </button>
              </div>
            </div>

            <div className="col-md-2 text-end">
              <h6>₹{item.price * item.quantity}</h6>
            </div>

            <div className="col-md-1 text-end">
              <button
                className="btn btn-danger btn-sm"
                onClick={() => handleRemove(item.id)}
              >
                ✕
              </button>
            </div>
          </div>
        </div>
      ))}

      <div className="text-end mt-3 border-top pt-3">
        <h4>Total: ₹{getTotal()}</h4>
        <button className="btn btn-success" onClick={() => navigate("/checkout")}>
  Proceed to Checkout
</button>

      </div>
    </div>
  );
};

export default CartPage;
