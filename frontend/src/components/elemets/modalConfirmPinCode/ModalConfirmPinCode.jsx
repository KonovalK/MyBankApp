import React, {useState} from "react";
import { Modal, Button } from "@mui/material";
import InputCustom from "../input/InputCustom";

const ModalConfirmPinCode = ({ open, onClose, onNot, setPinCode}) => {

  const OnChangePinInput = (event) => {
    const inputValue = event.target.value;

    if (inputValue.length === 4) {
      setPinCode(inputValue);
    }
  };

  return (
    <Modal open={open} onClose={onClose}>
      <div className="modal-container">
        <div className="modal-content">
          <h2>Введіть пін-код від карти</h2>
          <InputCustom id="confirmInput" name="confirmInput" label="Введіть пін-код від карти" onChange={OnChangePinInput}/>
          <Button variant="contained" color="inherit" onClick={onNot}>
            Відміна
          </Button>
        </div>
      </div>
    </Modal>
  );
};

export default ModalConfirmPinCode;