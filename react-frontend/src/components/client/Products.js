import React, { useEffect, useState } from "react";
import axios from "axios";
import { useNavigate, useLocation } from "react-router-dom";

const ProductList = () => {
  const [products, setProducts] = useState([]);
  const [selectedProduct, setSelectedProduct] = useState(null);
  const navigate = useNavigate();
  const location = useLocation();

  // 🔄 Fetch products on search change
  useEffect(() => {
    fetchProducts();
  }, [location.search]);

  // 📡 Fetch products from API
  const fetchProducts = async () => {
    try {
      const params = new URLSearchParams(location.search);
      const searchTerm = params.get("search") || "";

      const apiUrl = searchTerm
        ? `http://127.0.0.1:8000/api/search-products?search=${encodeURIComponent(
            searchTerm
          )}`
        : `http://127.0.0.1:8000/api/clientproducts`;

      const res = await axios.get(apiUrl);

      setProducts(Array.isArray(res.data) ? res.data : []);
    } catch (error) {
      console.error("❌ Failed to fetch products:", error);
    }
  };

  // ⭐ Render rating stars
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

  // 🖼 Open image gallery
  const handleImageClick = (product) => {
    setSelectedProduct(product);
  };

  const closeModal = () => setSelectedProduct(null);

  // 👁 View product details
  const handleViewProduct = (product) => {
    navigate(`/product/${product.id}`, { state: { product } });
  };

  return (
    <div className="container mt-4">
      <h2 className="mb-4">🛍 Products List</h2>

      <div className="row">
        {products.length > 0 ? (
          products.map((product) => {
            const primaryImage =
              product.images && product.images.length > 0
                ? `http://127.0.0.1:8000/storage/products/${product.images[0].image_path}`
                : "https://via.placeholder.com/300x300?text=No+Image";

            const categoryNames =
              product.categories && product.categories.length > 0
                ? product.categories.map((cat) => cat.name).join(", ")
                : "N/A";

            const avgRating = Number(product.average_rating || 0);
            const ratingCount = product.ratings_count || 0;

            return (
              <div className="col-md-4 mb-4" key={product.id}>
                <div className="card shadow-sm border-0 h-100">
                  {/* Image */}
                  <div
                    style={{
                      height: "220px",
                      overflow: "hidden",
                      cursor: "pointer",
                    }}
                    onClick={() => handleImageClick(product)}
                  >
                    <img
                      src={primaryImage}
                      alt={product.name}
                      className="w-100 h-100"
                      style={{ objectFit: "cover" }}
                    />
                  </div>

                  {/* Content */}
                  <div className="card-body text-center">
                    <h5 className="card-title">{product.name}</h5>

                    <p className="text-muted mb-1">
                      Category: <strong>{categoryNames}</strong>
                    </p>

                    <p className="fw-bold text-success mb-2">
                      ₹{Number(product.price || 0).toFixed(2)}
                    </p>

                    {/* ⭐ Rating */}
                    <div className="mb-3">
                      <span
                        style={{
                          color: " #FFA534",
                          fontSize: "16px",
                          marginRight: "6px",
                        }}
                      >
                        {renderStars(avgRating)}
                      </span>
                      <span className="text-muted">
                        {avgRating.toFixed(1)} ({ratingCount})
                      </span>
                    </div>

                    <button
                      className="btn btn-primary w-100"
                      onClick={() => handleViewProduct(product)}
                    >
                      👁 View Product
                    </button>
                  </div>
                </div>
              </div>
            );
          })
        ) : (
          <p className="text-center text-muted mt-5">No products found.</p>
        )}
      </div>

      {/* 🖼 Image Gallery Modal */}
      {selectedProduct && (
        <div
          onClick={closeModal}
          style={{
            position: "fixed",
            inset: 0,
            background: "rgba(0,0,0,0.8)",
            display: "flex",
            justifyContent: "center",
            alignItems: "center",
            zIndex: 999,
          }}
        >
          <div onClick={(e) => e.stopPropagation()}>
            <h5 className="text-white text-center mb-3">
              {selectedProduct.name}
            </h5>

            <div className="d-flex flex-wrap gap-3 justify-content-center">
              {selectedProduct.images?.map((img, index) => (
                <img
                  key={index}
                  src={`http://127.0.0.1:8000/storage/products/${img.image_path}`}
                  alt=""
                  width="200"
                  height="200"
                  style={{
                    objectFit: "cover",
                    borderRadius: "10px",
                  }}
                />
              ))}
            </div>

            <button
              className="btn btn-light mt-4 d-block mx-auto"
              onClick={closeModal}
            >
              Close
            </button>
          </div>
        </div>
      )}
    </div>
  );
};

export default ProductList;
