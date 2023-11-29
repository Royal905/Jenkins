import { useCallback, useState } from "react";
import { useDispatch } from "react-redux";
import { saveAuthData, saveUserData } from "../../features/login/LoginSlice";
import { endpoint } from "../../api/endpoint";
import { apiHandler } from "../../api";

export const useLogin = (props) => {
  const dispatch = useDispatch();
  const [email, setEmail] = useState("");
  const [emailError, setEmailError] = useState(false);
  const [password, setPassword] = useState("");
  const [passwordError, setPasswordError] = useState(false);

  const [loginError, setLoginError] = useState(false);

  const isValidEmail = () => {
    if (email.trim() !== "") {
      var emailCheck = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
      if (!emailCheck.test(email.trim())) {
        setEmailError(
          "Email should be valid and not contain special characters other than . and @."
        );
        return false;
      } else {
        setEmailError("");
        return true;
      }
    } else if (email.trim() === "") {
      setEmailError("This is a Required Field! ");
      return false;
    } else {
      setEmailError("");
      return true;
    }
  };

  const isValidPassword = () => {
    if (password.trim() === "") {
      setPasswordError("This is a Required Field! ");
      return false;
    } else {
      setPasswordError("");
      return true;
    }
  };

  const validate = () => {
    if (isValidEmail() && isValidPassword()) {
      return true;
    } else {
      isValidEmail();
      isValidPassword();
    }
    return false;
  };

  const requestAccessToken = async () => {
    const result = await apiHandler({
      url: endpoint.LOGIN,
      method: "POST",
      data: {
        email: email,
        password: password,
      },
    });
    return result;
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    const ManualLogin = true;
    if (ManualLogin) {
      dispatch(saveAuthData(true));
      return;
    }

    if (!validate()) {
      return;
    }

    const response = await requestAccessToken(email, password);

    if (response.data.status === true) {
      dispatch(saveAuthData(response.data.result.accessToken));
      dispatch(saveUserData(response.data.result));
    } else {
      setLoginError("Invalid Credentials");
    }
  };

  return {
    email,
    setEmail,
    emailError,
    isValidEmail,
    password,
    setPassword,
    passwordError,
    isValidPassword,
    handleSubmit,
    loginError,
  };
};
