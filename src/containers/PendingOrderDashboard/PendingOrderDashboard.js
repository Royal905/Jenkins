import React, { useEffect } from "react";
import { FaEdit } from "react-icons/fa";
import orderLogo from "../../assets/images/Order.png";
import { apiHandler } from "../../api";
import { endpoint } from "../../api/endpoint";
import { useSelector, useDispatch } from "react-redux";
import { Link } from "react-router-dom";
import { savePendingOrders } from "../../features/orders/ordersSlice";

const PendingOrderDashboard = () => {
  const dispatch = useDispatch();
  const pendingOrders = useSelector((state) => state.ordersSlice.pendingOrders);
  let rowCount = 0;

  const RETURN_REASON_CODES = {
    "001": "Buyer does not want product any more",
    "002": "Product available at lower than order price",
    "003": "Product damaged or not in usable state",
    "004": "Product is of incorrect quantity or size",
    "005": "Product delivered is different from what was shown and ordered",
  };

  useEffect(() => {
    const fetchPendingOrders = async () => {
      try {
        const pendingOrdersResponse = await apiHandler({
          url: endpoint.PENDING_REQUESTS,
          method: "GET",
        });

        if (pendingOrdersResponse && pendingOrdersResponse.data) {
          console.log("Pending Order Response:", pendingOrdersResponse);

          dispatch(savePendingOrders(pendingOrdersResponse.data));
        }
      } catch (error) {
        console.error("Error fetching pending orders:", error);
      }
    };

    fetchPendingOrders();
  }, [dispatch]);

  // const handleEditClick = (row) => {
  //   console.log("Edit clicked", row);
  // };

  return (
    <div className="containerRoot">
      <div className="main-content">
        <div style={{ display: "flex" }}>
          <h1 className="dashboard-heading m-4">
            <img className="dashboard-heading-logo" src={orderLogo} alt="" />{" "}
            Pending Orders
          </h1>
        </div>
        <div className="table-responsive gridTbl">
          <table className="table table-bordered table-striped table-hover">
            <thead>
              <tr className="border1">
                <th className="text1">S.No</th>
                <th className="text1">Order ID</th>
                <th className="text1">Return Reason</th>
                <th className="text1">Order Amount</th>
                <th className="text1">Action</th>
              </tr>
            </thead>
            <tbody>
              {!pendingOrders || pendingOrders.length === 0 ? (
                <tr>
                  <td className="text-center" colSpan="33">
                    No data available
                  </td>
                </tr>
              ) : (
                pendingOrders.map((item, idx) => (
                  <tr className="border1" key={idx + ""}>
                    <td>{++rowCount}</td>
                    <td>{item.order_id}</td>
                    <td>{RETURN_REASON_CODES[item.return_reason_id]}</td>
                    <td>{item.order_amount}</td>
                    <td>
                      <Link to={`/order/${item.order_id}`}>
                        {/* <div
                          style={{ cursor: "pointer" }}
                          onClick={() => handleEditClick(item)}
                        > */}
                        <FaEdit />
                        {/* </div> */}
                      </Link>
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
};

export default PendingOrderDashboard;
