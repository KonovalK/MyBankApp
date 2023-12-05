import React, { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import axios from "axios";
import { Helmet } from "react-helmet-async";
import Notification from "../../../components/elemets/notification/Notification";
import { responseStatus } from "../../../utils/consts";
import userAuthenticationConfig from "../../../utils/userAuthenticationConfig";
import CreateCardForm from "../../../components/cards/user/CreateCardForm";

const CreateCardPage = () => {
    const navigate = useNavigate();
    const [error, setError] = useState(null);
    const [loading, setLoading] = useState(false);
    const [data, setData] = useState({
        template: null,
        balance: null,
        pin: null,
        rate: null,
    });
    const [notification, setNotification] = useState({
        visible: false,
        type: "",
        message: ""
    });

    const [banks,setBanks]=useState(null);

    const [rates,setRates]=useState(null);
    const loadBanks = () => {
        axios.get("/api/banks", userAuthenticationConfig()).then(response => {
            if (response.status === responseStatus.HTTP_OK && response.data["hydra:member"]) {
                setBanks(response.data["hydra:member"]);
            }
        }).catch(error => {
            console.log("error");
        });
    };

    const loadRates = () => {
        axios.get("/api/exchange-rates", userAuthenticationConfig()).then(response => {
            if (response.status === responseStatus.HTTP_OK && response.data["hydra:member"]) {
                setRates(response.data["hydra:member"]);
            }
        }).catch(error => {
            console.log("error");
        });
    };

    useEffect(() => {
        loadBanks();
        loadRates();
    }, []);

    const CardRequest = () => {
        if(data.template && data.balance && data.rate){
            setLoading(true);
            axios.post(`/api/cards`, data, userAuthenticationConfig()).then(response => {
                if (response.status === responseStatus.HTTP_CREATED) {
                    data.template=null;
                    data.balance=null;
                    data.rate=null;
                    navigate("/main");
                }
            }).catch(error => {
                // setError(error.response.data["hydra:description"]);
                // setNotification({ ...notification, visible: true, type: "error", message: error.response.data["hydra:description"] });
            }).finally(() => setLoading(false));
        }
    };

    useEffect(() => {
        CardRequest();
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
                    Створити картку
                </title>
            </Helmet>
            <CreateCardForm
                setData={setData}
                loading={loading}
                banks={banks}
                rates={rates}
            />
        </>
    );
};

export default CreateCardPage;