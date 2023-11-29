import { createSlice } from "@reduxjs/toolkit";

export const customerTicketSlice = createSlice({
  name: "customerTicketSlice",
  initialState: {
    customerTicketData: [],
  },
  reducers: {
    // Redux Toolkit allows us to write "mutating" logic in reducers. It
    // doesn't actually mutate the state because it uses the immer library,
    // which detects changes to a "draft state" and produces a brand new
    // immutable state based off those changes
    saveCustomerTicket: (state, action) => {
      state.customerTicketData = action.payload;
    },
  },
});

export const { saveCustomerTicket } = customerTicketSlice.actions;

export default customerTicketSlice.reducer;
