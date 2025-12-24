import React, { useState, useEffect } from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";

const AddProduct = () => {
  const [name, setName] = useState("");
  const [sku, setSku] = useState("");
  const [alias, setAlias] = useState("");
  const [price, setPrice] = useState("");
  const [regularPrice, setRegularPrice] = useState("");
  const [status, setStatus] = useState(1);
  const [categoriesList, setCategoriesList] = useState([]);
  const [selectedCategory, setSelectedCategory] = useState("");
  const [images, setImages] = useState([]);
  const [message, setMessage] = useState("");
  const navigate = useNavigate();

  // Fetch categories
  useEffect(() => {
    const fetchCategories = async () => {
      try {
        const res = await axios.get("http://127.0.0.1:8000/api/categories");
        setCategoriesList(res.data.data || res.data || []);
      } catch (err) {
        console.error("Error fetching categories:", err);
      }
    };
    fetchCategories();
  }, []);

  const handleImageChange = (e) => {
    setImages(e.target.files);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    if (!selectedCategory) {
      alert("Please select a category.");
      return;
    }

    const formData = new FormData();
    formData.append("category_id", selectedCategory);
    formData.append("name", name);
    formData.append("sku", sku);
    formData.append("alias", alias);
    formData.append("price", price);
    formData.append("regular_price", regularPrice);
    formData.append("status", status);
    

    for (let i = 0; i < images.length; i++) {
      formData.append("images[]", images[i]);
    }

    try {
      const res = await axios.post("http://localhost:8000/api/products", formData, {
        headers: { "Content-Type": "multipart/form-data" },
      });
      
    navigate("/admin/products", { state: { message: "Product added successfully!" } });

      setTimeout(() => navigate("/products"), 1500);
    } catch (err) {
      console.error(err);
      alert("Failed to add product");
    }
  };

  return (
    <div className="container mt-4">
      <h4 className="text-primary mb-3">Add Product</h4>

      {message && <div className="alert alert-success">{message}</div>}

      <form onSubmit={handleSubmit} encType="multipart/form-data">
        <div className="mb-3">
          <label className="form-label">Category</label>
          <select
            className="form-select"
            value={selectedCategory}
            onChange={(e) => setSelectedCategory(e.target.value)}
            required
          >
            <option value="">Select Category</option>
            {categoriesList.map((cat) => (
              <option key={cat.id} value={cat.id}>{cat.name}</option>
            ))}
          </select>
        </div>
        <div className="mb-3">
          <label className="form-label">Product Name</label>
          <input
            type="text"
            className="form-control"
            value={name}
            onChange={(e) => setName(e.target.value)}
            required
          />
        </div>

        <div className="mb-3">
          <label className="form-label">SKU</label>
          <input
            type="text"
            className="form-control"
            value={sku}
            onChange={(e) => setSku(e.target.value)}
            required
          />
        </div>

        <div className="mb-3">
          <label className="form-label">Alias</label>
          <input
            type="text"
            className="form-control"
            value={alias}
            onChange={(e) => setAlias(e.target.value)}
            required
          />
        </div>

        <div className="mb-3">
          <label className="form-label">Price</label>
          <input
            type="number"
            className="form-control"
            value={price}
            onChange={(e) => setPrice(e.target.value)}
            required
          />
        </div>

        <div className="mb-3">
          <label className="form-label">Regular Price</label>
          <input
            type="number"
            className="form-control"
            value={regularPrice}
            onChange={(e) => setRegularPrice(e.target.value)}
            required
          />
        </div>

        <div className="mb-3">
          <label className="form-label">Status</label>
          <select
            className="form-select"
            value={status}
            onChange={(e) => setStatus(e.target.value)}
          >
            <option value={1}>Active</option>
            <option value={0}>Inactive</option>
          </select>
        </div>

      

        <div className="mb-3">
          <label className="form-label">Product Images</label>
          <input
            type="file"
            className="form-control"
            multiple
            onChange={handleImageChange}
            accept="image/*"
            required
          />
          <small className="text-muted">You can select multiple images.</small>
        </div>

        <button type="submit" className="btn btn-primary">Add Product</button>
      </form>
    </div>
  );
};

export default AddProduct;
