import React, { useEffect, useState } from "react";
import axios from "axios";
import Pagination from "../../components/Pagination";

const API_URL = "http://127.0.0.1:8000/api";

const AdminOrdersPage = () => {
  const [orders, setOrders] = useState([]);
  const [currentPage, setCurrentPage] = useState(1);
  const [lastPage, setLastPage] = useState(1);
  const [search, setSearch] = useState("");
  const [statusFilter, setStatusFilter] = useState("");
  const [loading, setLoading] = useState(false);

  // 🔑 JWT token
  const token = localStorage.getItem("admin_token");

  // 🔹 Common headers
  const authHeaders = {
    headers: {
      Authorization: `Bearer ${token}`,
      Accept: "application/json",
    },
  };

  // ===============================
  // Fetch Orders
  // ===============================
  const fetchOrders = async (page = 1, searchTerm = "", status = "") => {
    setLoading(true);
    try {
      const res = await axios.get(
        `${API_URL}/admin/orders?page=${page}&search=${searchTerm}&status=${status}`,
        authHeaders
      );

      console.log("Orders API Response:", res.data);

      setOrders(res.data?.data || []);
      setCurrentPage(res.data?.current_page || 1);
      setLastPage(res.data?.last_page || 1);
    } catch (error) {
      console.error("Fetch orders error:", error);
      setOrders([]);
    } finally {
      setLoading(false);
    }
  };

  // ===============================
  // Update Order Status
  // ===============================
  const updateOrderStatus = async (orderId, newStatus) => {
    if (!window.confirm(`Change status to ${newStatus}?`)) return;

    try {
      await axios.put(
        `${API_URL}/admin/orders/${orderId}/status`,
        { status: newStatus },
        authHeaders
      );

      fetchOrders(currentPage, search, statusFilter);
    } catch (error) {
      console.error("Update status error:", error);
      alert("Failed to update order status");
    }
  };

  // ===============================
  // Initial Load
  // ===============================
  useEffect(() => {
    fetchOrders();
  }, []);

  // ===============================
  // Search Submit
  // ===============================
  const handleSearch = (e) => {
    e.preventDefault();
    fetchOrders(1, search, statusFilter);
  };

  return (
    <div className="container mt-4">
      <h4 className="text-primary mb-3">Admin Orders</h4>

      {/* 🔍 Search & Filter */}
      <form className="mb-3" onSubmit={handleSearch}>
        <div className="d-flex gap-2 flex-wrap">
          <input
            type="text"
            className="form-control"
            placeholder="Search order / customer..."
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            style={{ maxWidth: "250px" }}
          />

          <select
            className="form-select"
            value={statusFilter}
            onChange={(e) => {
              setStatusFilter(e.target.value);
              fetchOrders(1, search, e.target.value);
            }}
            style={{ maxWidth: "160px" }}
          >
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="paid">Paid</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
          </select>

          <button className="btn btn-primary">Search</button>
        </div>
      </form>

      {loading && <p>Loading orders...</p>}

      {/* 📦 Orders Table */}
      <div className="card shadow">
        <div className="card-body table-responsive">
          <table className="table table-bordered table-striped align-middle">
            <thead className="table-dark">
              <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Items</th>
                <th>Created</th>
              </tr>
            </thead>
            <tbody>
              {orders.length > 0 ? (
                orders.map((order) => (
                  <tr key={order.id}>
                    <td>{order.order_number}</td>
                    <td>{order.customer?.name || "N/A"}</td>
                    <td>₹{order.total_amount}</td>
                    <td>{order.payment_method}</td>

                    {/* Status + Buttons */}
                    <td>
                      <span
                        className={`badge mb-2 ${
                          order.status === "paid"
                            ? "bg-success"
                            : order.status === "completed"
                            ? "bg-primary"
                            : order.status === "cancelled"
                            ? "bg-danger"
                            : "bg-secondary"
                        }`}
                      >
                        {order.status}
                      </span>

                      <div className="d-flex gap-1 mt-2 flex-wrap">
                        {order.status !== "paid" && (
                          <button
                            className="btn btn-sm btn-outline-success"
                            onClick={() =>
                              updateOrderStatus(order.id, "paid")
                            }
                          >
                            Mark Paid
                          </button>
                        )}

                        {order.status !== "completed" && (
                          <button
                            className="btn btn-sm btn-outline-primary"
                            onClick={() =>
                              updateOrderStatus(order.id, "completed")
                            }
                          >
                            Complete
                          </button>
                        )}

                        {order.status !== "cancelled" && (
                          <button
                            className="btn btn-sm btn-outline-danger"
                            onClick={() =>
                              updateOrderStatus(order.id, "cancelled")
                            }
                          >
                            Cancel
                          </button>
                        )}
                      </div>
                    </td>

                    <td>
                      <ul className="mb-0">
                        {(order.items || []).map((item) => (
                          <li key={item.id}>
                            {item.product?.name}
                          </li>
                        ))}
                      </ul>
                    </td>

                    <td>
                      {new Date(order.created_at).toLocaleString()}
                    </td>
                  </tr>
                ))
              ) : (
                <tr>
                  <td colSpan="7" className="text-center text-muted">
                    No orders found
                  </td>
                </tr>
              )}
            </tbody>
          </table>

          {/* 📄 Pagination */}
          {lastPage > 1 && (
            <Pagination
              currentPage={currentPage}
              lastPage={lastPage}
              onPageChange={(page) =>
                fetchOrders(page, search, statusFilter)
              }
            />
          )}
        </div>
      </div>
    </div>
  );
};

export default AdminOrdersPage;
