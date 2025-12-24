import React, { useState, useEffect } from "react";
import axios from "axios";
import { useNavigate, useParams } from "react-router-dom";

const EditProduct = () => {
  const { id } = useParams();
  const navigate = useNavigate();

  const [categoriesList, setCategoriesList] = useState([]);
  const [selectedCategory, setSelectedCategory] = useState("");
  const [name, setName] = useState("");
  const [sku, setSku] = useState("");
  const [alias, setAlias] = useState("");
  const [price, setPrice] = useState("");
  const [regularPrice, setRegularPrice] = useState("");
  const [status, setStatus] = useState(1);
  const [existingImages, setExistingImages] = useState([]);
  const [newImages, setNewImages] = useState([]);
  const [primaryImage, setPrimaryImage] = useState(null);

  // Fetch categories and product details
  useEffect(() => {
    const fetchData = async () => {
      try {
        const [catRes, prodRes] = await Promise.all([
          axios.get("http://127.0.0.1:8000/api/categories"),
          axios.get(`http://127.0.0.1:8000/api/products/${id}`)
        ]);

        const categories = catRes.data.data || catRes.data;
        setCategoriesList(categories);

        const product = prodRes.data.data || prodRes.data;

        setName(product.name);
        setSku(product.sku);
        setAlias(product.alias);
        setPrice(product.price);
        setRegularPrice(product.regular_price);
        setStatus(product.status);

        // Set category id
        const categoryId = product.categories?.[0]?.id?.toString() || "";
        setSelectedCategory(categoryId);

        // Set primary image
        const primary = product.images?.find(img => img.is_primary === 1);
        setPrimaryImage(primary ? primary.image_path : null);

        // Set existing images for submission
        const existing = product.images?.map(img => img.image_path) || [];
        setExistingImages(existing);

      } catch (err) {
        console.error("Error fetching data:", err);
      }
    };

    fetchData();
  }, [id]);

  const handleImageChange = (e) => setNewImages(e.target.files);

  const handleRemoveExistingImage = (index) => {
    const updatedImages = existingImages.filter((_, i) => i !== index);
    setExistingImages(updatedImages);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    if (!selectedCategory) {
      alert("Please select a category");
      return;
    }

    const formData = new FormData();
    formData.append("category_id", Number(selectedCategory));
    formData.append("name", name);
    formData.append("sku", sku);
    formData.append("alias", alias);
    formData.append("price", price);
    formData.append("regular_price", regularPrice);
    formData.append("status", Number(status));
    formData.append("existing_images", JSON.stringify(existingImages));

    for (let i = 0; i < newImages.length; i++) {
      formData.append("images[]", newImages[i]);
    }

    // Debug FormData
    for (let pair of formData.entries()) {
      console.log(pair[0], pair[1]);
    }

    try {
        
formData.append("_method", "PUT"); // Laravel understands this
await axios.post(
  `http://127.0.0.1:8000/api/products/${id}`,
  formData,
  { headers: { "Content-Type": "multipart/form-data" } }
);

      navigate("/admin/products", { state: { message: "Product updated successfully!" } });
    } catch (err) {
      console.error("Update error:", err.response?.data || err.message);
      alert(JSON.stringify(err.response?.data || err.message));
    }
  };

  return (
    <div className="container mt-4">
      <h4 className="text-primary mb-3">Edit Product</h4>

      <form onSubmit={handleSubmit} encType="multipart/form-data">
        {/* Category */}
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
              <option key={cat.id} value={cat.id.toString()}>
                {cat.name}
              </option>
            ))}
          </select>
        </div>

        {/* Product fields */}
        <div className="mb-3">
          <label className="form-label">Product Name</label>
          <input type="text" className="form-control" value={name} onChange={(e) => setName(e.target.value)} required />
        </div>

        <div className="mb-3">
          <label className="form-label">SKU</label>
          <input type="text" className="form-control" value={sku} onChange={(e) => setSku(e.target.value)} required />
        </div>

        <div className="mb-3">
          <label className="form-label">Alias</label>
          <input type="text" className="form-control" value={alias} onChange={(e) => setAlias(e.target.value)} required />
        </div>

        <div className="mb-3">
          <label className="form-label">Price</label>
          <input type="number" className="form-control" value={price} onChange={(e) => setPrice(e.target.value)} required />
        </div>

        <div className="mb-3">
          <label className="form-label">Regular Price</label>
          <input type="number" className="form-control" value={regularPrice} onChange={(e) => setRegularPrice(e.target.value)} required />
        </div>

        <div className="mb-3">
          <label className="form-label">Status</label>
          <select className="form-select" value={status} onChange={(e) => setStatus(e.target.value)}>
            <option value={1}>Active</option>
            <option value={0}>Inactive</option>
          </select>
        </div>

     

        {/* Primary Image */}
        {primaryImage && (
          <div className="mb-3">
            <label className="form-label">Primary Image</label>
            <div>
              <img
                src={`http://127.0.0.1:8000/storage/products/${primaryImage}`}
                alt="primary"
                width="120"
                height="120"
                className="border rounded"
              />
            </div>
          </div>
        )}

        {/* New image upload */}
        <div className="mb-3">
          <label className="form-label">Add New Images</label>
          <input type="file" className="form-control" multiple onChange={handleImageChange} accept="image/*" />
        </div>

        <button type="submit" className="btn btn-primary">Update Product</button>
      </form>
    </div>
  );
};

export default EditProduct;
