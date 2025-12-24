// AdminProductReviews.js
import React, { useEffect, useState } from "react";
import { useParams, useNavigate } from "react-router-dom";
import axios from "axios";

const AdminProductReviews = () => {
  const { id } = useParams(); // product ID from URL
  const navigate = useNavigate();

  const [product, setProduct] = useState(null);
  const [loading, setLoading] = useState(true);
  const [deleting, setDeleting] = useState(false);

  // Fetch product with reviews
  const fetchProduct = () => {
    setLoading(true);
    axios
      .get(`http://127.0.0.1:8000/api/products/${id}`)
      .then((res) => {
        setProduct(res.data.product);
        setLoading(false);
      })
      .catch(() => {
        alert("Product not found");
        navigate(-1);
      });
  };

  useEffect(() => {
    fetchProduct();
  }, [id, navigate]);

  if (loading) return <div className="text-center mt-5">Loading...</div>;

  // Render stars
  const renderStars = (rating) => {
    const fullStars = Math.floor(rating);
    const emptyStars = 5 - fullStars;
    return (
      <>
        {"★".repeat(fullStars)}
        {"☆".repeat(emptyStars)}
      </>
    );
  };

  // Delete a review
  const handleDeleteReview = async (reviewId) => {
  if (!window.confirm("Are you sure you want to delete this review?")) return;

  try {
    await axios.delete(`http://127.0.0.1:8000/api/products/${id}/reviews/${reviewId}`);
    alert("Review deleted successfully!");
    fetchProduct(); // refresh reviews
  } catch (err) {
    console.error(err);
    alert("Failed to delete review");
  }
};


  return (
    <div className="container mt-4">
      <button className="btn btn-secondary mb-3" onClick={() => navigate(-1)}>
        Back
      </button>

      <h2>{product.name} - Reviews</h2>
      <p>
        Average Rating:{" "}
        <span style={{ color: "#FFA534" }}>
          {renderStars(product.average_rating)} ({product.average_rating?.toFixed(1)} / 5)
        </span>
      </p>
      <p>Total Reviews: {product.ratings?.length || 0}</p>

      {product.ratings?.length > 0 ? (
        <div className="table-responsive">
          <table className="table table-bordered table-striped">
            <thead className="table-dark">
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Rating</th>
                <th>Review</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              {product.ratings.map((r, index) => (
                <tr key={r.id}>
                  <td>{index + 1}</td>
                  <td>{r.guest_name || "User"}</td>
                  <td style={{ color: "#FFA534" }}>
                    {renderStars(r.rating)} ({r.rating})
                  </td>
                  <td>{r.review || "-"}</td>
                  <td>
                    <button
                      className="btn btn-sm btn-danger"
                      disabled={deleting}
                      onClick={() => handleDeleteReview(r.id)}
                    >
                      Delete
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      ) : (
        <p>No reviews yet</p>
      )}
    </div>
  );
};

export default AdminProductReviews;
