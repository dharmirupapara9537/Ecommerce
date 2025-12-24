import React from "react";
import { Navigate, Outlet } from "react-router-dom";

const AdminRoute = () => {
  const token = localStorage.getItem("admin_token");
const role = localStorage.getItem("role");

if (!token || role !== "admin") {
  return <Navigate to="/admin" />;
}


  return <Outlet />; // render all child routes
};

export default AdminRoute;
