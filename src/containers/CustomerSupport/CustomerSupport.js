import React, { useState, useEffect } from "react";
import CustomerSupportLogo from "../../assets/images/Support.png";
import "./CustomerSupport.css";
import { endpoint } from "../../api/endpoint";
import { apiHandler } from "../../api";
import { useSelector } from "react-redux";
import { Link } from "react-router-dom";

const CustomerSupport = () => {
  const { customerTicketsData } = useSelector(
    (state) => state.customerTicketsSlice
  );

  return (
    <div className="containerRoot">
      <div className="main-content">
        <h1 className="dashboard-heading m-4">
          <img
            className="dashboard-heading-logo"
            src={CustomerSupportLogo}
            alt=""
          />{" "}
          Customer Support
        </h1>
        <div className="table-responsive gridTbl">
          <table className="table table-bordered table-striped table-hover">
            <thead>
              <tr className="border1">
                <th className="text1">S. No.</th>
                <th className="text1">Network Order Id</th>
                <th className="text1">Ticket Id</th>
                <th className="text1">Order Id</th>
                <th className="text1">Name of Buyer NP</th>
                <th className="text1">Name of Seller NP</th>
                <th className="text1">Name of Logistics NP (On network)</th>
                <th className="text1">Ticket Creation Date</th>
                <th className="text1">Ticket Creation Time</th>
                <th className="text1">Issue Category</th>
                <th className="text1">Order Category</th>
                <th className="text1">Ticket Status</th>
                <th className="text1">Ticket Closure Date</th>
                <th className="text1">Ticket Closure Time</th>
                <th className="text1">Ticket Relay Date</th>
                <th className="text1">Ticket Relay Time</th>
                <th className="text1">Ticket Last Update Date</th>
                <th className="text1">Ticket Last Update Time</th>
              </tr>
            </thead>
            <tbody>
              {customerTicketsData.length === 0 ? (
                <tr>
                  <td className="text-center" colSpan="18">
                    No data available
                  </td>
                </tr>
              ) : (
                customerTicketsData.map((row, index) => {
                  return (
                    <tr className="border1" key={index}>
                      <td>{index + 1}</td>
                      <td>
                        <Link to={`/ticket/${row.ticketId}`}>
                          {row.networkOrderId}
                        </Link>
                      </td>
                      <td>{row.ticketId}</td>
                      <td>{row.orderId}</td>
                      <td>{row.nameOfBuyerNP}</td>
                      <td>{row.nameOfSelleNP}</td>
                      <td>{row.nameOfLogisticsNP}</td>
                      <td>{row.ticketCreationAt}</td>
                      <td>{row.ticketCreationTime}</td>
                      <td>{row.issueCategory}</td>
                      <td>{row.orderCategory}</td>
                      <td>{row.ticketStatus}</td>
                      <td>{row.ticketClosureDate}</td>
                      <td>{row.ticketClosureTime}</td>
                      <td>{row.ticketRelayDate}</td>
                      <td>{row.ticketRelayTime}</td>
                      <td>{row.ticketlastUpdateDate}</td>
                      <td>{row.ticketlastUpdateTime}</td>
                    </tr>
                  );
                })
              )}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
};

export default CustomerSupport;
