import React from "react";
import ReactDOM from "react-dom/client";
import App from "./App";

import $ from "jquery";

// CSS imports
import "bootstrap/dist/css/bootstrap.min.css";
import "@fortawesome/fontawesome-free/css/all.min.css";
import "./assets/css/sb-admin-2.min.css";
import './assets/css/custom.css'; 
// JS imports
import "bootstrap/dist/js/bootstrap.bundle.min.js";
import "./assets/js/custom-init.js"; // ✅ load jQuery + sb-admin-2 together
import './assets/js/sb-admin-2.min.js';

import './index.css';

const root = ReactDOM.createRoot(document.getElementById("root"));
root.render(<App />);
