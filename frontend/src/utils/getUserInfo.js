import jwt_decode from "jwt-decode";
import { storageGetItem, storageKeyExists, TOKEN } from "../storage/storage";

const getUserInfo = () => {
  let token = null;

  if (storageKeyExists(TOKEN)) {
    try {
      token = jwt_decode(storageGetItem(TOKEN));
    } catch (e) {
      return null;
    }
    return token;
  }

  return null;
};

export default getUserInfo;