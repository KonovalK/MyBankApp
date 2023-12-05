import React, { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import axios from "axios";
import { Helmet } from "react-helmet-async";
import Notification from "../../../components/elemets/notification/Notification";
import { responseStatus } from "../../../utils/consts";
import userAuthenticationConfig from "../../../utils/userAuthenticationConfig";
import CreateCardTemplateForm from "../../../components/cardsTemplates/admin/CreateCardTemplateForm";

const CreateCardTemplatePage = () => {
    const navigate = useNavigate();
    const [error, setError] = useState(null);
    const [loading, setLoading] = useState(false);
    const [data, setData] = useState({
        cardType: null,
        cardBackgroundPhoto: "default",
        otherCardPropereties: null,
        bank: null,
    });
    const [notification, setNotification] = useState({
        visible: false,
        type: "",
        message: ""
    });

    const [banks,setBanks]=useState(null);
    const loadBanks = () => {
        axios.get("/api/banks", userAuthenticationConfig()).then(response => {
            if (response.status === responseStatus.HTTP_OK && response.data["hydra:member"]) {
                setBanks(response.data["hydra:member"]);
            }
        }).catch(error => {
            console.log("error");
        });
    };

    useEffect(() => {
        loadBanks();
    }, []);

    const TemplateRequest = () => {
        if(data.cardType && data.cardBackgroundPhoto && data.otherCardPropereties && data.bank){
            setLoading(true);
            axios.post(`/api/card-templates`, data, userAuthenticationConfig()).then(response => {
                if (response.status === responseStatus.HTTP_CREATED) {
                    data.cardType=null;
                    data.otherCardPropereties=null;
                    data.bank=null;
                    navigate("/main");
                }
            }).catch(error => {
                // setError(error.response.data["hydra:description"]);
                // setNotification({ ...notification, visible: true, type: "error", message: error.response.data["hydra:description"] });
            }).finally(() => setLoading(false));
        }
    };

    useEffect(() => {
        TemplateRequest();
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
                    Створити шаблон
                </title>
            </Helmet>
            <CreateCardTemplateForm
                setData={setData}
                loading={loading}
                banks={banks}
            />
        </>
    );
};

export default CreateCardTemplatePage;