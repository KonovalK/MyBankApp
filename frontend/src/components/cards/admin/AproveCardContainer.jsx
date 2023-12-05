import { NavLink, useNavigate, useSearchParams } from "react-router-dom";
import { Helmet } from "react-helmet-async";
import { Button, Pagination } from "@mui/material";
import Grid from "@mui/material/Grid";
import React, { useEffect, useState } from "react";
import { checkFilterItem, fetchFilterData } from "../../../utils/fetchFilterData";
import axios from "axios";
import userAuthenticationConfig from "../../../utils/userAuthenticationConfig";
import { responseStatus } from "../../../utils/consts";
import Notification from "../../elemets/notification/Notification";
import DataTable from "./DataTable";

const AproveCardContainer = () => {

    const navigate = useNavigate();
    const [requestData, setRequestData] = useState();
    const [notification, setNotification] = useState({
        visible: false,
        type: "",
        message: ""
    });

    const [cards, setCards] = useState(null);
    const [searchParams] = useSearchParams();

    const [paginationInfo, setPaginationInfo] = useState({
        totalItems: null,
        totalPageCount: null,
        itemsPerPage: 10
    });

    const [filterData, setFilterData] = useState({
        "page": checkFilterItem(searchParams, "page", 1, true),
        "isVerified": checkFilterItem(searchParams, "isVerified", 0, true),
    });

    const loadCards = () => {
        let filterUrl = fetchFilterData(filterData);
        navigate(filterUrl);

        axios.get("/api/cards" + filterUrl + "&itemsPerPage=" + paginationInfo.itemsPerPage, userAuthenticationConfig()).then(response => {
            if (response.status === responseStatus.HTTP_OK && response.data["hydra:member"]) {
                setCards(response.data["hydra:member"]);
                setPaginationInfo({
                    ...paginationInfo,
                    totalItems: response.data["hydra:totalItems"],
                    totalPageCount: Math.ceil(response.data["hydra:totalItems"] / paginationInfo.itemsPerPage)
                });
            }
        }).catch(error => {
            console.log("error");
        });
    };

    useEffect(() => {
        loadCards();
    }, [requestData, filterData]);

    const onChangePage = (event, page) => {
        setFilterData({ ...filterData, page: page });
    };

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
                    Список кард на підтвердження
                </title>
            </Helmet>

            <Grid container spacing={1}>
                <DataTable fetchedData={cards} reloadData={loadCards} />
                {paginationInfo.totalPageCount > 1 &&
                    <Pagination
                        count={paginationInfo.totalPageCount}
                        shape="rounded"
                        page={filterData.page}
                        onChange={(event, page) => onChangePage(event, page)}
                    />}
            </Grid>
        </>
    );
};

export default AproveCardContainer;