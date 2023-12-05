import { IconButton, TextField } from "@mui/material";
import { Delete } from "@mui/icons-material";
import React, { useState } from "react";

const InputAddRemove = () => {

  const [serviceList, setServiceList] = useState([{ service: "" }]);

  const handleServiceChange = (e, index) => {
    const { name, value } = e.target;
    const list = [...serviceList];
    list[index][name] = value;
    setServiceList(list);
  };

  const handleServiceRemove = (index) => {
    const list = [...serviceList];
    list.splice(index, 1);
    setServiceList(list);
  };

  const handleServiceAdd = () => {
    setServiceList([...serviceList, { service: "" }]);
  };

  return (

    <TextField
      id="standard-name"
      label="Name"
      value="hello"
      InputProps={{ endAdornment: <IconButton edge="end"> <Delete /> </IconButton> }}
    />
  );
};

export default InputAddRemove;