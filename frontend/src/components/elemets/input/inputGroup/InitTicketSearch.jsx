import { Autocomplete, Button, ButtonGroup, TextField } from "@mui/material";
import React from "react";

const InitTicketSearch = () => {

  const countries = [
    { code: "AD", label: "Andorra", phone: "376" },
    {
      code: "AE",
      label: "United Arab Emirates",
      phone: "971"
    }
  ];
  return (
    <ButtonGroup variant="contained" aria-label="standard primary button group">
      <Autocomplete
        id="country-customized-option-demo"
        options={countries}
        disableCloseOnSelect
        getOptionLabel={(option) =>
          `${option.label} (${option.code}) +${option.phone}`
        }
        renderInput={(params) => <TextField {...params} label="Choose a country" />}
      />
      <Button>Two</Button>
      <Button>Three</Button>
    </ButtonGroup>
  );
};

export default InitTicketSearch;