import { createSlice } from "@reduxjs/toolkit";

export const appLoaderSlice = createSlice({
  name: "appLoaderSlice",
  initialState: {
    appLoader: false,
  },
  reducers: {
    // Redux Toolkit allows us to write "mutating" logic in reducers. It
    // doesn't actually mutate the state because it uses the immer library,
    // which detects changes to a "draft state" and produces a brand new
    // immutable state based off those changes
    setappLoader: (state, action) => {
      state.appLoader = action.payload;
    },
  },
});

export const { setappLoader } = appLoaderSlice.actions;

export default appLoaderSlice.reducer;
