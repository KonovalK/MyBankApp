import React, { useEffect, useState } from "react";
import { useNavigate, useParams, useSearchParams } from "react-router-dom";
import { Helmet } from "react-helmet-async";
import { Button, Pagination, Grid, MenuItem, Select } from "@mui/material";
import axios from "axios";
import userAuthenticationConfig from "../../../utils/userAuthenticationConfig";
import { responseStatus } from "../../../utils/consts";
import Notification from "../../../components/elemets/notification/Notification";
import FilterGroup from "../../../components/savingsBank/FilterGroup";
import MySavingBank from "../../../components/savingsBank/MySavingBank";

const SavingsBanksPage = () => {
    const navigate = useNavigate();
    const [requestData, setRequestData] = useState();
    const [notification, setNotification] = useState({
        visible: false,
        type: "",
        message: "",
    });
    const [savingsBanks, setSavingsBanks] = useState(null);
    const [error, setError] = useState(null);
    const [sortingOption, setSortingOption] = useState("id");
    const [sortDirection, setSortDirection] = useState("asc");

    const loadSavingsBanks = () => {
        const sortingParams = `?sort=${sortingOption}&direction=${sortDirection}`;

        axios.get(
            "/api/get-savings-banks" + sortingParams, userAuthenticationConfig()).then((response) => {
            if (response.status === responseStatus.HTTP_OK && response.data) {
                setSavingsBanks(response.data);
            }
        }).catch((error) => {
            console.log("error");
        });
    };

    useEffect(() => {
        loadSavingsBanks();
    }, [requestData, sortingOption, sortDirection]);

    const handleSavingBankPlusClick = (savingBankNum) => {
        navigate(`/saving-bank-replenish/${savingBankNum}`);
    };
    const handleSavingBankMinusClick = (savingBankNum) => {
        navigate(`/saving-bank-withdraw/${savingBankNum}`);
    };

    return (
        <>
            {notification.visible && (
                <Notification
                    notification={notification}
                    setNotification={setNotification}
                />
            )}
            <Helmet>
                <title>Мої накопичувальні банки</title>
            </Helmet>
            <h1>Мої накопичувальні банки</h1>
            <Button
                variant="contained"
                type="submit"
                onClick={()=>{navigate('/create-saving-bank')}}
            >
                Створити банку
            </Button>

            <Grid container spacing={1}>
                {savingsBanks  && (
                    <FilterGroup
                        sortingOption={sortingOption}
                        setSortingOption={setSortingOption}
                        sortDirection={sortDirection}
                        setSortDirection={setSortDirection}
                    />
                )}
                <p></p>
                <Grid container spacing={1}>
                    {savingsBanks && savingsBanks.map((item, key) => (
                        <MySavingBank
                            key={key}
                            savingBankInfo={item}
                            onClickPlus={()=>handleSavingBankPlusClick(item.id)}
                            onClickMinus={()=>handleSavingBankMinusClick(item.id)}
                            setNotification={setNotification}
                            notification={notification}
                            setError={setError}
                        />
                    ))}
                </Grid>
            </Grid>
        </>
    );
};

export default SavingsBanksPage;
