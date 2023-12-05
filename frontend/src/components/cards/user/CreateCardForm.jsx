import React, {useEffect, useState} from "react";
import {
    Box,
    Button,
    Grid,
    Typography
} from "@mui/material";
import InputCustom from "../../elemets/input/InputCustom";
import MenuItem from "@mui/material/MenuItem";
import SearchFilterSelect from "../../elemets/searchFilter/SearchFilterDefault";
import axios from "axios";
import userAuthenticationConfig from "../../../utils/userAuthenticationConfig";
import {responseStatus} from "../../../utils/consts";
import TemplatesSelect from "../../elemets/select/templates/TemplatesSelect";
import RateSelect from "../../elemets/select/rates/RateSelect";

const CreateCardForm = ({ setData, loading, banks, rates}) => {

    const data = {
        template: null,
        balance: null,
        pin:null,
        rate:null,
    };
    const [selectBank, setSelectBank]=useState({
        bank: null,
    });
    const [selectTemplate, setSelectTemplate]=useState({
        template: null,
    });
    const [selectRate, setSelectRate]=useState({
        rate: null,
    });
    const [templates, setTemplates]=useState([{}]);
    const handleSubmit = (event) => {
        event.preventDefault();

        data.template = "/api/card-templates/" + selectTemplate.template;
        data.balance = parseInt(event.target.balance.value, 10);
        data.pin = parseInt(event.target.pinKod.value,10);
        data.rate = "/api/exchange-rates/" + selectRate.rate;
        setData(data);
    };

    const loadTemplatesForBank = () => {
        axios.get("/api/card-templates?bank=" + selectBank.bank, userAuthenticationConfig()).then(response => {
            if (response.status === responseStatus.HTTP_OK && response.data["hydra:member"]) {
                setTemplates(response.data["hydra:member"]);
            }
        }).catch(error => {
            console.log("error");
        });
    };

    useEffect(() => {
        loadTemplatesForBank();
    }, [selectBank]);



    return (
        <>
            <Box
                sx={{
                    marginTop: 8,
                    display: "flex",
                    flexDirection: "column",
                    alignItems: "center"
                }}
            >
                <Grid container>
                    <Grid item xs={11} lg={5} sx={{ margin: "auto" }}>
                        <form className="auth-form" onSubmit={handleSubmit}>
                            <Typography variant="h4" component="h1">
                                Відправте заявку на створення картки
                            </Typography>

                            <div>
                                <p>Оберіть банк *</p>
                                {banks && banks.length > 0 && (

                                    <SearchFilterSelect
                                        inputLabel="Банк"
                                        filterData={selectBank}
                                        setFilterData={setSelectBank}
                                        fieldName="bank"
                                        banks={banks}
                                    />
                                )}
                                <p>Оберіть тип картки *</p>
                                {templates && templates.length > 0 && (
                                    <TemplatesSelect
                                        inputLabel="Шаблон картки"
                                        filterData={selectTemplate}
                                        setFilterData={setSelectTemplate}
                                        fieldName="template"
                                        templates={templates}
                                    />
                                )}
                                <p>Оберіть валюту *</p>
                                {rates && rates.length > 0 && (
                                    <RateSelect
                                        inputLabel="Оберіть валюту"
                                        filterData={selectRate}
                                        setFilterData={setSelectRate}
                                        fieldName="rate"
                                        rates={rates}
                                    />
                                )}
                                <InputCustom
                                    id="pinKod"
                                    type="text"
                                    label="Придумайте pin-код"
                                    name="pinKod"
                                    required
                                />
                                <InputCustom
                                    id="balance"
                                    type="text"
                                    label="Початковий баланс"
                                    name="balance"
                                    required
                                />
                            </div>

                            <Button
                                variant="contained"
                                type="submit"
                                disabled={loading}
                            >
                                Відправити
                            </Button>
                        </form>
                    </Grid>
                </Grid>
            </Box>
        </>
    );
};

export default CreateCardForm;