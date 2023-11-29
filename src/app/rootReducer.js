import { combineReducers } from "redux";
import loginSliceReducer from "../features/login/LoginSlice";
import ordersSliceReducer from "../features/orders/ordersSlice";
import customerTicketsReducer from "../features/customerTickets/customerTicketsSlice";
import customerTicketReducer from "../features/customerTickets/customerTicketSlice";
import appLoaderReducer from "../features/appLoader/appLoaderSlice";

export default combineReducers({
  loginSlice: loginSliceReducer,
  ordersSlice: ordersSliceReducer,
  customerTicketsSlice: customerTicketsReducer,
  customerTicketSlice: customerTicketReducer,
  appLoaderSlice: appLoaderReducer,
});
