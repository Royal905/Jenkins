import React, { useEffect, useState } from "react";
import { useSelector } from "react-redux";
import { endpoint } from "../../api/endpoint";
import { apiHandler } from "../../api";
import { useNavigate, useParams } from "react-router-dom";

const UpdateTicket = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const { customerTicketData } = useSelector(
    (state) => state.customerTicketSlice
  );

  const [supportPersons, setSupportPersons] = useState({
    supportPersons: [],
    groPersons: [],
  });

  useEffect(async () => {
    const result = await apiHandler({
      url: endpoint.SUPPORT_PERSONS,
      method: "GET",
    });

    if (result && result.data) {
      setSupportPersons(result.data);
    }
  }, []);

  const [updatedTicketData, setUpdatedticketData] = useState({
    supportPerson: {
      id: null,
      name: "",
      email: "",
      phone: "",
    },
    groPerson: {
      id: null,
      name: "",
      email: "",
      phone: "",
    },
    resolution: {
      shortDescription: "",
      longDescription: "",
      actionTriggered: "refund",
      refundAmount: "",
    },
  });

  const handleSupportPersons = (e) => {
    const selectedSupportPersonId = e.target.value;
    const selectedSupportPersonDetails = supportPersons.supportPersons.find(
      (person) => person.id === parseInt(selectedSupportPersonId)
    );

    setUpdatedticketData({
      ...updatedTicketData,
      supportPerson: {
        id: selectedSupportPersonDetails.id,
        name: selectedSupportPersonDetails.name,
        email: selectedSupportPersonDetails.email,
        phone: selectedSupportPersonDetails.phone,
      },
    });
  };

  const handleGroPersons = (e) => {
    const selectedGroPersonId = e.target.value;
    const selectedGroPersonDetails = supportPersons.groPersons.find(
      (person) => person.id === parseInt(selectedGroPersonId)
    );

    setUpdatedticketData({
      ...updatedTicketData,
      groPerson: {
        id: selectedGroPersonDetails.id,
        name: selectedGroPersonDetails.name,
        email: selectedGroPersonDetails.email,
        phone: selectedGroPersonDetails.phone,
      },
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    console.log("submitData", updatedTicketData);

    const result = await apiHandler({
      url: endpoint.UPDATE_TICKET + id,
      method: "POST",
      data: updatedTicketData,
    });

    console.log("result", result);
    navigate(`/ticket/${id}`);
  };

  return (
    <div className="storeViewRoot">
      <>
        <div className="container">
          <div className="pt-3 px-2 d-flex justify-content-between align-items-center">
            <div>
              <h2>My Form</h2>
            </div>
          </div>
        </div>
        <hr />
        <form onSubmit={handleSubmit}>
          <div className="container">
            <div className="row">
              <div className="row">
                <div className="col-4">
                  <div className="">
                    <label>Support Person:</label>
                  </div>
                  <div className="">
                    <select
                      value={updatedTicketData.supportPerson.id}
                      onChange={handleSupportPersons}
                    >
                      <option value="">Select Support Person</option>
                      {supportPersons.supportPersons.map((person, index) => (
                        <option value={person.id} key={index}>
                          {person.name}
                        </option>
                      ))}
                    </select>
                  </div>
                </div>
                <div className="col-4">
                  <div className="">
                    <label>GRO Person:</label>
                  </div>
                  <div className="">
                    <select
                      value={updatedTicketData.groPerson.id}
                      onChange={handleGroPersons}
                    >
                      <option value="">Select GRO Person</option>
                      {supportPersons.groPersons.map((person, index) => (
                        <option value={person.id} key={index}>
                          {person.name}
                        </option>
                      ))}
                    </select>
                  </div>
                </div>
                <div className="col-4"></div>
              </div>
            </div>
            <hr />
            <h4>Resolution</h4>
            <div className="row">
              <div className="col-4">
                <div className="">
                  <label>Short Description:</label>
                </div>
                <div className="">
                  <input
                    type="text"
                    value={updatedTicketData.resolution.shortDescription}
                    onChange={(e) =>
                      setUpdatedticketData({
                        ...updatedTicketData,
                        resolution: {
                          ...updatedTicketData.resolution,
                          shortDescription: e.target.value,
                        },
                      })
                    }
                  />
                </div>
              </div>
              <div className="col-4">
                <div className="">
                  <label>Long Description:</label>
                </div>
                <div className="">
                  <input
                    type="text"
                    value={updatedTicketData.resolution.longDescription}
                    onChange={(e) =>
                      setUpdatedticketData({
                        ...updatedTicketData,
                        resolution: {
                          ...updatedTicketData.resolution,
                          longDescription: e.target.value,
                        },
                      })
                    }
                  />
                </div>
              </div>
              <div className="col-4"></div>
            </div>
            <div className="row">
              <div className="col-4">
                <div className="">
                  <label>Action Triggered:</label>
                </div>
                <div className="">
                  <select
                    value={updatedTicketData.resolution.actionTriggered}
                    onChange={(e) =>
                      setUpdatedticketData({
                        ...updatedTicketData,
                        resolution: {
                          ...updatedTicketData.resolution,
                          actionTriggered: e.target.value,
                        },
                      })
                    }
                  >
                    <option value="refund">Refund</option>
                    <option value="replacement">Replacement</option>
                    <option value="cancel">Cancel</option>
                  </select>
                </div>
              </div>
              {updatedTicketData.resolution.actionTriggered === "refund" && (
                <div className="col-4">
                  <div className="">
                    <label>Refund Amount:</label>
                  </div>
                  <div className="">
                    <input
                      type="text"
                      value={updatedTicketData.resolution.refundAmount}
                      onChange={(e) =>
                        setUpdatedticketData({
                          ...updatedTicketData,
                          resolution: {
                            ...updatedTicketData.resolution,
                            refundAmount: e.target.value,
                          },
                        })
                      }
                    />
                  </div>
                </div>
              )}
              <div className="col-4"></div>
            </div>
            <div>
              <button type="submit">Submit</button>
            </div>
          </div>
        </form>
      </>
    </div>
  );
};

export default UpdateTicket;
