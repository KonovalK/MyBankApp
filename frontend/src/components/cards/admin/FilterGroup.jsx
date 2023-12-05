import Grid from "@mui/material/Grid";
import React from "react";
import SearchFilterSelect from "../../elemets/searchFilter/SearchFilterDefault";

function FilterGroup({ filterData, setFilterData, banks }) {
  return (
      <>
        <Grid item xs={3}>
          {banks && banks.length > 0 && (
              <SearchFilterSelect
                  inputLabel="Банк"
                  filterData={filterData}
                  setFilterData={setFilterData}
                  fieldName="bank"
                  banks={banks}
              />
          )}
        </Grid>
      </>
  );
}

export default FilterGroup;