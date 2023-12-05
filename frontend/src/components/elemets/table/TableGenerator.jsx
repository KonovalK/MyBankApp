import { Table, TableBody, TableCell, TableContainer, TableHead, TableRow } from "@mui/material";
import React from "react";
import Paper from "@mui/material/Paper";

const TableGenerator = ({ titles, items = [], align = "left" }) => {

  return (
    <TableContainer component={Paper}>
      <Table sx={{ minWidth: 650 }} aria-label="simple table">
        <TableHead>
          <TableRow>
            {titles.map((t) => (
              <TableCell align={align}>{t}</TableCell>
            ))}
          </TableRow>
        </TableHead>
        <TableBody>
          {items}
        </TableBody>
      </Table>
    </TableContainer>
  );
};

export default TableGenerator;