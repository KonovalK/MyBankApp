import React from "react";
import { Select, MenuItem } from "@mui/material";

const RateSelect = ({
                        filterData,
                        setFilterData,
                        defaultValue = null,
                        fieldName = "",
                        inputLabel = "",
                        inputType = "",
                        rates,
                    }) => {
    const onChangeData = (event) => {
        event.preventDefault();
        let { value } = event.target;
        setFilterData((prevData) => ({ ...prevData, [fieldName]: value }));
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
                onChange={onChangeData}
            >
                {rates &&
                    rates.map((item, key) => (
                        <MenuItem key={key} value={item.id}>
                            {item.name}
                        </MenuItem>
                    ))}
            </Select>
        </>
    );
};

export default RateSelect;
