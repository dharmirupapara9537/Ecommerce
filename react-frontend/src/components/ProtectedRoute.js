import React from 'react';
import { Navigate, Outlet } from 'react-router-dom';

const PrivateRoute = () => {
  // Replace this with your actual authentication check (e.g., checking localStorage, context API, or Redux store)
  const isAuthenticated = localStorage.getItem('admin_token') !== null; 

  return isAuthenticated ? <Outlet /> : <Navigate to="/admin" replace />;
};

export default PrivateRoute;