import { Button, TableCell, TableRow } from "@mui/material";
import React from "react";

const CardsTemplatesRowItem = ({ align = "left", cardTemplate, openModalDeleteTemplate, navigate }) => {

  const onCardTemplateEdit = () => {
    navigate(`/api/card-template/edit/${cardTemplate.id}`);
  };

  const onDelete = () => {
    openModalDeleteTemplate(cardTemplate.id);
  };

  return <>
    <TableRow>
      <TableCell align={align}>
        {cardTemplate.id}
      </TableCell>
      <TableCell align={align}>
        {cardTemplate.cardType}
      </TableCell>
      <TableCell align={align}>
        {cardTemplate.otherCardPropereties}
      </TableCell>
      <TableCell align={align}>
        <Button onClick={() => onDelete()} color="error">Видалити</Button>
      </TableCell>
      <TableCell align={align}>
        <Button onClick={() => onCardTemplateEdit()} color="error">Змінити</Button>
      </TableCell>
    </TableRow>
  </>;
};

export default CardsTemplatesRowItem;