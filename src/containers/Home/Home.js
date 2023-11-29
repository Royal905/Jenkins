import React, { useEffect, useState } from "react";
import ondcBanner from "../../assets/images/ONDC_Banner.png";
import cardCircle from "../../assets/images/circle.svg";
import "./Home.css";
import { useSelector } from "react-redux";
import { Link } from "react-router-dom";

const Home = () => {
  const { ordersData } = useSelector((state) => state.ordersSlice);
  const [ordersSummary, setOrdersSummary] = useState([]);

  useEffect(() => {
    if (!ordersData || !Array.isArray(ordersData)) {
      console.error("Invalid Data:", ordersData);
      return;
    }
    const groupDataByOrder = () => {
      const groupedData = {};

      ordersData.forEach((order) => {
        const {
          buyerNPName,
          totalOrderValue,
          withHoldingAmount,
          cancelledBy,
          totalRefundAmount,
          buyerFinderFee,
        } = order;

        if (!groupedData[buyerNPName]) {
          groupedData[buyerNPName] = {
            overAllOrdersValue: 0,
            totalOrders: 0,
            totalBuyerFinderFee: 0,
            totalWithHoldingAmount: 0,
            totalCancelledOrders: 0,
            totalRefundAmount: 0,
          };
        }

        groupedData[buyerNPName].overAllOrdersValue +=
          parseInt(totalOrderValue);
        groupedData[buyerNPName].totalOrders++;
        groupedData[buyerNPName].totalBuyerFinderFee +=
          parseInt(buyerFinderFee);
        groupedData[buyerNPName].totalWithHoldingAmount += withHoldingAmount
          ? parseInt(withHoldingAmount)
          : 0;
        groupedData[buyerNPName].totalCancelledOrders = cancelledBy
          ? groupedData[buyerNPName].totalCancelledOrders + 1
          : groupedData[buyerNPName].totalCancelledOrders;
        groupedData[buyerNPName].totalRefundAmount +=
          parseInt(totalRefundAmount);
      });

      const ordersSummary = Object.keys(groupedData).map((buyerNPName) => ({
        buyerNPName,
        overAllOrdersValue: groupedData[buyerNPName].overAllOrdersValue,
        totalOrders: groupedData[buyerNPName].totalOrders,
        totalAmountRecieved:
          groupedData[buyerNPName].overAllOrdersValue -
          groupedData[buyerNPName].totalWithHoldingAmount -
          groupedData[buyerNPName].totalBuyerFinderFee,
        totalBuyerFinderFee: groupedData[buyerNPName].totalBuyerFinderFee,
        totalWithHoldingAmount: groupedData[buyerNPName].totalWithHoldingAmount,
        totalCancelledOrders: groupedData[buyerNPName].totalCancelledOrders,
        totalRefundAmount: groupedData[buyerNPName].totalRefundAmount,
      }));

      return ordersSummary;
    };

    const summary = groupDataByOrder();
    setOrdersSummary(summary);
  }, []);

  let totalSummary = {
    overAllOrdersValue: 0,
    totalAmountRecieved: 38816,
    totalBuyerFinderFee: 1200,
    totalCancelledOrders: 0,
    totalOrders: 4,
    totalRefundAmount: 0,
    totalWithHoldingAmount: 0,
  };

  totalSummary = ordersSummary.reduce(
    (acc, order) => {
      acc.totalOrders += order.totalOrders;
      acc.overAllOrdersValue += order.overAllOrdersValue;
      acc.totalAmountRecieved += order.totalAmountRecieved;
      acc.totalBuyerFinderFee += order.totalBuyerFinderFee;
      acc.totalWithHoldingAmount += order.totalWithHoldingAmount;
      acc.totalCancelledOrders += order.totalCancelledOrders;
      acc.totalRefundAmount += order.totalRefundAmount;
      return acc;
    },
    {
      totalOrders: 0,
      overAllOrdersValue: 0,
      totalAmountRecieved: 0,
      totalBuyerFinderFee: 0,
      totalWithHoldingAmount: 0,
      totalCancelledOrders: 0,
      totalRefundAmount: 0,
    }
  );

  return (
    <>
      <div className="HomePageRoot">
        <h1 className="m-4">Dashboard</h1>
        <Link to={"/storeView"}>
          <button className="storeConfiguration m-4">
            Store Configuration
          </button>
        </Link>
      </div>

      {/* <img className = "BannerCss" src={cardCircle} alt="Banner"/> */}
      <div className="row text-align-center">
        <div className="col-2">
          <div className="card bg-gradient-success card-img-holder  text-white  o-hidden h-100">
            <div className="card-body">
              <img
                className="card-img-absolute"
                src={cardCircle}
                alt="Banner"
              />
              <div className="text-center card-font-size">
                Total Orders
                <br />
                <h2>{totalSummary.totalOrders}</h2>
              </div>
            </div>
          </div>
        </div>
        <div className="col-2">
          <div className="card bg-gradient-info card-img-holder  text-white  o-hidden h-100">
            <div className="card-body">
              <img
                className="card-img-absolute"
                src={cardCircle}
                alt="Banner"
              />
              <div className="text-center card-font-size">
                Total Orders Value
                <br />{" "}
                <h2>
                  {totalSummary.overAllOrdersValue === 0
                    ? totalSummary.overAllOrdersValue
                    : `₹${totalSummary.overAllOrdersValue.toLocaleString(
                        "en-IN"
                      )}`}
                </h2>
              </div>
            </div>
          </div>
        </div>
        <div className="col-2">
          <div className="card bg-gradient-amount card-img-holder  text-white  o-hidden h-100">
            <div className="card-body">
              <img
                className="card-img-absolute"
                src={cardCircle}
                alt="Banner"
              />
              <div className="text-center card-font-size">
                Total Amount Recieved
                <br />{" "}
                <h2>
                  {totalSummary.totalAmountRecieved === 0
                    ? totalSummary.totalAmountRecieved
                    : `₹${totalSummary.totalAmountRecieved.toLocaleString(
                        "en-IN"
                      )}`}
                </h2>
              </div>
            </div>
          </div>
        </div>

        <div className="col-2">
          <div className="card bg-gradient-danger card-img-holder  text-white  o-hidden h-100">
            <div className="card-body">
              <img
                className="card-img-absolute"
                src={cardCircle}
                alt="Banner"
              />
              <div className="text-center card-font-size">
                Pending Withholding Amount
                <br />{" "}
                <h2>
                  {totalSummary.totalWithHoldingAmount === 0
                    ? totalSummary.totalWithHoldingAmount
                    : `₹${totalSummary.totalWithHoldingAmount.toLocaleString(
                        "en-IN"
                      )}`}
                </h2>
              </div>
            </div>
          </div>
        </div>
        <div className="col-2">
          <div className="card bg-gradient-pending card-img-holder  text-white  o-hidden h-100">
            <div className="card-body">
              <img
                className="card-img-absolute"
                src={cardCircle}
                alt="Banner"
              />
              <div className="text-center card-font-size">
                Total Cancelled Orders
                <br /> <h2>{totalSummary.totalCancelledOrders}</h2>
              </div>
            </div>
          </div>
        </div>
        <div className="col-2">
          <div className="card bg-gradient-refund card-img-holder  text-white  o-hidden h-100">
            <div className="card-body">
              <img
                className="card-img-absolute"
                src={cardCircle}
                alt="Banner"
              />
              <div className="text-center card-font-size">
                Total Amount Refunded
                <br />{" "}
                <h2>
                  {totalSummary.totalRefundAmount === 0
                    ? totalSummary.totalRefundAmount
                    : `₹${totalSummary.totalRefundAmount.toLocaleString(
                        "en-IN"
                      )}`}
                </h2>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div className="table-responsive gridTbl  m-3">
        <table className="table table-bordered table-striped table-hover">
          <thead>
            <tr className="border1">
              <th className="text1">Buyer Name</th>
              <th className="text1">Total Orders</th>
              <th className="text1">Total Orders Value</th>
              <th className="text1">Total Amount Recieved</th>
              <th className="text1">Total Buyer Finder Fee</th>
              <th className="text1">Pending Withholding Amount</th>
              <th className="text1">Total Cancelled Orders</th>
              <th className="text1">Total Amount Refunded</th>
            </tr>
          </thead>
          <tbody>
            {ordersSummary.length === 0 ? (
              <tr>
                <td className="text-center" colSpan="18">
                  No data available
                </td>
              </tr>
            ) : (
              ordersSummary.map((buyer, index) => {
                return (
                  <tr className="border1">
                    <td>{buyer.buyerNPName}</td>
                    <td>{buyer.totalOrders}</td>
                    <td>
                      {buyer.overAllOrdersValue === 0
                        ? buyer.overAllOrdersValue
                        : `₹${buyer.overAllOrdersValue.toLocaleString(
                            "en-IN"
                          )}`}
                    </td>
                    <td>
                      {buyer.totalAmountRecieved === 0
                        ? buyer.totalAmountRecieved
                        : `₹${buyer.totalAmountRecieved.toLocaleString(
                            "en-IN"
                          )}`}
                    </td>
                    <td>
                      {buyer.totalBuyerFinderFee === 0
                        ? buyer.totalBuyerFinderFee
                        : `₹${buyer.totalBuyerFinderFee.toLocaleString(
                            "en-IN"
                          )}`}
                    </td>
                    <td>
                      {buyer.totalWithHoldingAmount === 0
                        ? buyer.totalWithHoldingAmount
                        : `₹${buyer.totalWithHoldingAmount.toLocaleString(
                            "en-IN"
                          )}`}
                    </td>
                    <td>{buyer.totalCancelledOrders}</td>
                    <td>
                      {buyer.totalRefundAmount === 0
                        ? buyer.totalRefundAmount
                        : `₹${buyer.totalRefundAmount.toLocaleString("en-IN")}`}
                    </td>
                  </tr>
                );
              })
            )}
          </tbody>
        </table>
      </div>
    </>
  );
};

export default Home;
