import React, { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import axios from "axios";
import { Helmet } from "react-helmet-async";
import Notification from "../../../components/elemets/notification/Notification";
import { responseStatus } from "../../../utils/consts";
import userAuthenticationConfig from "../../../utils/userAuthenticationConfig";
import CreateBankForm from "../../../components/banks/admin/CreateBankForm";

const EditBankPage = () => {
    const navigate = useNavigate();
    const [error, setError] = useState(null);
    const [loading, setLoading] = useState(false);
    const [data, setData] = useState([{}]);
    const [notification, setNotification] = useState({
        visible: false,
        type: "",
        message: ""
    });
    const [selectedBank, setSelectedBank] = useState({
        bankName:"",
        adress:""
    });
    const parts = window.location.pathname.split('/');
    const bankId = parts[parts.length - 1];
    const loadSelectedBank = () => {

        axios.get("/api/banks/" + bankId, userAuthenticationConfig()).then(response => {
            if (response.status === responseStatus.HTTP_OK && response.data["hydra:member"]) {
                setSelectedBank(response.data["hydra:member"]);
            }
        }).catch(error => {
            console.log("error");
        });
    };

    useEffect(() => {
        loadSelectedBank();
    }, []);
    const BankRequest = () => {
        if(data.bankName && data.adress){
            setLoading(true);
            axios.put(`/api/banks/`+bankId, data, userAuthenticationConfig()).then(response => {
                if (response.status === responseStatus.HTTP_OK) {
                    data.bankName=null;
                    data.adress=null;
                    navigate("/main");
                }
            }).catch(error => {
                // setError(error.response.data);
                // setNotification({ ...notification, visible: true, type: "error", message: error.response.data });
            }).finally(() => setLoading(false));
        }
    };

    useEffect(() => {
        BankRequest();
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
                    Створити банк
                </title>
            </Helmet>
            <CreateBankForm
                setData={setData}
                loading={loading}
                selectedBank={selectedBank}
            />
        </>
    );
};

export default EditBankPage;