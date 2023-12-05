import React, { useState } from "react";
import TableGenerator from "../../elemets/table/TableGenerator";
import PopupDefault from "../../elemets/popup/PopupDefault";
import axios from "axios";
import userAuthenticationConfig from "../../../utils/userAuthenticationConfig";
import { responseStatus } from "../../../utils/consts";
import { useNavigate } from "react-router-dom";
import BankRowItem from "./BankRowItem";

const DataTable = ({ fetchedData, reloadData }) => {
  const navigate = useNavigate();

  const [selectedBank, setSelectedBank] = useState(0);
  const [isPopupFinishOpen, setPopupFinishOpen] = useState(false);

  const openModalDeleteBank = (bank) => {
    setSelectedBank(bank);
    setPopupFinishOpen(true);
  };

  const closeModals = (e) => {
    setPopupFinishOpen(false);
  };

  const deleteBank = () => {
    axios.delete(`/api/banks/delete/${selectedBank}`, userAuthenticationConfig(false)).then(response => {
      if (response.status === responseStatus.HTTP_NO_CONTENT) {
      }
    }).catch(error => {

    }).finally(() => {
      reloadData();
      closeModals();
    });
  };

  return (
    <>
      <TableGenerator
        titles={["id", "Назва банку", "Адреса"]}
        items={
          fetchedData && fetchedData.map((item, key) => (
            <BankRowItem
              key={key}
              bank={item}
              openModalDeleteBank={openModalDeleteBank}
              navigate={navigate}
            />
          ))
        }
      />

      <PopupDefault
        isOpen={isPopupFinishOpen}
        title={"Видалення банку #" + selectedBank}
        description={"Ви впевнені, що хочете видалити банк?"}
        acceptLabel="Так"
        declineLabel="Ні"
        onAccept={() => deleteBank()}
        onDecline={() => closeModals()}
        handleClose={() => closeModals()}
      />
    </>
  );
};

export default DataTable;