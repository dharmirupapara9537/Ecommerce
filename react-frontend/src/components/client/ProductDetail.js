import React, { useEffect, useState } from "react";
import { useParams, useNavigate } from "react-router-dom";
import axios from "axios";

const ProductDetail = () => {
  const { id } = useParams();
  const navigate = useNavigate();

  const [product, setProduct] = useState(null);
  const [averageRating, setAverageRating] = useState(0);

  // Review form state
  const [guestName, setGuestName] = useState("");
  const [rating, setRating] = useState(5);
  const [review, setReview] = useState("");
  const [submitting, setSubmitting] = useState(false);

  // Fetch product
  useEffect(() => {
    axios
      .get(`http://127.0.0.1:8000/api/products/${id}`)
      .then((res) => {
        setProduct(res.data.product);
        setAverageRating(Number(res.data.average_rating || 0));
      })
      .catch(() => {
        alert("Product not found");
        navigate(-1);
      });
  }, [id, navigate]);

  // Add to cart
  const handleAddToCart = () => {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    const existing = cart.find((i) => i.id === product.id);

    if (existing) {
      existing.quantity += 1;
    } else {
      cart.push({ ...product, quantity: 1 });
    }

    localStorage.setItem("cart", JSON.stringify(cart));
    navigate("/cart");
  };

  // Submit review
  const submitReview = () => {
    if (!guestName) {
      alert("Please enter your name");
      return;
    }

    setSubmitting(true);

    axios
      .post(`http://127.0.0.1:8000/api/products/${id}/rate`, {
        guest_name: guestName,
        rating,
        review,
      })
      .then((res) => {
        setAverageRating(Number(res.data.average_rating));
        setProduct({
          ...product,
          ratings: [...product.ratings, res.data.rating],
        });

        setGuestName("");
        setRating(5);
        setReview("");
        setSubmitting(false);
      })
      .catch(() => {
        alert("Failed to submit review");
        setSubmitting(false);
      });
  };

  if (!product) return <div className="text-center mt-5">Loading...</div>;

  const categoryNames =
    product.categories?.map((c) => c.name).join(", ") || "N/A";

  // ⭐ Render stars with half-star support
  const renderStars = (rating) => {
    const fullStars = Math.floor(rating);
    const halfStar = rating - fullStars >= 0.5 ? 1 : 0;
    const emptyStars = 5 - fullStars - halfStar;

    return (
      <>
        {"★".repeat(fullStars)}
        {halfStar ? "⯪" : ""} {/* ⯪ represents half star */}
        {"☆".repeat(emptyStars)}
      </>
    );
  };

  return (
    <div className="container mt-4">
      <button className="btn btn-secondary mb-3" onClick={() => navigate(-1)}>
        ← Back
      </button>

      <div className="row">
        <div className="col-md-6">
          {product.images?.length > 0 && (
            <img
              src={`http://127.0.0.1:8000/storage/products/${product.images[0].image_path}`}
              className="img-fluid rounded"
              alt={product.name}
            />
          )}
        </div>

        <div className="col-md-6">
          <h2>{product.name}</h2>
          <p>Category: {categoryNames}</p>
          <h4>₹{Number(product.price).toFixed(2)}</h4>

          {/* ⭐ Rating */}
          <div className="mb-3">
            <span style={{ color: "#FFA534", fontSize: "20px", marginRight: "6px" }}>
              {renderStars(averageRating)}
            </span>
            <span className="text-muted">{averageRating.toFixed(1)} / 5</span>
          </div>

          <button
            className="btn btn-success mb-3"
            onClick={handleAddToCart}
          >
            🛒 Add to Cart
          </button>
        </div>
      </div>

      {/* Reviews */}
      <hr />
      <h4>Customer Reviews</h4>

      {product.ratings?.length > 0 ? (
        product.ratings.map((r) => (
          <div key={r.id} className="border p-2 mb-2 rounded">
            <strong>{r.guest_name || "User"}</strong>
            <div>⭐ {r.rating}/5</div>
            <p>{r.review}</p>
          </div>
        ))
      ) : (
        <p>No reviews yet</p>
      )}

      {/* Give Review */}
      <hr />
      <h4>Give a Review</h4>

      <input
        type="text"
        className="form-control mb-2"
        placeholder="Your Name"
        value={guestName}
        onChange={(e) => setGuestName(e.target.value)}
      />

      <select
        className="form-select mb-2"
        value={rating}
        onChange={(e) => setRating(Number(e.target.value))}
      >
        {[5, 4, 3, 2, 1].map((n) => (
          <option key={n} value={n}>
            {"★".repeat(n)} ({n})
          </option>
        ))}
      </select>

      <textarea
        className="form-control mb-2"
        rows="3"
        placeholder="Write review (optional)"
        value={review}
        onChange={(e) => setReview(e.target.value)}
      />

      <button
        className="btn btn-primary"
        disabled={submitting}
        onClick={submitReview}
      >
        {submitting ? "Submitting..." : "Submit Review"}
      </button>
    </div>
  );
};

export default ProductDetail;
