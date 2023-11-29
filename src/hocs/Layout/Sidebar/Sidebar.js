import React from "react";
import { useDispatch } from "react-redux";
import "./sidebar.css";
import { Link } from "react-router-dom";
import CompanyLogo from "../../../assets/images/Bamboology_logo.webp";
import { saveAuthData } from "../../../features/login/LoginSlice";
import styles from "./NavBar.module.css";

const Sidebar = () => {
  const dispatch = useDispatch();
  const handleSubmit = async (e) => {
    e.preventDefault();
    dispatch(saveAuthData(false));
  };

  return (
    <div className={styles.VerticalNav}>
      <div className="container-fluid">
        <div className="row d-flex align-items-center justify-content-center mb-0">
          <div className="col-md-2">
            <div className={styles.NavbarExpand}>
              <Link to="/" className={styles.NavbarBrand}>
                <img className="OndcLogo" src={CompanyLogo} alt="Logo" />
              </Link>
            </div>
          </div>
          <div className="col-md-10">
            <ul className={styles.SideMenu}>
              <li className={styles.SideMenuItem}>
                <Link to="/order-dashboard" className={styles.SideMenuItemLink}>
                  <i className="fas fa-chart-bar"></i>
                  <span>Order Dashboard</span>
                </Link>
              </li>
              <li className={styles.SideMenuItem}>
                <Link to="/CustomerSupport" className={styles.SideMenuItemLink}>
                  <i className="fas fa-chart-bar mt-5"></i>
                  <span>Customer Support</span>
                </Link>
              </li>
              {/* Add more menu items here */}
            </ul>
            {/*<div className="logout_Section">*/}
            {/*  <button className="logoutBtn btn btn-primary" onClick={handleSubmit}>*/}
            {/*    LogOut*/}
            {/*  </button>*/}
          </div>
        </div>
      </div>
    </div>
  );
};

export default Sidebar;
