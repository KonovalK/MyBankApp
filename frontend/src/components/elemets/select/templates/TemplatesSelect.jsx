import React from "react";
import { Select, MenuItem } from "@mui/material";

const TemplatesSelect = ({
                                filterData,
                                setFilterData,
                                defaultValue = null,
                                fieldName = "",
                                inputLabel = "",
                                inputType = "",
                                templates,
                            }) => {
    const onChangeData = (event) => {
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
                onChange={onChangeData}
            >
                {templates &&
                    templates.map((item, key) => (
                        <MenuItem key={key} value={item.id}>
                            {item.cardType}
                        </MenuItem>
                    ))}
            </Select>
        </>
    );
};

export default TemplatesSelect;