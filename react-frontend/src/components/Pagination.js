import React from "react";

const Pagination = ({ currentPage, lastPage, onPageChange }) => {
  if (lastPage <= 1) return null; // hide pagination if only 1 page

  const handleChange = (page) => {
    onPageChange(page);
    window.scrollTo({ top: 0, behavior: "smooth" });
  };

  const pages = [];
  const visibleRange = 2;
  const start = Math.max(1, currentPage - visibleRange);
  const end = Math.min(lastPage, currentPage + visibleRange);

  // First page + leading dots
  if (start > 1) {
    pages.push(
      <li key={1} className={`page-item ${currentPage === 1 ? "active" : ""}`}>
        <button className="page-link rounded-pill shadow-sm" onClick={() => handleChange(1)}>
          1
        </button>
      </li>
    );
    if (start > 2) {
      pages.push(
        <li key="start-ellipsis" className="page-item disabled">
          <span className="page-link border-0 bg-transparent">...</span>
        </li>
      );
    }
  }

  // Visible page numbers
  for (let i = start; i <= end; i++) {
    pages.push(
      <li key={i} className={`page-item ${currentPage === i ? "active" : ""}`}>
        <button
          className={`page-link rounded-pill shadow-sm ${
            currentPage === i ? "bg-primary text-white border-primary" : ""
          }`}
          onClick={() => handleChange(i)}
        >
          {i}
        </button>
      </li>
    );
  }

  // Trailing dots + last page
  if (end < lastPage) {
    if (end < lastPage - 1) {
      pages.push(
        <li key="end-ellipsis" className="page-item disabled">
          <span className="page-link border-0 bg-transparent">...</span>
        </li>
      );
    }
    pages.push(
      <li key={lastPage} className={`page-item ${currentPage === lastPage ? "active" : ""}`}>
        <button className="page-link rounded-pill shadow-sm" onClick={() => handleChange(lastPage)}>
          {lastPage}
        </button>
      </li>
    );
  }

  return (
    <nav className="mt-4">
      <ul className="pagination justify-content-center flex-wrap gap-1">
        {/* Previous */}
        <li className={`page-item ${currentPage === 1 ? "disabled" : ""}`}>
          <button
            className="page-link rounded-pill shadow-sm"
            onClick={() => currentPage > 1 && handleChange(currentPage - 1)}
          >
            <i className="fa fa-angle-left me-1"></i> Prev
          </button>
        </li>

        {/* Page Numbers */}
        {pages}

        {/* Next */}
        <li className={`page-item ${currentPage === lastPage ? "disabled" : ""}`}>
          <button
            className="page-link rounded-pill shadow-sm"
            onClick={() => currentPage < lastPage && handleChange(currentPage + 1)}
          >
            Next <i className="fa fa-angle-right ms-1"></i>
          </button>
        </li>
      </ul>
    </nav>
  );
};

export default Pagination;
