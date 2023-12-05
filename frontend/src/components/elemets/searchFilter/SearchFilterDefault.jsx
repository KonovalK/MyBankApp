import React from "react";
import { Select, MenuItem } from "@mui/material";

const SearchFilterSelect = ({
                              filterData,
                              setFilterData,
                              defaultValue = null,
                              fieldName = "",
                              inputLabel = "",
                              inputType = "",
                              banks,
                            }) => {
  const onChangeFilterData = (event) => {
    event.preventDefault();
    let { value } = event.target;
    filterData[fieldName] = value;
    setFilterData({ ...filterData });
  };

  return (
      <>
        <Select
            style={{ width: 400 }}
            hiddenLabel
            id="filled-hidden"
            type={inputType}
            defaultValue={defaultValue}
            variant="filled"
            onChange={onChangeFilterData}
        >
          {banks &&
              banks.map((item, key) => (
                  <MenuItem key={key} value={item.id}>
                    {item.bankName}
                  </MenuItem>
              ))}
        </Select>
      </>
  );
};

export default SearchFilterSelect;