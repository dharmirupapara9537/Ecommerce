import React, { useState } from "react";
import { Link } from "react-router-dom";

const Sidebar = ({ isOpen }) => {
  const [openMenu, setOpenMenu] = useState(null);

  const toggleMenu = (menuId) => {
    setOpenMenu(openMenu === menuId ? null : menuId);
  };

  return (
    <div className={`sidebar-container ${isOpen ? "open" : "closed"}`}>
      <ul
        className="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion"
        id="accordionSidebar"
      >
        <Link
          className="sidebar-brand d-flex align-items-center justify-content-center"
          to="/"
        >
          <div className="sidebar-brand-icon rotate-n-15">
            <i className="fas fa-laugh-wink"></i>
          </div>
          <div className="sidebar-brand-text mx-3">Admin</div>
        </Link>

        <hr className="sidebar-divider my-0" />

        <li className="nav-item">
          <Link className="nav-link" to="/admin/categories">
            <i className="nav-icon fas fa-list"></i>&nbsp;
            <span>Categories</span>
          </Link>
        </li>

        <li className="nav-item">
          <Link className="nav-link" to="/admin/products">
            <i className="nav-icon fas fa-box"></i>&nbsp;
            <span>Products</span>
          </Link>
        </li>

         <li className="nav-item">
          <Link className="nav-link" to="/admin/orders">
            <i className="nav-icon fas fa-receipt"></i>&nbsp;
            <span>Orders</span>
          </Link>
        </li>
      </ul>
    </div>
  );
};

export default Sidebar;
