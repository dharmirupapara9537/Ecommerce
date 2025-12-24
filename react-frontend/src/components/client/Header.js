import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import "../../assets/client/css/style.css";
import Logo from "../../assets/client/images/logo/logo.svg";

const Header = () => {
  const [searchTerm, setSearchTerm] = useState("");
  const navigate = useNavigate();

  // 🔍 Handle search
  const handleSearch = (e) => {
    e.preventDefault();

    // Prevent empty searches
    if (!searchTerm.trim()) return;

    // Navigate to ProductList page with search param
    navigate(`/search?search=${encodeURIComponent(searchTerm.trim())}`);


  };

  return (
    <header>

      {/* Header Main */}
      <div className="header-main">
        <div className="container">
          <a href="/" className="header-logo">
            <img src={Logo} alt="Anon's logo" width="120" height="36" />
          </a>
         
          {/* ✅ Search Form */}
          <form className="header-search-container" onSubmit={handleSearch}>
            <input
              type="search"
              name="search"
              className="search-field"
              placeholder="Enter your product name..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
            />
            <button type="submit" className="search-btn">
              <ion-icon name="search-outline"></ion-icon>
            </button>
          </form>

        </div>
      </div>

      
    </header>
  );
};

export default Header;
