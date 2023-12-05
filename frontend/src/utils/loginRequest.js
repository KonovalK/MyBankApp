import axios from "axios";
import { responseStatus } from "./consts";
import { storageGetItem, storageSetItem, TOKEN } from "../storage/storage";

const loginRequest = (authData, onSuccess = null, onError = null, onFinally = null) => {
  axios.post(`/api/login-check`, authData).then(response => {
    if (response.status === responseStatus.HTTP_OK && response.data.token) {
      storageSetItem(TOKEN, response.data.token);
      if (onSuccess) {
        onSuccess();
      }
    }
  }).catch(error => {
    if (onError) {
      onError(error.response.data.message);
    }
  }).finally(() => {
    if (onFinally) {
      onFinally();
    }
  });
};

export default loginRequest;