import React, { useState, useEffect } from "react";
import axios from "axios";
import { useNavigate,useLocation  } from "react-router-dom";
import Pagination from "../../components/Pagination"; // adjust path if needed
const CategoryList = () => {
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(true);
  const location = useLocation();
   const [search, setSearch] = useState("");
  const [currentPage, setCurrentPage] = useState(1);
  const [lastPage, setLastPage] = useState(1);
const [message, setMessage] = useState("");
      const navigate = useNavigate(); 
const [statusFilter, setStatusFilter] = useState("");

  const fetchCategories = async (page = 1, searchTerm = "", status = "") => {
  try {
    const res = await axios.get(
      `http://localhost:8000/api/categories?page=${page}&search=${searchTerm}&status=${status}`
    );
    setCategories(res.data.data);
    setCurrentPage(res.data.current_page);
    setLastPage(res.data.last_page);
    setLoading(false);
  } catch (err) {
    console.error("Error fetching categories:", err);
    setLoading(false);
  }
};


  useEffect(() => {
    fetchCategories();
        // ✅ show success message if redirected
    if (location.state?.message) {
      setMessage(location.state.message);
      // clear message from state so it doesn't show again
      navigate(location.pathname, { replace: true, state: {} });
    }
  }, []);

  const handleSearch = (e) => {
    e.preventDefault();
    fetchCategories(1, search,statusFilter); // search resets to page 1
  };

  const handlePageChange = (page) => {
    fetchCategories(page, search);
  };
  const handleAddCategory = () => {
    navigate("/admin/add-category"); // 👈 redirect to Add Category page
  };

 // ✅ Delete category
  const handleDelete = async (id) => {
  if (window.confirm("Are you sure you want to delete this category?")) {
    try {
      const res = await axios.delete(`http://localhost:8000/api/categories/${id}`);
          setMessage(res.data.message); // show success message
      setCategories(categories.filter(cat => cat.id !== id)); // remove deleted category from state
      setTimeout(() => setMessage(""), 3000); // hide message after 3 seconds
    } catch (err) {
      console.error(err);
      alert("Error deleting category");
    }
  }
};

  if (loading) return <p>Loading categories...</p>;

  return (
    <div className="container mt-4">
       
      <div className="d-flex justify-content-between align-items-center mb-3">
        <h4 className="text-primary mb-0">All Categories</h4>
        <button className="btn btn-primary" onClick={handleAddCategory}>
          <i className="fa fa-plus me-2"></i> Add Category
        </button>
      </div>
      {message && (
        <div className="alert alert-success alert-dismissible fade show" role="alert">
          {message}
          <button
            type="button"
            className="btn-close"
            onClick={() => setMessage("")}
          ></button>
        </div>
      )}
      
        <div className="card shadow">
          <div className="card-body">
            <div className="table-responsive">
                {/* Search */}
      <form className="mb-3" onSubmit={handleSearch}>
        <div className="input-group">
          <input
    type="text"
    className="form-control me-2"
    placeholder="Search by name..."
    value={search}
    onChange={(e) => setSearch(e.target.value)}
    style={{ maxWidth: "180px", height: "36px" }}
  />
          <select
    className="form-select me-2"
    value={statusFilter}
    onChange={(e) => setStatusFilter(e.target.value)}
    style={{ maxWidth: "150px", height: "36px" }}
  >
    <option value="">All Status</option>
    <option value="1">Active</option>
    <option value="0">Inactive</option>
  </select>
          <button className="btn btn-primary" type="submit"  style={{ height: "36px" }} >
            Search
          </button>
        </div>
      </form>
             <table className="table table-bordered table-striped align-middle">
  <thead className="table-dark">
    <tr>
      <th>Name</th>
      <th>Image</th>
      <th>Status</th>
      <th>Action</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    {categories.length === 0 ? (
      <tr>
        <td colSpan="5" className="text-center text-muted py-4">
          No categories found.
        </td>
      </tr>
    ) : (
      categories.map((cat) => (
        <tr key={cat.id}>
          <td>{cat.name}</td>
          <td>
            {cat.image ? (
              <img
                src={
                  cat.image.startsWith("http")
                    ? cat.image
                    : `http://localhost:8000/storage/categories/${cat.image}`
                }
                alt={cat.name}
                width="70"
                height="70"
                className="rounded"
                style={{ objectFit: "cover" }}
              />
            ) : (
              <span className="text-muted">No Image</span>
            )}
          </td>
          <td>
            <span
              className={`badge ${
                cat.status === 1 || cat.status === "Active"
                  ? "bg-success"
                  : "bg-secondary"
              }`}
            >
              {cat.status === 1 || cat.status === "Active"
                ? "Active"
                : "Inactive"}
            </span>
          </td>
          <td>
            <button
              className="btn btn-sm btn-warning me-2"
              onClick={() => navigate(`/admin/edit-category/${cat.id}`)}
            >
              <i className="fa fa-edit me-1"></i> Edit
            </button>
          </td>
          <td>
            <button
              className="btn btn-sm btn-danger"
              onClick={() => handleDelete(cat.id)}
            >
              <i className="fa fa-trash me-1"></i> Delete
            </button>
          </td>
        </tr>
      ))
    )}
  </tbody>
</table>

              {/* Pagination */}
<Pagination
  currentPage={currentPage}
  lastPage={lastPage}
  onPageChange={(page) => fetchCategories(page, search)}
/>

                
            </div>
          </div>
        </div>
      
    </div>
  );
};

export default CategoryList;
