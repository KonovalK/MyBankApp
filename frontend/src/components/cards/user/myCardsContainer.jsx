import {useNavigate, useSearchParams} from "react-router-dom";
import React, {useEffect, useState} from "react";
import axios from "axios";
import userAuthenticationConfig from "../../../utils/userAuthenticationConfig";
import {responseStatus} from "../../../utils/consts";
import {Helmet} from "react-helmet-async";
import Grid from "@mui/material/Grid";
import MyCard from "./myCard";
import {checkFilterItem, fetchFilterData} from "../../../utils/fetchFilterData";
const MyCardsContainer = () => {

    const navigate = useNavigate();

    const handleCardClick = (cardNum) => {
        navigate(`/transactions/${cardNum}`);
    };

    const [searchParams] = useSearchParams();

    const [cards, setCards] = useState([{}]);

    const [filterData, setFilterData] = useState({
        "isVerified": checkFilterItem(searchParams, "isVerified", 1, true),
    });
    const loadCards = () => {
        let filterUrl = fetchFilterData(filterData);
        navigate(filterUrl);

        axios.get("/api/cards" + filterUrl, userAuthenticationConfig()).then(response => {
            if (response.status === responseStatus.HTTP_OK && response.data["hydra:member"]) {
                setCards(response.data["hydra:member"]);
            }
        }).catch(error => {
            console.log("error");
        });
    };

    useEffect(() => {
        loadCards();
    }, []);
    return (
        <>
            <Helmet>
                <title>
                    My Cards
                </title>
            </Helmet>
            <h1 style={{marginLeft:50}}>МОЇ КАРТКИ</h1>
            <Grid container spacing={1}>
                {cards && cards.map((item, key) => (
                    <MyCard
                        key={key}
                        cardInfo={item}
                        onClick={()=>handleCardClick(item.cardNumber)}
                    />
                ))}
            </Grid>
        </>
    );
};

export default MyCardsContainer;