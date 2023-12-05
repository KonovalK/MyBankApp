import * as React from "react";
import MenuItem from "@mui/material/MenuItem";
import eventBus from "../../../../utils/eventBus";

export default function DropdownUnlogged ({ goTo }) {

  return (
    <>
      <MenuItem onClick={() => goTo("main")}>На головну</MenuItem>
      <MenuItem
        onClick={() => eventBus.dispatch("logout")}
      >Logout
      </MenuItem>
    </>
  );
}