import { createSlice } from "@reduxjs/toolkit";

export const ordersSlice = createSlice({
  name: "ordersSlice",
  initialState: {
    ordersData: [],
    pendingOrderCount: 0,
    pendingOrders: [],
  },
  reducers: {
    // Redux Toolkit allows us to write "mutating" logic in reducers. It
    // doesn't actually mutate the state because it uses the immer library,
    // which detects changes to a "draft state" and produces a brand new
    // immutable state based off those changes
    saveOrdes: (state, action) => {
      state.ordersData = action.payload;
    },
    savePendingOrderCount: (state, action) => {
      state.pendingOrderCount = action.payload;
    },
    savePendingOrders: (state, action) => {
      state.pendingOrders = action.payload;
    },
  },
});

export const { saveOrdes, savePendingOrderCount, savePendingOrders } =
  ordersSlice.actions;

export default ordersSlice.reducer;
