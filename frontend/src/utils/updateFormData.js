import axios from "axios";
import userAuthenticationConfig from "./userAuthenticationConfig";
import { responseStatus } from "./consts";

const updateFormData = (formId, updatedData) => {
  axios.put(`https://courselab.com${formId}`, updatedData, userAuthenticationConfig()).then(response => {
    if (response.status === responseStatus.HTTP_OK) {
      console.log("Дані оновлені успішно");
    }
  }).catch(error => {
    console.error("Помилка під час оновлення даних:", error);
  });
};

export default updateFormData;