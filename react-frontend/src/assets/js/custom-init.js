import $ from "jquery";

// Attach jQuery globally before SB Admin 2 is used
window.$ = window.jQuery = $;

// Dynamically import SB Admin 2 JS (after jQuery exists)
import("./sb-admin-2.min.js");
