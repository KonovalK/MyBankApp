import React, { useEffect, useState } from "react";
import { useNavigate, useParams, useSearchParams } from "react-router-dom";
import { Helmet } from "react-helmet-async";
import axios from "axios";
import userAuthenticationConfig from "../../../utils/userAuthenticationConfig";
import { responseStatus } from "../../../utils/consts";
import Notification from "../../../components/elemets/notification/Notification";
import EditSavingBankForm from "../../../components/savingsBank/EditSavingBankForm";
import {checkFilterItem, fetchFilterData} from "../../../utils/fetchFilterData";

const SelectedSavingBankPlusPage = () => {
    const navigate = useNavigate();
    const { savingBankNum } = useParams();
    const [notification, setNotification] = useState({
        visible: false,
        type: "",
        message: "",
    });
    const [data, setData] = useState([{
        card:null,
        summa:null,
    }]);
    const [loading, setLoading] = useState(false);
    const [searchParams] = useSearchParams();
    const [cards, setCards] = useState([{}]);
    const [error, setError] = useState(null);
    const [filterData, setFilterData] = useState({
        "isVerified": checkFilterItem(searchParams, "isVerified", 1, true),
    });
    const loadCards = () => {
        let filterUrl = fetchFilterData(filterData);
        axios.get("/api/cards" + filterUrl, userAuthenticationConfig()).then(response => {
            if (response.status === responseStatus.HTTP_OK && response.data["hydra:member"]) {
                setCards(response.data["hydra:member"]);
            }
        }).catch(error => {
            console.log("error");
        });
    };

    const savingCardRequest = () => {
        if(data.card && data.summa){
            setLoading(true);
            axios.put(`/api/saving-bank-replenish/${savingBankNum}`, data,userAuthenticationConfig()).then(response => {
                if (response.status === responseStatus.HTTP_OK && response.data) {
                    data.card=null;
                    data.summa=null;
                    navigate("/savings-banks-list")
                }
            }).catch(error => {
                setError(error.response.data);
                setNotification({ ...notification, visible: true, type: "error", message: error.response.data });
            }).finally(() => setLoading(false));
        }
    };

    useEffect(() => {
        loadCards();
    }, []);

    useEffect(() => {
        savingCardRequest();
    }, [data]);

    return (
        <>
            {notification.visible &&
                <Notification
                    notification={notification}
                    setNotification={setNotification}
                />
            }
            <EditSavingBankForm setData={setData} cards={cards} action="replenish"/>
        </>
    );
};

export default SelectedSavingBankPlusPage;
