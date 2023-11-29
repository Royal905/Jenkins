import React, { useEffect, useState } from "react";
import { apiHandler } from "../../api";
import { endpoint } from "../../api/endpoint";
import { FaToggleOn, FaToggleOff } from "react-icons/fa";
import { BsPlusCircleDotted } from "react-icons/bs";
import { RiDeleteBin6Line } from "react-icons/ri";
import "./Storeview.css";

const StoreView = () => {
  const [storeDetails, setStoreDetails] = useState([]);

  const [storeImages, setStoreImages] = useState([]);
  const [storeImagesLength, setStoreImagesLength] = useState(
    storeImages.length > 0 ? storeImages.length > 0 : 1
  );
  useEffect(async () => {
    const result = await apiHandler({
      url: endpoint.GET_STORE_DETAILS,
      method: "GET",
    });

    if (result && result.data) {
      setStoreDetails(result.data);
      setStoreImages(result.data[0].bpp_images);
      setStoreImagesLength(result.data[0].bpp_images.length);
    }
  }, []);

  console.log("storeDetails", storeDetails);

  const handleUpdate = async () => {
    const reqBody = {
      ...storeDetails[0],
      bpp_images: storeImages,
    };

    const result = await apiHandler({
      url: endpoint.UPDATE_STORE_DETAILS,
      method: "POST",
      data: reqBody,
    });

    console.log("result", result);
  };

  const addInputField = () => {
    setStoreImagesLength(storeImagesLength + 1);
    setStoreImages([...storeImages, ""]);
  };

  // Function to update the array when an input field changes
  const handleInputChange = (index, value) => {
    const updatedArray = [...storeImages];
    updatedArray[index] = value;
    setStoreImages(updatedArray);
  };

  // Function to remove an input field
  const removeInputField = (index) => {
    const updatedLength = storeImagesLength - 1;
    setStoreImagesLength(updatedLength > 0 ? updatedLength : 1);
    const updatedArray = [...storeImages];
    updatedArray.splice(index, 1);
    setStoreImages(updatedArray.length ? updatedArray : [""]);
  };

  return (
    <div className="storeViewRoot">
      {storeDetails.length ? (
        <>
          <div className="container">
            <div className="pt-3 px-2 d-flex justify-content-between align-items-center">
              <div>
                <h2>Store Configuration</h2>
              </div>
              <div>
                <button className="py-2 px-4 saveButton" onClick={handleUpdate}>
                  Save
                </button>
              </div>
            </div>
          </div>
          <hr />
          <div className="container">
            <div className="row">
              {/*  */}
              <div className="col-4">Store Status</div>
              <div className="col-8">
                <div
                  className="toggleBtn"
                  onClick={() =>
                    setStoreDetails([
                      {
                        ...storeDetails[0],
                        store_active: !storeDetails[0].store_active,
                      },
                    ])
                  }
                >
                  {storeDetails[0].store_active ? (
                    <FaToggleOn size={40} color={"green"} />
                  ) : (
                    <FaToggleOff size={40} color={"#cd3e3e"} />
                  )}
                </div>
              </div>
              {/*  */}
              <div className="row">
                <div className="col-4">
                  <div className="">
                    <label>Store Name</label>
                  </div>
                  <div className="">
                    <input
                      onChange={(e) =>
                        setStoreDetails([
                          {
                            ...storeDetails[0],
                            bpp_name: e.target.value,
                          },
                        ])
                      }
                      value={storeDetails[0].bpp_name}
                    ></input>
                  </div>
                </div>
                {/*  */}
                <div className="col-4">
                  <div className="">
                    <label>Store Symbol</label>
                  </div>
                  <div className="">
                    <input
                      onChange={(e) =>
                        setStoreDetails([
                          {
                            ...storeDetails[0],
                            bpp_symbol: e.target.value,
                          },
                        ])
                      }
                      value={storeDetails[0].bpp_symbol}
                    ></input>
                  </div>
                </div>
                <div className="col-4">
                  {/*  */}
                  <div className="">
                    <label>Store Images</label>
                  </div>
                  <div className="">
                    {/* <button onClick={addInputField}>Add</button> */}
                    {storeImages.map((value, index) => (
                      <div key={index} className="imagesFields">
                        <input
                          type="text"
                          value={value ? value : ""}
                          onChange={(e) =>
                            handleInputChange(index, e.target.value)
                          }
                        />
                        {/* <div> */}
                        {index === storeImages.length - 1 ? (
                          <button onClick={addInputField}>
                            <BsPlusCircleDotted />
                          </button>
                        ) : null}
                        {storeImages.length != 1 ? (
                          <button onClick={() => removeInputField(index)}>
                            <RiDeleteBin6Line />
                          </button>
                        ) : null}
                        {/* </div> */}
                      </div>
                    ))}
                  </div>
                </div>
              </div>
              {/*  */}
              <div className="row">
                <div className="col-4">
                  <div className="">
                    <label>Short Description</label>
                  </div>
                  <div className="">
                    <input
                      onChange={(e) =>
                        setStoreDetails([
                          {
                            ...storeDetails[0],
                            bpp_short_desc: e.target.value,
                          },
                        ])
                      }
                      value={storeDetails[0].bpp_short_desc}
                    ></input>
                  </div>
                </div>
                {/*  */}
                <div className="col-4">
                  <div className="">
                    <label>Long Description</label>
                  </div>
                  <div className="">
                    <input
                      onChange={(e) =>
                        setStoreDetails([
                          {
                            ...storeDetails[0],
                            bpp_long_desc: e.target.value,
                          },
                        ])
                      }
                      value={storeDetails[0].bpp_long_desc}
                    ></input>
                  </div>
                </div>
                {/*  */}
                <div className="col-4">
                  <div className="">
                    <label for="openingTime">Store Opening Time</label>
                  </div>
                  <div className="">
                    <input
                      type="time"
                      id="openingTime"
                      name="openingTime"
                      onChange={(e) =>
                        setStoreDetails([
                          {
                            ...storeDetails[0],
                            store_opening_time: e.target.value,
                          },
                        ])
                      }
                      value={storeDetails[0].store_opening_time}
                    ></input>
                  </div>
                </div>
              </div>
              <div className="row">
                <div className="col-4">
                  <div className="">
                    <label for="closingTime">Store Closing Time</label>
                  </div>
                  <div className="">
                    <input
                      type="time"
                      id="closingTime"
                      name="closingTime"
                      onChange={(e) =>
                        setStoreDetails([
                          {
                            ...storeDetails[0],
                            store_closing_time: e.target.value,
                          },
                        ])
                      }
                      value={storeDetails[0].store_closing_time}
                    ></input>
                  </div>
                </div>
                <div className="col-4">
                  <div className="">
                    <label>Locality</label>
                  </div>
                  <div className="">
                    <input
                      onChange={(e) =>
                        setStoreDetails([
                          {
                            ...storeDetails[0],
                            store_address: {
                              ...storeDetails[0].store_address,
                              locality: e.target.value,
                            },
                          },
                        ])
                      }
                      value={storeDetails[0].store_address.locality}
                    ></input>
                  </div>
                </div>
                {/*  */}
                <div className="col-4">
                  <div className="">
                    <label>Street</label>
                  </div>
                  <div className="">
                    <input
                      onChange={(e) =>
                        setStoreDetails([
                          {
                            ...storeDetails[0],
                            store_address: {
                              ...storeDetails[0].store_address,
                              street: e.target.value,
                            },
                          },
                        ])
                      }
                      value={storeDetails[0].store_address.street}
                    ></input>
                  </div>
                </div>
              </div>
              {/*  */}
              <div className="row">
                <div className="col-4">
                  <div className="">
                    <label>City</label>
                  </div>
                  <div className="">
                    <input
                      onChange={(e) =>
                        setStoreDetails([
                          {
                            ...storeDetails[0],
                            store_address: {
                              ...storeDetails[0].store_address,
                              city: e.target.value,
                            },
                          },
                        ])
                      }
                      value={storeDetails[0].store_address.city}
                    ></input>
                  </div>
                </div>
                {/*  */}
                <div className="col-4">
                  <div className="">
                    <label>State</label>
                  </div>
                  <div className="">
                    <input
                      onChange={(e) =>
                        setStoreDetails([
                          {
                            ...storeDetails[0],
                            store_address: {
                              ...storeDetails[0].store_address,
                              state: e.target.value,
                            },
                          },
                        ])
                      }
                      value={storeDetails[0].store_address.state}
                    ></input>
                  </div>
                </div>
                {/*  */}
                <div className="col-4">
                  <div className="">
                    <label>Area Code</label>
                  </div>
                  <div className="">
                    <input
                      onChange={(e) =>
                        setStoreDetails([
                          {
                            ...storeDetails[0],
                            store_address: {
                              ...storeDetails[0].store_address,
                              area_code: e.target.value,
                            },
                          },
                        ])
                      }
                      value={storeDetails[0].store_address.area_code}
                    ></input>
                  </div>
                </div>
              </div>
              {/*  */}
              <div className="row">
                <div className="col-4">
                  <div className="">
                    <label>Latitude</label>
                  </div>
                  <div className="">
                    <input
                      onChange={(e) =>
                        setStoreDetails([
                          {
                            ...storeDetails[0],
                            store_address: {
                              ...storeDetails[0].store_address,
                              latitude: e.target.value,
                            },
                          },
                        ])
                      }
                      value={storeDetails[0].store_address.latitude}
                    ></input>
                  </div>
                  {/*  */}
                </div>
                <div className="col-4">
                  <div className="">
                    <label>Longitude</label>
                  </div>
                  <div className="">
                    <input
                      onChange={(e) =>
                        setStoreDetails([
                          {
                            ...storeDetails[0],
                            store_address: {
                              ...storeDetails[0].store_address,
                              longitude: e.target.value,
                            },
                          },
                        ])
                      }
                      value={storeDetails[0].store_address.longitude}
                    ></input>
                  </div>
                </div>
                {/*  */}
                <div className="col-4">
                  <div className="">
                    <label>Store Email</label>
                  </div>
                  <div className="">
                    <input
                      onChange={(e) =>
                        setStoreDetails([
                          {
                            ...storeDetails[0],
                            store_email: e.target.value,
                          },
                        ])
                      }
                      value={storeDetails[0].store_email}
                    ></input>
                  </div>
                </div>
              </div>
              {/*  */}
              <div className="row">
                <div className="col-4">
                  <div className="">
                    <label>Store Contact No</label>
                  </div>
                  <div className="">
                    <input
                      onChange={(e) =>
                        setStoreDetails([
                          {
                            ...storeDetails[0],
                            store_phone_number: e.target.value,
                          },
                        ])
                      }
                      value={storeDetails[0].store_phone_number}
                    ></input>
                  </div>
                </div>
                {/*  */}
                <div className="col-4">
                  <div className="">
                    <label>Support Email</label>
                  </div>
                  <div className="">
                    <input
                      onChange={(e) =>
                        setStoreDetails([
                          {
                            ...storeDetails[0],
                            support_email: e.target.value,
                          },
                        ])
                      }
                      value={storeDetails[0].support_email}
                    ></input>
                  </div>
                </div>
                {/*  */}
                <div className="col-4">
                  <div className="">
                    <label>Support Contact No</label>
                  </div>
                  <div className="">
                    <input
                      onChange={(e) =>
                        setStoreDetails([
                          {
                            ...storeDetails[0],
                            support_phone_number: e.target.value,
                          },
                        ])
                      }
                      value={storeDetails[0].support_phone_number}
                    ></input>
                  </div>
                </div>
              </div>
              {/*  */}
              <div className="row">
                <div className="col-4">
                  <div className="">
                    <label>Is Products Returnable</label>
                  </div>
                  <div className="">
                    <div
                      className="toggleBtn"
                      onClick={() =>
                        setStoreDetails([
                          {
                            ...storeDetails[0],
                            is_products_returnable:
                              !storeDetails[0].is_products_returnable,
                          },
                        ])
                      }
                    >
                      {storeDetails[0].is_products_returnable ? (
                        <FaToggleOn size={40} color={"green"} />
                      ) : (
                        <FaToggleOff size={40} color={"#cd3e3e"} />
                      )}
                    </div>
                  </div>
                </div>
                <div className="col-4">
                  {/*  */}
                  <div className="">
                    <label>Is Seller Pickup Return</label>
                  </div>
                  <div className="">
                    <div
                      className="toggleBtn"
                      onClick={() =>
                        setStoreDetails([
                          {
                            ...storeDetails[0],
                            is_seller_pickup_return:
                              !storeDetails[0].is_seller_pickup_return,
                          },
                        ])
                      }
                    >
                      {storeDetails[0].is_seller_pickup_return ? (
                        <FaToggleOn size={40} color={"green"} />
                      ) : (
                        <FaToggleOff size={40} color={"#cd3e3e"} />
                      )}
                    </div>
                  </div>
                </div>
              </div>
              {/*  */}
            </div>
            <hr />
            <h4>Bank Details</h4>
            <div className="row">
              <div className="col-4">
                <div className="">
                  <label>Beneficiary Name</label>
                </div>
                <div className="">
                  <input
                    onChange={(e) =>
                      setStoreDetails([
                        {
                          ...storeDetails[0],
                          store_bank_details: {
                            ...storeDetails[0].store_bank_details,
                            beneficiary_name: e.target.value,
                          },
                        },
                      ])
                    }
                    value={storeDetails[0].store_bank_details.beneficiary_name}
                  ></input>
                </div>
              </div>
              <div className="col-4">
                <div className="">
                  <label>Upi Address</label>
                </div>
                <div className="">
                  <input
                    onChange={(e) =>
                      setStoreDetails([
                        {
                          ...storeDetails[0],
                          store_bank_details: {
                            ...storeDetails[0].store_bank_details,
                            upi_address: e.target.value,
                          },
                        },
                      ])
                    }
                    value={storeDetails[0].store_bank_details.upi_address}
                  ></input>
                </div>
              </div>
              <div className="col-4">
                <div className="">
                  <label>Account Number</label>
                </div>
                <div className="">
                  <input
                    onChange={(e) =>
                      setStoreDetails([
                        {
                          ...storeDetails[0],
                          store_bank_details: {
                            ...storeDetails[0].store_bank_details,
                            settlement_bank_account_no: e.target.value,
                          },
                        },
                      ])
                    }
                    value={
                      storeDetails[0].store_bank_details
                        .settlement_bank_account_no
                    }
                  ></input>
                </div>
              </div>
            </div>
            <div className="row">
              <div className="col-4">
                <div className="">
                  <label>IFSE Code</label>
                </div>
                <div className="">
                  <input
                    onChange={(e) =>
                      setStoreDetails([
                        {
                          ...storeDetails[0],
                          store_bank_details: {
                            ...storeDetails[0].store_bank_details,
                            settlement_ifsc_code: e.target.value,
                          },
                        },
                      ])
                    }
                    value={
                      storeDetails[0].store_bank_details.settlement_ifsc_code
                    }
                  ></input>
                </div>
              </div>
              <div className="col-4">
                <div className="">
                  <label>Name of the Bank</label>
                </div>
                <div className="">
                  <input
                    onChange={(e) =>
                      setStoreDetails([
                        {
                          ...storeDetails[0],
                          store_bank_details: {
                            ...storeDetails[0].store_bank_details,
                            bank_name: e.target.value,
                          },
                        },
                      ])
                    }
                    value={storeDetails[0].store_bank_details.bank_name}
                  ></input>
                </div>
              </div>
              <div className="col-4">
                <div className="">
                  <label>Name of the Branch</label>
                </div>
                <div className="">
                  <input
                    onChange={(e) =>
                      setStoreDetails([
                        {
                          ...storeDetails[0],
                          store_bank_details: {
                            ...storeDetails[0].store_bank_details,
                            branch_name: e.target.value,
                          },
                        },
                      ])
                    }
                    value={storeDetails[0].store_bank_details.branch_name}
                  ></input>
                </div>
              </div>
            </div>
          </div>
        </>
      ) : (
        <></>
      )}
    </div>
  );
};

export default StoreView;
