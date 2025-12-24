import React, { useState, useEffect } from "react";
import { useNavigate, useParams } from "react-router-dom";
import axios from "axios";


const EditCategory = () => {
  const { id } = useParams();
  const navigate = useNavigate();

  const [name, setName] = useState("");
  const [status, setStatus] = useState("1");
  const [image, setImage] = useState(null);
  const [currentImage, setCurrentImage] = useState("");
  const [loading, setLoading] = useState(true);

  // Fetch category by ID
  const fetchCategory = async () => {
    try {
      const res = await axios.get(`http://localhost:8000/api/categories/${id}`);
      setName(res.data.name);
      setStatus(res.data.status.toString());
      setCurrentImage(res.data.image); // existing image
      setLoading(false);
    } catch (err) {
      console.error(err);
      alert("Error fetching category data");
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchCategory();
  }, [id]);

  const handleSubmit = async (e) => {
    e.preventDefault();

    // Create FormData for image upload
    const formData = new FormData();
    formData.append("name", name);
    formData.append("status", status);
    if (image) formData.append("image", image);

    try {
      const res = await axios.post(
        `http://localhost:8000/api/categories/${id}?_method=PUT`,
        formData,
        {
          headers: { "Content-Type": "multipart/form-data" },
        }
      );

      console.log("Server response:", res.data); // check response

      // Redirect to category list with success message
      navigate("/admin/categories", { state: { message: "Category updated successfully!" } });
    } catch (err) {
      console.error("Update error:", err.response?.data || err);
      if (err.response?.data?.errors) {
        // Show validation errors
        alert(
          Object.values(err.response.data.errors)
            .flat()
            .join("\n")
        );
      } else {
        alert("Error updating category");
      }
    }
  };

  if (loading) return <p>Loading category data...</p>;

  return (
    <div className="container mt-4">
      <h3>Edit Category</h3>
      <form onSubmit={handleSubmit}>
        <div className="mb-3">
          <label className="form-label">Category Name</label>
          <input
            type="text"
            className="form-control"
            value={name}
            onChange={(e) => setName(e.target.value)}
            required
          />
        </div>

        <div className="mb-3">
          <label className="form-label">Current Image</label>
          <div className="mb-2">
            {currentImage ? (
              <img
                src={`http://localhost:8000/storage/categories/${currentImage}`}
                alt={name}
                width="100"
                height="100"
                style={{ objectFit: "cover" }}
                className="rounded"
              />
            ) : (
              <span className="text-muted">No Image</span>
            )}
          </div>
          <label className="form-label">Change Image</label>
          <input
            type="file"
            className="form-control"
            onChange={(e) => setImage(e.target.files[0])}
          />
        </div>

        <div className="mb-3">
          <label className="form-label">Status</label>
          <select
            className="form-select"
            value={status}
            onChange={(e) => setStatus(e.target.value)}
          >
            <option value="1">Active</option>
            <option value="0">Inactive</option>
          </select>
        </div>

        <button type="submit" className="btn btn-success">
          Update Category
        </button>
      </form>
    </div>
  );
};

export default EditCategory;
