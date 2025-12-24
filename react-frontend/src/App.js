import React from "react";
import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import Register from "./pages/Register";
import Login from "./pages/Login";
import Dashboard from "./pages/Dashboard";
import ProtectedRoute from "./components/ProtectedRoute"; // checks if user is logged in
import AdminRoute from "./components/AdminRoute"; // checks if user is admin

// Category
import AddCategory from "./pages/Category/AddCategory";
import CategoryList from "./pages/Category/CategoryList";
import EditCategory from "./pages/Category/EditCategory";

// Product
import ProductList from "./pages/Product/ProductList";
import AddProduct from "./pages/Product/AddProduct";
import EditProduct from "./pages/Product/EditProduct";

// Orders
import AdminOrdersPage from "./pages/orders/AdminOrdersPage";

//Review
import AdminProductReviews from "./pages/Review/AdminProductReviews";

// Client
import Header from "./components/client/Header";
import Navbar from "./components/client/Navbar";
import Footer from "./components/client/Footer";
import Home from "./components/client/Home";
import ProductDetail from "./components/client/ProductDetail";
import CartPage from "./components/client/CartPage";
import CheckoutPage from "./components/client/CheckoutPage";
import ThankYouPage from "./components/client/ThankYouPage";
import Product from "./components/client/Products";

import DashboardLayout from "./components/DashboardLayout";

function App() {
  return (
    <Router>
      <Routes>
        {/* Client Routes */}
        <Route
          path="/*"
          element={
            <>
              <Header />
              <Navbar />
              <Routes>
                <Route path="/" element={<Home />} />
                <Route path="/product/:id" element={<ProductDetail />} />
                <Route path="/cart" element={<CartPage />} />
                <Route path="/checkout" element={<CheckoutPage />} />
                <Route path="/thank-you" element={<ThankYouPage />} />
                <Route path="/search" element={<Product />} />
              </Routes>
              <Footer />
            </>
          }
        />

        {/* Public Auth Routes */}
        <Route path="/admin" element={<Login />} />
        <Route path="/register" element={<Register />} />

        {/* Protected Routes - only logged in users */}
        <Route element={<ProtectedRoute />}>
          <Route path="/admin/dashboard" element={<Dashboard />} />
        </Route>

        {/* Admin Routes - only admin users */}
        <Route element={<AdminRoute />}>
          {/* Category */}
          <Route
            path="/admin/categories"
            element={
              <DashboardLayout>
                <CategoryList />
              </DashboardLayout>
            }
          />
          <Route
            path="/admin/add-category"
            element={
              <DashboardLayout>
                <AddCategory />
              </DashboardLayout>
            }
          />
          <Route
            path="/admin/edit-category/:id"
            element={
              <DashboardLayout>
                <EditCategory />
              </DashboardLayout>
            }
          />

          {/* Product */}
          <Route
            path="/admin/products"
            element={
              <DashboardLayout>
                <ProductList />
              </DashboardLayout>
            }
          />
           <Route path="/admin/product/:id/reviews" element={
             <DashboardLayout>
                <AdminProductReviews />
              </DashboardLayout>
            } />
          <Route
            path="/admin/add-product"
            element={
              <DashboardLayout>
                <AddProduct />
              </DashboardLayout>
            }
          />
          <Route
            path="/admin/edit-product/:id"
            element={
              <DashboardLayout>
                <EditProduct />
              </DashboardLayout>
            }
          />

          {/* Orders */}
          <Route
            path="/admin/orders"
            element={
              <DashboardLayout>
                <AdminOrdersPage />
              </DashboardLayout>
            }
          />
        </Route>
      </Routes>
    </Router>
  );
}

export default App;
