import React, { useEffect, useState } from "react";
import axios from "axios";
import { useNavigate, useLocation } from "react-router-dom";
import Pagination from "../../components/Pagination"; // adjust path if needed

const ProductList = () => {
  const [products, setProducts] = useState([]);
  const [categoriesList, setCategoriesList] = useState([]);
  const [search, setSearch] = useState("");
  const [statusFilter, setStatusFilter] = useState("");
  const [categoryFilter, setCategoryFilter] = useState("");
  const [currentPage, setCurrentPage] = useState(1);
  const [lastPage, setLastPage] = useState(1);
  const [message, setMessage] = useState("");
  const navigate = useNavigate();
  const location = useLocation();

  // Fetch categories once
  const fetchCategories = async () => {
    try {
      const res = await axios.get("http://127.0.0.1:8000/api/categories");
      setCategoriesList(res.data.data || res.data || []);
    } catch (err) {
      console.error("Error fetching categories:", err);
      setCategoriesList([]);
    }
  };

  // Fetch products with filters
  const fetchProducts = async (
    page = 1,
    searchTerm = "",
    status = "",
    categoryId = ""
  ) => {
    try {
      const res = await axios.get(
        `http://127.0.0.1:8000/api/products?page=${page}&search=${searchTerm}&status=${status}&category_id=${categoryId}`
      );
      setProducts(res.data.data);
      setCurrentPage(res.data.current_page);
      setLastPage(res.data.last_page);
    } catch (err) {
      console.error("Error fetching products:", err);
      setProducts([]);
    }
  };

  // Initial load
  useEffect(() => {
    fetchCategories();
    fetchProducts();
    if (location.state?.message) {
      setMessage(location.state.message);
      navigate(location.pathname, { replace: true, state: {} });
      setTimeout(() => setMessage(""), 3000);
    }
  }, [location, navigate]);

  // Handle search
  const handleSearch = (e) => {
    e.preventDefault();
    fetchProducts(1, search, statusFilter, categoryFilter);
  };

  // Delete product
  const handleDelete = async (id) => {
    if (!window.confirm("Are you sure you want to delete this product?")) return;
    try {
      const res = await axios.delete(`http://127.0.0.1:8000/api/products/${id}`);
      setMessage(res.data.message);
      setProducts(products.filter((p) => p.id !== id));
      setTimeout(() => setMessage(""), 3000);
    } catch (err) {
      console.error(err);
      alert("Failed to delete product");
    }
  };

  // Set primary image
  const handleSetPrimary = async (imageId) => {
    try {
      await axios.put(`http://127.0.0.1:8000/api/product-images/${imageId}/set-primary`);
      fetchProducts(currentPage, search, statusFilter, categoryFilter);
    } catch (err) {
      console.error(err);
      alert("Failed to set primary image");
    }
  };

  // Delete single image
  const handleDeleteImage = async (imageId) => {
    if (!window.confirm("Are you sure you want to delete this image?")) return;
    try {
      await axios.delete(`http://127.0.0.1:8000/api/product-images/${imageId}`);
      fetchProducts(currentPage, search, statusFilter, categoryFilter);
    } catch (err) {
      console.error(err);
      alert("Failed to delete image");
    }
  };

  return (
    <div className="container mt-4">
      {/* Header */}
      <div className="d-flex justify-content-between align-items-center mb-3">
        <h4 className="text-primary mb-0">All Products</h4>
        <button
          className="btn btn-primary"
          onClick={() => navigate("/admin/add-product")}
        >
          <i className="fa fa-plus me-2"></i> Add Product
        </button>
      </div>

      {/* Success Message */}
      {message && (
        <div className="alert alert-success alert-dismissible fade show" role="alert">
          {message}
          <button type="button" className="btn-close" onClick={() => setMessage("")}></button>
        </div>
      )}

      {/* Search & Filters */}
      <form className="mb-3" onSubmit={handleSearch}>
        <div className="d-flex align-items-center" style={{ gap: "10px", flexWrap: "wrap" }}>
          {/* Category filter */}
          <select
            className="form-select"
            value={categoryFilter}
            onChange={(e) => {
              const selected = e.target.value;
              setCategoryFilter(selected);
              fetchProducts(1, search, statusFilter, selected);
            }}
            style={{ maxWidth: "180px", height: "36px" }}
          >
            <option value="">All Categories</option>
            {Array.isArray(categoriesList) &&
              categoriesList.map((cat) => (
                <option key={cat.id} value={cat.id}>{cat.name}</option>
              ))
            }
          </select>

          {/* Status filter */}
          <select
            className="form-select"
            value={statusFilter}
            onChange={(e) => {
              const selected = e.target.value;
              setStatusFilter(selected);
              fetchProducts(1, search, selected, categoryFilter);
            }}
            style={{ maxWidth: "150px", height: "36px" }}
          >
            <option value="">All Status</option>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
          </select>

          {/* Name search */}
          <input
            type="text"
            className="form-control"
            placeholder="Search by name..."
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            style={{ maxWidth: "200px", height: "36px" }}
          />

          <button className="btn btn-primary" type="submit" style={{ height: "46px" }}>
            Search
          </button>
        </div>
      </form>

      {/* Product Table */}
      <div className="card shadow">
        <div className="card-body table-responsive">
          <table className="table table-bordered table-striped align-middle">
            <thead className="table-dark">
              <tr>
                <th>Name</th>
                <th>SKU</th>
                <th>Price</th>
                <th>Status</th>
                <th>Images</th>
                <th>View Review</th>
                <th>Actions</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              {products.length > 0 ? (
                products.map((p) => (
                  <tr key={p.id}>
                    <td>{p.name}</td>
                    <td>{p.sku}</td>
                    <td>₹{p.price}</td>
                    <td>
                      <span className={`badge ${p.status ? "bg-success" : "bg-secondary"}`}>
                        {p.status ? "Active" : "Inactive"}
                      </span>
                    </td>

                    {/* Images */}
                    <td className="product-img-cell">
                      {p.images && p.images.length > 0 ? (
                        <div className="product-image-grid">
                          {p.images.map((img) => (
                            <div key={img.id} className="text-center">
                              <img
                                src={`http://127.0.0.1:8000/storage/products/${img.image_path}`}
                                alt={p.name}
                                width="70"
                                height="70"
                                className="rounded"
                                style={{ objectFit: "cover" }}
                              />
                              <div className="mt-1 d-flex flex-column" style={{ gap: "3px" }}>
                                {img.is_primary ? (
                                  <span className="badge bg-success">Primary</span>
                                ) : (
                                  <button
                                    className="btn btn-sm btn-outline-primary"
                                    style={{ fontSize: "10px" }}
                                    onClick={() => handleSetPrimary(img.id)}
                                  >
                                    Set Primary
                                  </button>
                                )}
                                <button
                                  className="btn btn-sm btn-outline-danger"
                                  style={{ fontSize: "10px" }}
                                  onClick={() => handleDeleteImage(img.id)}
                                >
                                  Delete
                                </button>
                              </div>
                            </div>
                          ))}
                        </div>
                      ) : (
                        <span className="text-muted">No Image</span>
                      )}
                    </td>

                    {/* Actions */}
     
<td>
                      <button
                        className="btn btn-sm btn-info"
                        onClick={() => navigate(`/admin/product/${p.id}/reviews`)}
                      >
                        <i className="fa fa-star me-1"></i>View
                      </button>
                    </td>
                                       <td>
                      <button
                       className="btn btn-sm btn-warning me-2"
                         onClick={() => navigate(`/admin/edit-product/${p.id}`)}
                      >
                        <i className="fa fa-edit me-1"></i>Edit
                      </button>
</td>
                    <td>
                      <button
                        className="btn btn-sm btn-danger"
                        onClick={() => handleDelete(p.id)}
                      >
                        <i className="fa fa-trash me-1"></i>Delete
                      </button>
                    </td>
                  </tr>
                ))
              ) : (
                <tr>
                  <td colSpan="7" className="text-center text-muted">
                    No products found.
                  </td>
                </tr>
              )}
            </tbody>
          </table>

          {/* Pagination */}
          {lastPage > 1 && (
            <Pagination
              currentPage={currentPage}
              lastPage={lastPage}
              onPageChange={(page) =>
                fetchProducts(page, search, statusFilter, categoryFilter)
              }
            />
          )}
        </div>
      </div>
    </div>
  );
};

export default ProductList;
