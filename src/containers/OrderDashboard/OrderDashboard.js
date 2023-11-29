import React, { useState, useEffect } from "react";
import "./OrderDashboard.css";
import orderLogo from "../../assets/images/Order.png";
import { apiHandler } from "../../api";
import { endpoint } from "../../api/endpoint";
import { useSelector, useDispatch } from "react-redux";
import { Link } from "react-router-dom";
import {
  saveOrdes,
  savePendingOrderCount,
} from "../../features/orders/ordersSlice";

const OrderDashboard = () => {
  const dispatch = useDispatch();
  const { ordersData, pendingOrderCount } = useSelector(
    (state) => state.ordersSlice
  );

  const [loading, setLoading] = useState(false);

  useEffect(() => {
    fetchData();
  }, [dispatch]);

  const fetchData = async () => {
    try {
      console.log("fetching data...");
      setLoading(true);

      // const orderDataResponse = await apiHandler({
      //   url: endpoint.ORDERS,
      //   method: "GET",
      // });
      // console.log("Data received", orderDataResponse.data);

      // if (orderDataResponse && orderDataResponse.data) {
      //   dispatch(saveOrdes(orderDataResponse.data));
      // }

      const pendingCountResponse = await apiHandler({
        url: endpoint.PENDING_REQUESTS_COUNT,
        method: "GET",
      });

      if (pendingCountResponse && pendingCountResponse.data) {
        console.log("Pending count Response:", pendingCountResponse);
        dispatch(savePendingOrderCount(pendingCountResponse.data));
      }
    } catch (error) {
      console.error("Error fetching data:", error);
    } finally {
      setLoading(false);
    }
  };

  let rowCount = 0;
  return (
    <div className="containerRoot">
      <div className="main-content">
        <div style={{ display: "flex" }}>
          <h1 className="dashboard-heading m-4">
            <img className="dashboard-heading-logo" src={orderLogo} alt="" />{" "}
            Order Dashboard
          </h1>
          <Link to="/pending-order-dashboard">
            <button className="OrderButton">
              Pending Orders | {loading ? "Loading..." : pendingOrderCount ?? 0}
            </button>
          </Link>
        </div>
        <div className="table-responsive gridTbl">
          <table className="table table-bordered table-striped table-hover">
            <thead>
              <tr className="border1">
                <th className="text1">S.No</th>
                <th className="text1">Buyer NP Name</th>
                <th className="text1">Order Created</th>
                <th className="text1">Network OrderId</th>
                <th className="text1">Network TransactionId</th>
                <th className="text1">Seller NP OrderId</th>
                <th className="text1">Item Id</th>
                <th className="text1">Qty</th>
                <th className="text1">Seller NP Type</th>
                <th className="text1">Name of Seller</th>
                <th className="text1">Seller Pincode</th>
                <th className="text1">Seller City</th>
                <th className="text1">SKU Code</th>
                <th className="text1">Order Category</th>
                <th className="text1">Total Shipping Charges</th>
                <th className="text1">Total Order Value</th>
                <th className="text1">Buyer Finder Fee</th>
                <th className="text1">Withholding Amount</th>
                <th className="text1">Order Status</th>
                <th className="text1">Ready to ShipAt</th>
                <th className="text1">Shipped At</th>
                <th className="text1">Logistics Seller NP Name</th>
                <th className="text1">Logistics Network OrderId</th>
                <th className="text1">Logistics Network TransactionId</th>
                <th className="text1">Delivered At</th>
                <th className="text1">Delivery Type</th>
                <th className="text1">Delivery City</th>
                <th className="text1">Delivery Pincode</th>
                <th className="text1">Cancelled At</th>
                <th className="text1">Cancelled By</th>
                <th className="text1">Cancellation/Return Reason</th>
                <th className="text1">Total Refund Amount</th>
              </tr>
            </thead>
            <tbody>
              {console.log("checking order data", ordersData)}
              {!ordersData.length ? (
                <tr>
                  <td className="text-center" colSpan="33">
                    No data available
                  </td>
                </tr>
              ) : (
                ordersData.map((row, index) =>
                  row.itemId.length ? (
                    row.itemId.map((item, idx) => (
                      <tr className="border1" key={idx + ""}>
                        <td>{++rowCount}</td>
                        <td>{row.buyerNPName}</td>
                        <td>
                          {new Date(row.orderCreateDateTime).toLocaleString()}
                        </td>
                        <td>
                          <Link to={`/order/${row.networkOrderId}`}>
                            {row.networkOrderId}
                          </Link>
                        </td>
                        <td>{row.networkTransactionId}</td>
                        <td>{row.sellerNPOrderId}</td>
                        <td>{row.itemId[idx]}</td>
                        <td>{row.qty[idx]}</td>
                        <td>{row.sellerNPType}</td>
                        <td>{row.sellerName}</td>
                        <td>{row.sellerPincode}</td>
                        <td>{row.sellerCity}</td>
                        <td>{row.skuCode[idx]}</td>
                        <td>{row.orderCategory}</td>
                        <td>{row.totalShippingCharges}</td>
                        <td>{row.totalOrderValue}</td>
                        <td>{row.buyerFinderFee}</td>
                        <td>{row.withHoldingAmount}</td>
                        <td>{row.orderStatus}</td>
                        <td>{row.readyToShipDateTime}</td>
                        <td>{row.shippedDateTime}</td>
                        <td>{row.logisticsSellerNPName}</td>
                        <td>{row.logisticsNetworkOrderId}</td>
                        <td>{row.logisticsNetworkTransactionId}</td>
                        <td>{row.deliveredDateTime}</td>
                        <td>{row.deliveryType}</td>
                        <td>{row.deliveryCity}</td>
                        <td>{row.deliveryPincode}</td>
                        <td>{row.cancelledDateTime}</td>
                        <td>{row.cancelledBy}</td>
                        <td>{row.cancellationReason}</td>
                        <td>{row.totalRefundAmount}</td>
                      </tr>
                    ))
                  ) : (
                    <tr className="border1" key={index}>
                      <td>{++rowCount}</td>
                      <td>{row.buyerNPName}</td>
                      <td>{row.orderCreateDateTime}</td>
                      <td>{row.networkOrderId}</td>
                      <td>{row.networkTransactionId}</td>
                      <td>{row.sellerNPOrderId}</td>
                      <td></td>
                      <td></td>
                      <td>{row.sellerNPType}</td>
                      <td>{row.sellerName}</td>
                      <td>{row.sellerPincode}</td>
                      <td>{row.sellerCity}</td>
                      <td></td>
                      <td>{row.orderCategory}</td>
                      <td>{row.totalShippingCharges}</td>
                      <td>{row.totalOrderValue}</td>
                      <td>{row.buyerFinderFee}</td>
                      <td>{row.withHoldingAmount}</td>
                      <td>{row.orderStatus}</td>
                      <td>{row.readyToShipDateTime}</td>
                      <td>{row.shippedDateTime}</td>
                      <td>{row.logisticsSellerNPName}</td>
                      <td>{row.logisticsNetworkOrderId}</td>
                      <td>{row.logisticsNetworkTransactionId}</td>
                      <td>{row.deliveredDateTime}</td>
                      <td>{row.deliveryType}</td>
                      <td>{row.deliveryCity}</td>
                      <td>{row.deliveryPincode}</td>
                      <td>{row.cancelledDateTime}</td>
                      <td>{row.cancelledBy}</td>
                      <td>{row.cancellationReason}</td>
                      <td>{row.totalRefundAmount}</td>
                    </tr>
                  )
                )
              )}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
};

export default OrderDashboard;
