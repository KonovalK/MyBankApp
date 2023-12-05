import {Button} from "@mui/material";
import React, {useState} from "react";
import axios from "axios";
import userAuthenticationConfig from "../../utils/userAuthenticationConfig";
import {responseStatus} from "../../utils/consts";
import {useNavigate} from "react-router-dom";

const MySavingBank = ({savingBankInfo, onClickPlus, onClickMinus, setNotification, notification, setError}) => {
    const navigate = useNavigate();

    const handleDelete = () => {
        axios.delete(`/api/delete-saving-bank/${savingBankInfo.id}`, userAuthenticationConfig())
            .then(response => {
                if (response.status === responseStatus.HTTP_NO_CONTENT) {
                    window.location.reload();
                }
            })
            .catch(error => {
                setError(error.response.data);
                setNotification({...notification, visible: true, type: "error", message: error.response.data});
            });
    };

    return (
        <div style={{border:2, borderStyle:"solid", borderRadius:20, margin:50, padding:20}}>
            <Button
                style={{backgroundColor:"red"}}
                variant="contained"
                type="submit"
                onClick={handleDelete}
            >
                Видалити банку
            </Button>
            <h3>{savingBankInfo.name}</h3>
            <h5>{savingBankInfo.description}</h5>
            <h2>{savingBankInfo.amount}</h2>
            <Button
                variant="contained"
                type="submit"
                onClick={onClickPlus}
            >
                Поповнити банку
            </Button>
            <Button style={{marginLeft:20}}
                    variant="contained"
                    type="submit"
                    onClick={onClickMinus}
            >
                Зняти кошти з банки
            </Button>
        </div>
    );
};

export default MySavingBank;
