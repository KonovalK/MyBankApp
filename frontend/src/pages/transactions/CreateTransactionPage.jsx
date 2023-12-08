import React, { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import axios from "axios";
import { Helmet } from "react-helmet-async";
import Notification from "../../components/elemets/notification/Notification";
import { responseStatus } from "../../utils/consts";
import CreateTransactionForm from "../../components/transaction/CreateTransactionForm";
import userAuthenticationConfig from "../../utils/userAuthenticationConfig";

const CreateTransactionPage = () => {
    const navigate = useNavigate();
    const [error, setError] = useState(null);
    const [loading, setLoading] = useState(false);
    const [data, setData] = useState([{
        senderCard:null,
    }]);
    const [pinCode, setPinCode] = useState(null);
    const [notification, setNotification] = useState({
        visible: false,
        type: "",
        message: ""
    });
    const [cards, setCards] = useState([{}]);

    const loadCards = () => {
        axios.get("/api/cards", userAuthenticationConfig()).then(response => {
            if (response.status === responseStatus.HTTP_OK && response.data["hydra:member"]) {
                setCards(response.data["hydra:member"]);
            }
        }).catch(error => {
            console.log("error");
        });
    };

    const transactionRequest = () => {

        if(data.senderCard && data.pinCode && (data.pinCode.length===4)){
            setLoading(true);
            axios.post(`/api/post-transactions`, data, userAuthenticationConfig()).then(response => {
                if (response.status === responseStatus.HTTP_CREATED) {
                    navigate("/main");
                }
            }).catch(error => {
                setError(error.response.data);
                setNotification({ ...notification, visible: true, type: "error", message: error.response.data });
                setPinCode(null);
            }).finally(() => setLoading(false));
        }
    };

    useEffect(() => {
        transactionRequest();
    }, [data]);

    useEffect(() => {
        setData(prevData => ({
            ...prevData,
            pinCode: pinCode,
        }));
    }, [pinCode]);

    useEffect(() => {
        loadCards();
    }, []);

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
                    Створити транзакцію
                </title>
            </Helmet>
            <CreateTransactionForm
                setData={setData}
                loading={loading}
                cards={cards}
                setPinCode={setPinCode}
            />
        </>
    );
};

export default CreateTransactionPage;