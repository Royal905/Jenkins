import React, { lazy, useEffect, useState } from "react"; // Import useState
import { useDispatch, useSelector } from "react-redux";
import { BrowserRouter as Router, Routes, Route } from "react-router-dom";

// Layout
import Layout from "./hocs/Layout";
import Sidebar from "./hocs/Layout/Sidebar/Sidebar"; // Import your Sidebar component
import OrderDashboard from "./containers/OrderDashboard/OrderDashboard";
import CustomerSupport from "./containers/CustomerSupport/CustomerSupport";
import PendingOrderDashboard from "./containers/PendingOrderDashboard/PendingOrderDashboard";
import ActionDashboard from "./containers/ActionDashboard/ActionDashboard";

// Container Imports
import Home from "./containers/Home/Home";
import Login from "./containers/Login/Login";
import { getAppData } from "./api/appData";
import { setappLoader } from "./features/appLoader/appLoaderSlice";
import StoreView from "./containers/StoreView/StoreView";
import TicketDetails from "./containers/TicketDetails/TicketDetails";
import UpdateTicket from "./containers/UpdateTicket/UpdateTicket";
import Order from "./containers/Order/Order";

function App() {
  const dispatch = useDispatch();
  const { authData } = useSelector((state) => state.loginSlice);
  const content = authData ? (
    <Router>
      <Layout>
        <Routes>
          <Route path="/" exact element={<Home />} />
          <Route path="/order-dashboard" element={<OrderDashboard />} />
          <Route path="/order/:id" element={<Order />} />
          <Route
            path="/pending-order-dashboard"
            element={<PendingOrderDashboard />}
          />
          <Route path="/CustomerSupport" element={<CustomerSupport />} />
          <Route path="/ticket/:id" element={<TicketDetails />} />
          <Route path="/updateticket/:id" element={<UpdateTicket />} />
          <Route path="/storeView" element={<StoreView />} />
        </Routes>
      </Layout>
    </Router>
  ) : (
    <Login />
  );

  useEffect(async () => {
    if (authData) {
      console.log("came in");
      dispatch(setappLoader(true));
      await getAppData({ dispatch }).then((result) => {
        console.log("DATA - ", result);
      });
      dispatch(setappLoader(false));
    }
  }, [authData]);

  return <main className="main">{content}</main>;
}

export default App;
