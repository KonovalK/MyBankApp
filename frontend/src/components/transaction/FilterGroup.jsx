import React from "react";
import Grid from "@mui/material/Grid";
import MenuItem from "@mui/material/MenuItem";
import { Select } from "@mui/material";
import SearchFilterDefault from "../elemets/searchFilter/transaction/SearchFilterDefault";
import {Label} from "recharts";

function FilterGroup({
                         filterData,
                         setFilterData,
                         sortingOption,
                         setSortingOption,
                         sortDirection,
                         setSortDirection,
                     }) {
    return (
        <>
            <Grid item xs={3}>
                <h4>Фільтрація за номером карти отримувача</h4>
                <SearchFilterDefault
                    inputLabel="Receiver"
                    filterData={filterData}
                    setFilterData={setFilterData}
                    fieldName="receiver"
                />
            </Grid>
            <Grid item xs={3}>
                <h4>Фільтрація за описом транзанції</h4>
                <SearchFilterDefault
                    inputLabel="Description"
                    filterData={filterData}
                    setFilterData={setFilterData}
                    fieldName="description"
                />
            </Grid>
            <Grid item xs={3}>
                <Select style={{marginTop:60}}
                    labelId="sorting-label"
                    id="sorting-select"
                    value={sortingOption}
                    label="Сортировка"
                    onChange={(event) => setSortingOption(event.target.value)}
                >
                    <MenuItem value="id">За замовчуванням</MenuItem>
                    <MenuItem value="summa">По суммі</MenuItem>
                </Select>
            </Grid>
            <Grid item xs={3}>
                <Select style={{marginTop:60}}
                    labelId="sort-direction-label"
                    id="sort-direction-select"
                    value={sortDirection}
                    label="Направление сортировки"
                    onChange={(event) => setSortDirection(event.target.value)}
                >
                    <MenuItem value="asc">По зростанню</MenuItem>
                    <MenuItem value="desc">По спаданню</MenuItem>
                </Select>
            </Grid>
        </>
    );
}

export default FilterGroup;
