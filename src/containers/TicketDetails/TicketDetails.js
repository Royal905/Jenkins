import React, { useEffect, useState } from "react";
import { Link, useParams } from "react-router-dom";
import { apiHandler } from "../../api";
import { endpoint } from "../../api/endpoint";
import { saveCustomerTicket } from "../../features/customerTickets/customerTicketSlice";
import { useDispatch, useSelector } from "react-redux";

const TicketDetails = () => {
  const { id } = useParams();
  const dispatch = useDispatch();
  const { customerTicketData } = useSelector(
    (state) => state.customerTicketSlice
  );
  useEffect(async () => {
    const result = await apiHandler({
      url: endpoint.TICKET_BY_ID + id,
      method: "GET",
    });

    if (result && result.data) {
      dispatch(saveCustomerTicket(result.data));
    }
  }, []);

  console.log("customerTicketData", customerTicketData);

  return (
    <div>
      <div className="storeViewRoot">
        {customerTicketData && customerTicketData.ticketId ? (
          <>
            <div className="container">
              <div className="pt-3 px-2 d-flex justify-content-between align-items-center">
                <div>
                  <h2>Ticket Details</h2>
                </div>
                <div>
                  <Link to={`/updateticket/${customerTicketData.ticketId}`}>
                    <button className="py-2 px-4 saveButton">
                      Edit Ticket Status
                    </button>
                  </Link>
                </div>
              </div>
            </div>
            <hr />
            <div className="container">
              {/* <h4>Ticket</h4> */}
              <div className="row">
                <div className="col-6">
                  <b>Ticket ID </b>: {customerTicketData.ticketId}
                </div>
                <div className="col-6">
                  <b>Ticket created At</b> :{" "}
                  {new Date(
                    customerTicketData.ticketCreationAt
                  ).toLocaleString()}
                </div>
                <div className="col-6">
                  <b>Ticket Status </b>: {customerTicketData.ticketStatus}
                </div>
                <div className="col-6">
                  <b>Ticket last Updated At</b> :{" "}
                  {new Date(
                    customerTicketData.ticketlastUpdateDate
                  ).toLocaleString()}
                </div>

                <div className="col-6">
                  <b>Buyer Name </b>: {customerTicketData.nameOfBuyerNP}
                </div>

                <div className="col-6">
                  <b>Network Order Id </b>: {customerTicketData.networkOrderId}
                </div>

                <div className="col-6">
                  <b>Order Category </b>: {customerTicketData.orderCategory}
                </div>

                <div className="col-6">
                  <b>Ticket Category </b>: {customerTicketData.issueCategory}
                </div>

                <div className="col-6">
                  <b>Short Description </b>: {customerTicketData.shortDesc}
                </div>

                <div className="col-6">
                  <b>Long Description</b>: {customerTicketData.longDesc}
                </div>

                <div className="col-6">
                  <b>Logistic Provider</b>:{" "}
                  {customerTicketData.nameOfLogisticsNP}
                </div>
              </div>

              <hr />
              <div className="row">
                <h4>Complaint Information</h4>
                <div className="col-6">
                  <b>Name</b> : {customerTicketData.complainantInfo.person.name}
                </div>

                <div className="col-6">
                  <b>Phone</b> :{" "}
                  {customerTicketData.complainantInfo.contact.phone}
                </div>

                <div className="col-6">
                  <b>email</b> :{" "}
                  {customerTicketData.complainantInfo.contact.email}
                </div>

                <div className="col-6">
                  <b>Action Status</b> :{" "}
                  {customerTicketData.complainantActions[0].complainant_action}
                </div>
                <div className="col-6">
                  <b>Status Updated At</b> :{" "}
                  {new Date(
                    customerTicketData.complainantActions[0].updated_at
                  ).toLocaleString()}
                </div>
              </div>
            </div>
          </>
        ) : (
          <></>
        )}
      </div>
    </div>
  );
};

export default TicketDetails;
