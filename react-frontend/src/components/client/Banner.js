import React from "react";
import ImgBanner from "../../assets/client/images/banner-1.jpg"; 
const Banner = () => {
  return (
    <div className="banner">
      <div className="container">
        <div className="slider-container has-scrollbar">

          <div className="slider-item">
            <img
              src={ImgBanner}
              alt="women's latest fashion sale"
              className="banner-img"
            />
            <div className="banner-content">
              <p className="banner-subtitle">Trending item</p>
              <h2 className="banner-title">Women's latest fashion sale</h2>
             
            </div>
          </div>

         

        </div>
      </div>
    </div>
  );
};

export default Banner;
