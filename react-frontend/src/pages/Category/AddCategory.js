import React, { useState } from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";

const AddCategory = () => {
  const [name, setName] = useState("");
  const [status, setStatus] = useState("1");
  const [image, setImage] = useState(null);
  const [message, setMessage] = useState("");

  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();

    // Prepare form data
    const formData = new FormData();
    formData.append("name", name);
    formData.append("status", status);
    if (image) {
      formData.append("image", image);
    }

    // Get token from localStorage
    const token = localStorage.getItem("admin_token");

    try {
      await axios.post(
        "http://localhost:8000/api/categories",
        formData,
        {
          headers: {
            "Content-Type": "multipart/form-data",
             "Accept": "application/json",
            Authorization: `Bearer ${token}`,
          },
        }
      );

      navigate("/admin/categories", {
        state: { message: "Category added successfully!" },
      });
    } catch (error) {
      console.error(error);
      setMessage(
        error.response?.data?.message ||
          "Unauthorized or something went wrong!"
      );
    }
  };

  return (
    <div className="container mt-4">
      <h3>Add New Category</h3>

      {message && (
        <div className="alert alert-danger mt-3">{message}</div>
      )}

      <form onSubmit={handleSubmit} encType="multipart/form-data">
        {/* Category Name */}
        <div className="form-group mb-3">
          <label>Category Name</label>
          <input
            type="text"
            className="form-control"
            value={name}
            onChange={(e) => setName(e.target.value)}
            placeholder="Enter category name"
            required
          />
        </div>

        {/* Image */}
        <div className="form-group mb-3">
          <label>Image</label>
          <input
            type="file"
            className="form-control"
            onChange={(e) => setImage(e.target.files[0])}
            accept="image/*"
          />
        </div>

        {/* Status */}
        <div className="form-group mb-3">
          <label>Status</label>
          <select
            className="form-control"
            value={status}
            onChange={(e) => setStatus(e.target.value)}
          >
            <option value="1">Active</option>
            <option value="0">Inactive</option>
          </select>
        </div>

        <button type="submit" className="btn btn-primary">
          Save Category
        </button>
      </form>
    </div>
  );
};

export default AddCategory;
