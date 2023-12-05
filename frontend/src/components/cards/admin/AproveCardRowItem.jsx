import { Button, TableCell, TableRow } from "@mui/material";
import React from "react";

const AproveCardRowItem = ({ align = "left", card, openModalDeleteCard, openModalAproveCard, navigate }) => {

  const onCardAprove = () => {
    openModalAproveCard(card.id);
  };

  const onDelete = () => {
    openModalDeleteCard(card.id);
  };

  return <>
    <TableRow>
      <TableCell align={align}>
        {card.id}
      </TableCell>
      <TableCell align={align}>
        {card.cardNumber}
      </TableCell>
      <TableCell align={align}>
        <Button onClick={() => onDelete()} color="error">Видалити</Button>
      </TableCell>
      <TableCell align={align}>
        <Button onClick={() => onCardAprove()} color="error">Підтвердити</Button>
      </TableCell>
    </TableRow>
  </>;
};

export default AproveCardRowItem;