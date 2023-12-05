import { MuiTelInput } from "mui-tel-input";
import { useState } from "react";

const InputPhoneNumber = ({ name = "", label = "" }) => {

  const [value, setValue] = useState("+380");

  const handleChange = (newValue) => {
    setValue(newValue);
  };

  return <>
    <MuiTelInput
      value={value}
      variant="standard"
      onChange={handleChange}
      label={label}
      name={name}
    />
  </>;
};
export default InputPhoneNumber;