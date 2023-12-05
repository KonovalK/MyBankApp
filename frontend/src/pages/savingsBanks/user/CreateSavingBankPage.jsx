import React, { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import axios from "axios";
import { Helmet } from "react-helmet-async";
import Notification from "../../../components/elemets/notification/Notification";
import { responseStatus } from "../../../utils/consts";
import userAuthenticationConfig from "../../../utils/userAuthenticationConfig";
import CreateCardForm from "../../../components/cards/user/CreateCardForm";
import CreateSavingsBankForm from "../../../components/savingsBank/CreateSavingBankForm";

const CreateSavingBankPage = () => {
    const navigate = useNavigate();
    const [error, setError] = useState(null);
    const [loading, setLoading] = useState(false);
    const [data, setData] = useState({
        name: null,
        description: null
    });
    const [notification, setNotification] = useState({
        visible: false,
        type: "",
        message: ""
    });

    const SavingBankRequest = () => {
        if(data.name && data.description){
            setLoading(true);
            axios.post(`/api/savings-banks`, data, userAuthenticationConfig()).then(response => {
                if (response.status === responseStatus.HTTP_CREATED) {
                    data.name=null;
                    data.description=null;
                    navigate("/savings-banks-list");
                }
            }).catch(error => {
                // setError(error.response.data["hydra:description"]);
                // setNotification({ ...notification, visible: true, type: "error", message: error.response.data["hydra:description"] });
            }).finally(() => setLoading(false));
        }
    };

    useEffect(() => {
        SavingBankRequest();
    }, [data]);

    return (
        <>
            {notification.visible &&
                <Notification
                    notification={notification}
                    setNotification={setNotification}
                />
            }
            <Helmet>
                <title>
                    Створити накопичувальну банку
                </title>
            </Helmet>
            <CreateSavingsBankForm
                setData={setData}
                loading={loading}
            />
        </>
    );
};

export default CreateSavingBankPage;