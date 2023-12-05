import React from "react";
import { FormControl, IconButton, Input, InputAdornment, InputLabel, TextField } from "@mui/material";
import { Visibility, VisibilityOff } from "@mui/icons-material";

const InputPassword = ({ id, label, name }) => {

  const [showPassword, setShowPassword] = React.useState(false);
  const handleClickShowPassword = () => setShowPassword((show) => !show);

  return <>
    <FormControl variant="standard">
      <InputLabel htmlFor="standard-adornment-password">Password</InputLabel>
      <Input
        type={showPassword ? "text" : "password"}
        id={id}
        name={name}
        endAdornment={
          <InputAdornment position="end">
            <IconButton
              aria-label="toggle password visibility"
              onClick={handleClickShowPassword}
            >
              {showPassword ? <VisibilityOff /> : <Visibility />}
            </IconButton>
          </InputAdornment>
        }
      />
    </FormControl>
  </>;
};

export default InputPassword;