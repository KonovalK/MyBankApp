import React, { useEffect, useState } from "react";
import { useNavigate, useParams, useSearchParams } from "react-router-dom";
import { Helmet } from "react-helmet-async";
import { Button, Pagination, Grid, MenuItem, Select } from "@mui/material";
import axios from "axios";
import userAuthenticationConfig from "../../utils/userAuthenticationConfig";
import { responseStatus } from "../../utils/consts";
import Notification from "../../components/elemets/notification/Notification";
import TableGenerator from "../../components/elemets/table/TableGenerator";
import Transaction from "../../components/transaction/transaction";
import FilterGroup from "../../components/transaction/FilterGroup";
import { checkFilterItem, fetchFilterData } from "../../utils/fetchFilterData";
import ReportsContainer from "../../components/reports/reportsContainer";

const TransactionPage = () => {
    const { cardNumber } = useParams();
    const navigate = useNavigate();
    const [requestData, setRequestData] = useState();
    const [notification, setNotification] = useState({
        visible: false,
        type: "",
        message: "",
    });

    const [transactions, setTransactions] = useState(null);
    const [searchParams] = useSearchParams();

    const [paginationInfo, setPaginationInfo] = useState({
        totalItems: null,
        totalPageCount: null,
        itemsPerPage: 10,
    });

    const [filterData, setFilterData] = useState({
        page: checkFilterItem(searchParams, "page", 1, true),
        description: checkFilterItem(searchParams, "description", ""),
        receiver: checkFilterItem(searchParams, "receiver", ""),
        senderCard: checkFilterItem(searchParams, "", cardNumber),
    });

    const [sortingOption, setSortingOption] = useState("id");
    const [sortDirection, setSortDirection] = useState("asc");

    const loadTransactions = () => {
        let filterUrl = fetchFilterData(filterData);
        navigate(filterUrl);

        const sortingParams = `&sort=${sortingOption}&direction=${sortDirection}`;

        axios.get(
                "/api/get-transactions" +
                filterUrl +
                "&itemsPerPage=" +
                paginationInfo.itemsPerPage +
                sortingParams,
                userAuthenticationConfig()
            ).then((response) => {
                if (
                    response.status === responseStatus.HTTP_OK &&
                    response.data["hydra:member"]
                ) {
                    setTransactions(response.data["hydra:member"]);
                    setPaginationInfo({
                        ...paginationInfo,
                        totalItems: response.data["hydra:totalItems"],
                        totalPageCount: Math.ceil(
                            response.data["hydra:totalItems"] / paginationInfo.itemsPerPage
                        ),
                    });
                }
            }).catch((error) => {
                console.log("error");
            });
    };

    useEffect(() => {
        loadTransactions();
    }, [requestData, filterData, sortingOption, sortDirection]);

    const onChangePage = (event, page) => {
        setFilterData({ ...filterData, page: page });
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
                <title>My Transactions</title>
            </Helmet>

            <Grid container spacing={1}>
                <FilterGroup
                    filterData={filterData}
                    setFilterData={setFilterData}
                    sortingOption={sortingOption}
                    setSortingOption={setSortingOption}
                    sortDirection={sortDirection}
                    setSortDirection={setSortDirection}
                />

                <p></p>

                <TableGenerator
                    titles={["Відправник", "Отримувач", "Сума переказу", "Опис", "Дата"]}
                    items={
                        transactions &&
                        transactions.map((item, key) => (
                            <Transaction key={key} transaction={item} />
                        ))
                    }
                />
                {paginationInfo.totalPageCount > 1 && (
                    <Pagination
                        count={paginationInfo.totalPageCount}
                        shape="rounded"
                        page={filterData.page}
                        onChange={(event, page) => onChangePage(event, page)}
                    />
                )}
                <ReportsContainer cardNumber={filterData.senderCard}/>
            </Grid>
        </>
    );
};

export default TransactionPage;
