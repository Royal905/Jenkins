import React, { useState, useEffect } from "react";
import "./Order.css";
import { useParams } from "react-router-dom";
import { apiHandler } from "../../api";
import { endpoint } from "../../api/endpoint";
import constants from "../../utils/constants";

const Order = () => {
  const { id } = useParams();

  const [orderData, setOrderData] = useState();

  useEffect(async () => {
    const result = await apiHandler({
      url: endpoint.ORDER_DETAILS + id,
      method: "GET",
    });

    if (result && result.data) {
      setOrderData(result.data);
    }
  }, []);

  console.log("orderData", orderData);

  const handleshipment = async (orderStatus) => {
    const reqBody = {
      updatedStatus: orderStatus,
    };

    const result = await apiHandler({
      url: endpoint.UPDATE_ORDER + id,
      method: "POST",
      data: reqBody,
    });

    console.log("result", result);
  };

  const handlecancel = async () => {
    const result = await apiHandler({
      url: endpoint.CANCEL_ORDER + id,
      method: "POST",
    });

    console.log("result", result);
  };

  const handlereturn = async (returnStatus) => {
    const reqBody = {
      updatedStatus: returnStatus,
    };

    const result = await apiHandler({
      url: endpoint.RETURN_ORDER + id,
      method: "POST",
      data: reqBody,
    });

    console.log("result", result);
  };

  return (
    <>
      {orderData && orderData.length ? (
        <div>
          <div className="storeViewRoot">
            <div className="container">
              <div className="pt-3 px-2 d-flex justify-content-between align-items-center">
                <div>
                  <h2>Order : {orderData[0].id}</h2>
                </div>
                <div>
                  {orderData[0].orderstatus == "Accepted" ? (
                    <>
                      <button
                        className="m-2 py-2 px-4 saveButton"
                        onClick={() => handleshipment("PICKED UP")}
                      >
                        Ready for shipment
                      </button>
                      <button
                        className="m-2 py-2 px-4 saveButton"
                        onClick={() => handleshipment("DELIVERED")}
                      >
                        Delivered
                      </button>
                      <button
                        className="m-2 py-2 px-4 saveButton"
                        onClick={() => handlecancel()}
                      >
                        Cancel Total Order
                      </button>
                    </>
                  ) : orderData[0].orderstatus == "In-progress" ? (
                    <>
                      <button
                        className="m-2 py-2 px-4 saveButton"
                        onClick={() => handleshipment("DELIVERED")}
                      >
                        Delivered
                      </button>
                      <button
                        className="m-2 py-2 px-4 saveButton"
                        onClick={() => handlecancel()}
                      >
                        Cancel Total Order
                      </button>
                    </>
                  ) : orderData[0].orderstatus == "Completed" ? (
                    <>
                      {orderData[0].fulfilment_status == "Order-delivered" &&
                      orderData[0].return_status == "Return_Initiated" ? (
                        <>
                          <button
                            className="m-2 py-2 px-4 saveButton"
                            onClick={() => handlereturn("Return_Approved")}
                          >
                            Return Approved
                          </button>
                          <button
                            className="m-2 py-2 px-4 saveButton"
                            onClick={() => handlereturn("Liquidated")}
                          >
                            Liquidated
                          </button>{" "}
                          <button
                            className="m-2 py-2 px-4 saveButton"
                            onClick={() => handlereturn("Return_Rejected")}
                          >
                            Return Rejected
                          </button>{" "}
                        </>
                      ) : (
                        <></>
                      )}
                    </>
                  ) : orderData[0].orderstatus == "Cancelled" &&
                    orderData[0].fulfilment_status == "Cancelled" &&
                    !orderData[0].refund_status ? (
                    <>
                      <button className="m-2 py-2 px-4 saveButton">
                        Refund
                      </button>
                    </>
                  ) : (
                    <>
                      <b>
                        Amount : {orderData[0].refund_amount} has been refunded
                      </b>
                    </>
                  )}
                </div>
              </div>
            </div>
            <hr />
            <div className="container">
              {/* <h4>Ticket</h4> */}
              <div className="row">
                <div className="col-6">
                  <b>Buyer App </b>: {orderData[0].bap_id}
                </div>
                <div className="col-6">
                  <b>Seller Order Id</b> : {orderData[0].seller_order_id}
                </div>
                <div className="col-6">
                  <b>Order Status </b>: {orderData[0].orderstatus}
                </div>
                <div className="col-6">
                  <b>Fulfilment Status</b> : {orderData[0].fulfilment_status}
                </div>
                <div className="col-6">
                  <b>Order Amount</b> : {orderData[0].order_amount}
                </div>
              </div>

              {orderData[0].return_status ? (
                <>
                  <hr />
                  <div className="row">
                    <div className="col-6">
                      <b>Return Status </b>: {orderData[0].return_status}
                    </div>
                    <div className="col-6">
                      <b>Return Reason </b>:{" "}
                      {
                        constants.RETURN_REASON_CODES[
                          orderData[0].return_reason_id
                        ]
                      }
                    </div>
                    <div className="col-12">
                      <div className="row">
                        <div className="col-2">
                          <b>Return Items </b>:{" "}
                        </div>
                        <div className="col-7">
                          {orderData[0].return_items.map((item) => (
                            <>
                              <div>{item.id}</div> <b>quantity</b> :{" "}
                              {item.quantity}
                            </>
                          ))}
                        </div>
                      </div>
                    </div>
                  </div>
                </>
              ) : null}
            </div>
          </div>
        </div>
      ) : (
        <></>
      )}
    </>
  );
};

export default Order;
