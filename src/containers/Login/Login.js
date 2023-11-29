import React from "react";
import { GrMail } from "react-icons/gr";
import { AiFillLock } from "react-icons/ai";
import { BsArrowRight } from "react-icons/bs";
import { useLogin } from "../../customHooks/Login/useLogin";
import loginLogo from "../../assets/images/loginLogo.webp";
import CompanyLogo from "../../assets/images/Bamboology_logo.webp";
import "./Login.css";

const Login = () => {
  const talonProps = useLogin();
  const {
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
  } = talonProps;
  return (
    <div className="LoginRoot">
      <div className="LoginBlock">
              <div className="LoginLeftBlock">
                  <img src={CompanyLogo} alt="Login" />
          <img src={loginLogo} alt="Login" />
        </div>
        <form className="LoginRightBlock validate-form" onSubmit={handleSubmit}>
          <span className="LoginRightBlock-title">User Login</span>
          <div
            className="LoginRightBlock-input"
            data-validate="Valid email is required: ex@abc.xyz"
          >
            <input
              className="LoginRightBlockInput"
              type="text"
              name="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              //   onBlur={isValidEmail}
              placeholder="Email"
            />
            <span className="focus-LoginRightBlockInput">{emailError}</span>
            <span className="symbol-LoginRightBlockInput">
              <GrMail />
            </span>
          </div>
          <span className="fsize10 color_red">{emailError}</span>
          <div
            className="LoginRightBlock-input"
            data-validate="Password is required"
          >
            <input
              className="LoginRightBlockInput"
              type="password"
              name="pass"
              placeholder="Password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              //   onBlur={isValidPassword}
            />
            <span className="focus-LoginRightBlockInput">{passwordError}</span>
            <span className="symbol-LoginRightBlockInput">
              <AiFillLock />
            </span>
          </div>
          <span className="fsize10 color_red">{passwordError}</span>
          {loginError ? (
            <div className="loginFailure mt-3">
              <span className="color_red">{loginError}</span>
            </div>
          ) : null}
          <div className="container-LoginRightBlock-form-btn">
            <button className="LoginRightBlock-form-btn" type="submit">
              Login
            </button>
          </div>
          <div className="text-center forgotContainer">
            <span className="txt1">Forgot</span>&nbsp;
            <a className="txt2" href="#">
              Password?
            </a>
          </div>
          {/* <div className="text-center createContainer">
            <a className="txt2" href="#">
              Create your Account
              <BsArrowRight />
            </a>
          </div> */}
        </form>
      </div>
    </div>
  );
};

export default Login;
