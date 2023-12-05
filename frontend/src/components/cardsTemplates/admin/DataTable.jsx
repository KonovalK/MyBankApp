import React, { useState } from "react";
import TableGenerator from "../../elemets/table/TableGenerator";
import PopupDefault from "../../elemets/popup/PopupDefault";
import axios from "axios";
import userAuthenticationConfig from "../../../utils/userAuthenticationConfig";
import { responseStatus } from "../../../utils/consts";
import { useNavigate } from "react-router-dom";
import CardsTemplatesRowItem from "./CardsTemplatesRowItem";

const DataTable = ({ fetchedData, reloadData }) => {
  const navigate = useNavigate();

  const [selectedTemplate, setSelectedTemplate] = useState(0);
  const [isPopupFinishOpen, setPopupFinishOpen] = useState(false);

  const openModalDeleteTemplate = (template) => {
    setSelectedTemplate(template);
    setPopupFinishOpen(true);
  };

  const closeModals = (e) => {
    setPopupFinishOpen(false);
  };

  const deleteTemplate = () => {
    axios.delete(`/api/card-template/delete/${selectedTemplate}`, userAuthenticationConfig(false)).then(response => {
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
        titles={["id", "Тип карти", "Додаткова інформація"]}
        items={
          fetchedData && fetchedData.map((item, key) => (
            <CardsTemplatesRowItem
              key={key}
              cardTemplate={item}
              openModalDeleteTemplate={openModalDeleteTemplate}
              navigate={navigate}
            />
          ))
        }
      />

      <PopupDefault
        isOpen={isPopupFinishOpen}
        title={"Видалення шаблону #" + selectedTemplate}
        description={"Ви впевнені, що хочете видалити шаблон?"}
        acceptLabel="Так"
        declineLabel="Ні"
        onAccept={() => deleteTemplate()}
        onDecline={() => closeModals()}
        handleClose={() => closeModals()}
      />
    </>
  );
};

export default DataTable;