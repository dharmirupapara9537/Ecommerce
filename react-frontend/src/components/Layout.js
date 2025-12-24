import React, { useState } from "react";

import Sidebar from "./Sidebar";
import Topbar from "./Topbar";

const Layout = ({ children }) => {
  const [sidebarOpen, setSidebarOpen] = useState(true);

  const toggleSidebar = () => setSidebarOpen(!sidebarOpen);

  return (
    <div id="wrapper">
      <Sidebar isOpen={sidebarOpen} />
      <div id="content-wrapper">
        <Topbar onToggleSidebar={toggleSidebar} />
        <div className="container-fluid">{children}</div>
      </div>
    </div>
  );
};

export default Layout;
