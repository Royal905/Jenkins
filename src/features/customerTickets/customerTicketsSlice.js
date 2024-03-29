import { createSlice } from "@reduxjs/toolkit";

export const customerTicketsSlice = createSlice({
  name: "customerTicketsSlice",
  initialState: {
    customerTicketsData: [],
  },
  reducers: {
    // Redux Toolkit allows us to write "mutating" logic in reducers. It
    // doesn't actually mutate the state because it uses the immer library,
    // which detects changes to a "draft state" and produces a brand new
    // immutable state based off those changes
    saveCustomerTickets: (state, action) => {
      state.customerTicketsData = action.payload;
    },
  },
});

export const { saveCustomerTickets } = customerTicketsSlice.actions;

export default customerTicketsSlice.reducer;
