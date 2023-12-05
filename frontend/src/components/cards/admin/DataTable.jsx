import React, { useState } from "react";
import TableGenerator from "../../elemets/table/TableGenerator";
import PopupDefault from "../../elemets/popup/PopupDefault";
import axios from "axios";
import userAuthenticationConfig from "../../../utils/userAuthenticationConfig";
import { responseStatus } from "../../../utils/consts";
import { useNavigate } from "react-router-dom";
import CardsTemplatesRowItem from "./AproveCardRowItem";

const DataTable = ({ fetchedData, reloadData }) => {
  const navigate = useNavigate();

  const [selectedCard, setSelectedCard] = useState(0);
  const [isPopupFinishOpen, setPopupFinishOpen] = useState(false);
  const [isPopupAproveOpen, setPopupAproveOpen] = useState(false);
  const openModalDeleteCard = (card) => {
    setSelectedCard(card);
    setPopupFinishOpen(true);
  };
    const openModalAproveCard = (card) => {
        setSelectedCard(card);
        setPopupAproveOpen(true);
    };

  const closeModals = (e) => {
    setPopupFinishOpen(false);
  };
    const closeModalsAprove = (e) => {
        setPopupAproveOpen(false);
    };

  const deleteCard = () => {
    axios.delete(`/api/delete-card/${selectedCard}`, userAuthenticationConfig(false)).then(response => {
      if (response.status === responseStatus.HTTP_NO_CONTENT) {
      }
    }).catch(error => {

    }).finally(() => {
      reloadData();
      closeModals();
    });
  };

    const aproveCard = () => {
        const updateData = {
            isVerified: 1
        };
        axios.put(`/api/cards/${selectedCard}`,updateData ,userAuthenticationConfig(false)).then(response => {
            if (response.status === responseStatus.HTTP_NO_CONTENT) {
            }
        }).catch(error => {

        }).finally(() => {
            reloadData();
            closeModalsAprove();
        });
    };
  return (
    <>
      <TableGenerator
        titles={["id", "Номер картки"]}
        items={
          fetchedData && fetchedData.map((item, key) => (
            <CardsTemplatesRowItem
              key={key}
              card={item}
              openModalDeleteCard={openModalDeleteCard}
              openModalAproveCard={openModalAproveCard}
              navigate={navigate}
            />
          ))
        }
      />

      <PopupDefault
        isOpen={isPopupFinishOpen}
        title={"Видалення карти #" + selectedCard}
        description={"Ви впевнені, що хочете видалити карту?"}
        acceptLabel="Yes"
        declineLabel="No"
        onAccept={() => deleteCard()}
        onDecline={() => closeModals()}
        handleClose={() => closeModals()}
      />
        <PopupDefault
            isOpen={isPopupAproveOpen}
            title={"Підтвердження карти #" + selectedCard}
            description={"Ви впевнені, що хочете підтвердити карту?"}
            acceptLabel="Yes"
            declineLabel="No"
            onAccept={() => aproveCard()}
            onDecline={() => closeModalsAprove()}
            handleClose={() => closeModalsAprove()}
        />
    </>
  );
};

export default DataTable;