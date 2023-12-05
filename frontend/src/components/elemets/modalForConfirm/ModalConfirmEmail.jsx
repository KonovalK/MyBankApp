import React, {useState} from "react";
import { Modal, Button } from "@mui/material";
import InputCustom from "../input/InputCustom";

const ModalConfirmEmail = ({ open, onClose, onNotMyEmail, setVereficationCode}) => {

  const OnChangeVefificationInput = (event) => {
    const inputValue = event.target.value;

    if (inputValue.length === 4) {
      setVereficationCode(inputValue);
    }
  };

  return (
    <Modal open={open} onClose={onClose}>
      <div className="modal-container">
        <div className="modal-content">
          <h2>Confirm Email</h2>
          <InputCustom id="confirmInput" name="confirmInput" label="Введіть код підтвердження з пошти" onChange={OnChangeVefificationInput}/>
          <Button variant="contained" color="inherit" onClick={onNotMyEmail}>
            Відміна
          </Button>
        </div>
      </div>
    </Modal>
  );
};

export default ModalConfirmEmail;