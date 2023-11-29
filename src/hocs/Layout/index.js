import React, { useState } from "react";
import PropTypes from "prop-types";

import { ThemeProvider } from "styled-components";

import Sidebar from "./Sidebar/Sidebar";
import "./layout.css";
import AppLoader from "../../components/AppLoader/appLoader";
import { useSelector } from "react-redux";

const Layout = ({ children }) => {
  const { appLoader } = useSelector((state) => state.appLoaderSlice);

  return (
    <>
      <div className="">
        <Sidebar />
        <div className="container-fluid">
          {appLoader ? <AppLoader /> : <>{children}</>}
        </div>
      </div>
    </>
  );
};

Layout.propTypes = {
  children: PropTypes.object.isRequired,
};

export default Layout;
