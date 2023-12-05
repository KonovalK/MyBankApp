import { Button, TableCell, TableRow } from "@mui/material";
import React from "react";

const BankRowItem = ({ align = "left", bank, openModalDeleteBank, navigate }) => {

  const onBankEdit = () => {
    navigate(`/banks/edit/${bank.id}`);
  };

  const onDelete = () => {
    openModalDeleteBank(bank.id);
  };

  return <>
    <TableRow>
      <TableCell align={align}>
        {bank.id}
      </TableCell>
      <TableCell align={align}>
        {bank.bankName}
      </TableCell>
      <TableCell align={align}>
        {bank.adress}
      </TableCell>
      <TableCell align={align}>
        <Button onClick={() => onDelete()} color="error">Видалити</Button>
      </TableCell>
      <TableCell align={align}>
        <Button onClick={() => onBankEdit()} color="error">Змінити</Button>
      </TableCell>
    </TableRow>
  </>;
};

export default BankRowItem;