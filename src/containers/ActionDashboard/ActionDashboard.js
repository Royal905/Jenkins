import React, { useEffect } from "react";
import { useParams } from "react-router-dom";

const ActionDashboard = () => {
  const { id } = useParams();

  const handleButtonClick = (action) => {
    console.log(`Action: ${action}`);
  };

  return (
    <div>
      <div className="storeViewRoot">
        <>
          <div className="container">
            <div className="pt-3 px-2 d-flex justify-content-between align-items-center">
              <div>
                <h2>Order Details</h2>
              </div>
              <div style={{ display: "flex" }}>
                <p
                  className="py-2 px-2 mx-2 saveButton"
                  onClick={() => handleButtonClick("Return_Approved")}
                >
                  Return Approved
                </p>
                <p
                  className="py-2 px-2  mx-2 saveButton"
                  onClick={() => handleButtonClick("Return_Reject")}
                >
                  Return Reject
                </p>
                <p
                  className="py-2 px-2  mx-2 saveButton"
                  onClick={() => handleButtonClick("Liquidated")}
                >
                  Liquidated
                </p>
              </div>
            </div>
          </div>
          <hr />
          <p>Order ID: {id}</p>
          {console.log("ID:", id)}
        </>
      </div>
    </div>
  );
};

export default ActionDashboard;
