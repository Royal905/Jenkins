import { apiHandler } from "./index";
import axios from "axios";
import { saveOrdes } from "../features/orders/ordersSlice";
import { endpoint } from "./endpoint";
import { saveCustomerTickets } from "../features/customerTickets/customerTicketsSlice";

export const getAppData = ({ dispatch }) => {
  return axios
    .all([
      apiHandler({ url: endpoint.ORDERS }),
      apiHandler({
        url: endpoint.CUSTOMER_TICKETS,
      }),
    ])
    .then(
      axios.spread((ordersData, ticketsData) => {
        dispatch(saveOrdes(ordersData.data));
        dispatch(saveCustomerTickets(ticketsData.data));

        return {
          ordersData,
          ticketsData,
        };
      })
    );
};
