import * as React from "react";
import TextField from "@mui/material/TextField";
import Autocomplete from "@mui/material/Autocomplete";
import { useEffect, useRef, useState } from "react";
import axios from "axios";
import userAuthenticationConfig from "../../../utils/userAuthenticationConfig";
import { responseStatus } from "../../../utils/consts";

function InputDataLoader ({
  url,
  name = "",
  getOptionLabel,
  itemsPerPage = 20,
  searchWord = "",
  label,
  onChange = undefined
}) {
  const [open, setOpen] = useState(false);
  const [options, setOptions] = useState([]);
  const [inputQuery, setInputQuery] = useState("");
  const loading = open && options.length === 0;

  const ref0 = useRef();

  const onInput = (e) => {
    console.log(options.length);
    setInputQuery(e.target.value);
  };
  const sendRequest = () => {
    axios.get(`${url}?${searchWord}=${inputQuery}&itemsPerPage=${itemsPerPage}`, userAuthenticationConfig()).then(response => {

      if (response.status === responseStatus.HTTP_OK && response.data["hydra:member"]) {
        let data = response.data["hydra:member"];
        setOptions(data);

        if (data.length === 0) {
          setOptions([]);
        }
      }
    });
  };

  useEffect(() => {
    let active = true;

    sendRequest();

    return () => {
      active = false;
    };
  }, [loading, inputQuery]);

  useEffect(() => {
    if (!open) {
      setOptions([]);
    }
  }, [open]);

  return (
    <Autocomplete
      onInput={onInput}
      ref={ref0}
      name={name}
      open={open}
      onOpen={() => {
        setOpen(true);
      }}
      onClose={() => {
        setOpen(false);
      }}
      onChange={(e, v) => {
        if (onChange) {
          onChange(e, v);
        }
      }}
      isOptionEqualToValue={(option, value) => option.title === value.title}
      getOptionLabel={getOptionLabel}
      options={options}
      loading={loading}
      renderInput={(params) => (
        <TextField
          {...params}
          label={label}
          /*InputProps={{
              ...params.InputProps,
              endAdornment: (
                  <React.Fragment>
                      {loading ? <CircularProgress color="inherit" size={20}/> : null}
                      {params.InputProps.endAdornment}
                  </React.Fragment>
              ),
          }}*/
        />
      )}
    />
  );
}

const topFilms = [
  { title: "The Shawshank Redemption", year: 1994 },
  { title: "The Godfather", year: 1972 }
];

export default InputDataLoader;