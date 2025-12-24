// DashboardLayout.js
import React from "react";
import { useState } from "react";
import Sidebar from "./Sidebar";
import Topbar from "./Topbar";
import Footer from "./Footer";



const DashboardLayout = ({ children }) => {
   const [isSidebarOpen, setIsSidebarOpen] = useState(true);

  const handleToggleSidebar = () => {
    setIsSidebarOpen(!isSidebarOpen);
  };
  return (
    <div id="wrapper">
      {/* Sidebar */}

    <Sidebar isOpen={isSidebarOpen} />
      {/* Content Wrapper */}
      <div id="content-wrapper" className="d-flex flex-column">
        {/* Main Content */}
        <div id="content">
          {/* Topbar */}
           <Topbar onToggleSidebar={handleToggleSidebar} />

          {/* Page Content */}
          <div className="container-fluid">
            {children}
          </div>
        </div>

        {/* Footer */}
        <Footer />
      </div>
    </div>
  );
};

export default DashboardLayout;
