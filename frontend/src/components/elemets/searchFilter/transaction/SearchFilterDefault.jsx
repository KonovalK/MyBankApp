import React from "react";
import {TextField} from "@mui/material";

const SearchFilterDefault = ({
                                 filterData, setFilterData, defaultValue = "",
                                 fieldName = "", inputLabel = "", inputType = ""
                             }) => {

    const onChangeFilterData = (event) => {
        event.preventDefault();

        let { price, value } = event.target;

        filterData[fieldName] = value;
        setFilterData({ ...filterData });
    };

    return <>
        <TextField
            hiddenLabel
            id="filled-hidden"
            type={inputType}
            defaultValue={defaultValue}
            variant="filled"
            onChange={onChangeFilterData}
        />
    </>;
};

export default SearchFilterDefault;