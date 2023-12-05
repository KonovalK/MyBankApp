import React, { useContext, useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import axios from "axios";
import { Helmet } from "react-helmet-async";
import Notification from "../elemets/notification/Notification";
import { authentication } from "../../utils/authenticationRequest";
import { responseStatus } from "../../utils/consts";
import { AppContext } from "../../App";
import loginRequest from "../../utils/loginRequest";
import RegistrationForm from "./RegistrationForm";
import userAuthenticationConfig from "../../utils/userAuthenticationConfig";

const RegistrationContainer = () => {
  const navigate = useNavigate();
  const { setAuthenticated, authenticated } = useContext(AppContext);
  const [authData, setAuthData] = useState();
  const [error, setError] = useState(null);
  const [loading, setLoading] = useState(false);
  const [verificationCode, setVerificationCode] = useState("");
  const [notification, setNotification] = useState({
    visible: false,
    type: "",
    message: ""
  });

  const registrationRequest = () => {

    if(!authData){
      return;
    }

    setLoading(true);

    //Тут ми вже записуємо юзера в БД (Action CreateUser)

    axios.post(`/api/users`, authData).then(response => {
      if (response.status === responseStatus.HTTP_CREATED) {
        loginRequest(authData,
            () => {
              CodePost();
            });
      }
    }).catch(error => {
      setError(error.response.data.detail);
      setNotification({ ...notification, visible: true, type: "error", message: error.response.data.detail });
    }).finally(() => setLoading(false));
  };
  const CodePost = () => {
    axios.post(`/api/generate-code`, [], userAuthenticationConfig()).then(response => {
      if (response.status === responseStatus.HTTP_CREATED) {
      }
    }).catch(error => {
      setError(error.response.data.detail);
      setNotification({ ...notification, visible: true, type: "error", message: error.response.data.detail });
    }).finally(() => setLoading(false));
  };
  const VerificationRequest = () => {
    if(verificationCode.length===4){
      let refactoredData={
          code:parseInt(verificationCode),
      };
      axios.put(`/api/verificate-user`, refactoredData ,userAuthenticationConfig()).then(response => {
        if (response.status === responseStatus.HTTP_OK) {
          loginRequest(authData,
              () => {
                setAuthenticated(true);
              });
        }
      }).catch(error => {
        setError(error.response.data.detail);
        setNotification({ ...notification, visible: true, type: "error", message: error.response.data.detail });
      }).finally(() => setLoading(false));
    }
  };

  useEffect(() => {
    authentication(navigate, authenticated);
  }, [authenticated]);

  useEffect(() => {
    VerificationRequest();
  }, [verificationCode]);

  useEffect(() => {
    registrationRequest();
  }, [authData]);

  return (
    <>
      {notification.visible &&
        <Notification
          notification={notification}
          setNotification={setNotification}
        />
      }
      <Helmet>
        <title>
          Create account
        </title>
      </Helmet>
      <RegistrationForm
        setAuthData={setAuthData}
        loading={loading}
        setVereficationCode={setVerificationCode}
      />
    </>
  );
};

export default RegistrationContainer;