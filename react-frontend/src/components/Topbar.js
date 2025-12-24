import React from "react";
import { useNavigate } from "react-router-dom";

const Topbar = ({ onLogout, onToggleSidebar }) => {
  const navigate = useNavigate();

  const handleLogout = () => {
    if (onLogout) onLogout();
    localStorage.removeItem("admin_token");
    navigate("/admin");
  };

  return (
    <nav className="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
      {/* Sidebar Toggle */}
      <button
        id="sidebarToggleTop"
        className="btn btn-link rounded-circle mr-3"
        onClick={onToggleSidebar}
      >
        <i className="fa fa-bars"></i>
      </button>

      <ul className="navbar-nav ml-auto">
        <li className="nav-item">
          <button className="btn btn-danger btn-sm ml-3" onClick={handleLogout}>
            Logout
          </button>
        </li>
      </ul>
    </nav>
  );
};

export default Topbar;
