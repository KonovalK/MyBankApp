import React from "react";
import InputCustom from "../elemets/input/InputCustom";
import InputPassword from "../elemets/input/InputPassword";
import InputPhoneNumber from "../elemets/input/InputPhoneNumber";

const globalRegistrationItems = () => {

  return <>
    <InputCustom
      id="name"
      type="text"
      label="First name"
      name="name"
      required
    />

    <InputCustom
      id="surname"
      type="text"
      label="Last name"
      name="surname"
      required
    />

    <InputCustom
      id="email"
      type="email"
      label="E-mail"
      name="email"
      required
    />

    <InputPhoneNumber
      name="phoneNumber"
      label=""
    />

    <InputPassword
      id="password"
      name="password"
    />
  </>;
};

export default globalRegistrationItems;