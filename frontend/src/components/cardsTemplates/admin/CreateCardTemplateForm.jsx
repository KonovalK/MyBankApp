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

const CreateCardTemplateForm = ({ setData, loading, banks}) => {

    const data = {
        cardType: null,
        cardBackgroundPhoto: "default",
        otherCardPropereties: null,
        bank: null,
    };
    const [selectBank, setSelectBank]=useState({
        bank: null,
    });
    const handleSubmit = (event) => {
        event.preventDefault();

        data.cardType = event.target.cardType.value;
        //data.cardBackgroundPhoto = event.target.cardBackgroundPhoto.value;
        data.otherCardPropereties = event.target.otherCardPropereties.value;
        data.bank= "/api/banks/" + selectBank.bank;
        setData(data);
    };
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
                                Створити Шаблон картки
                            </Typography>

                            <div>
                                {banks && banks.length > 0 && (
                                    <SearchFilterSelect
                                        inputLabel="Банк"
                                        filterData={selectBank}
                                        setFilterData={setSelectBank}
                                        fieldName="bank"
                                        banks={banks}
                                    />
                                )}
                                <InputCustom
                                    id="cardType"
                                    type="text"
                                    label="cardType"
                                    name="cardType"
                                    required
                                />

                                {/*<InputCustom*/}
                                {/*    id="cardBackgroundPhoto"*/}
                                {/*    type="text"*/}
                                {/*    label="cardBackgroundPhoto"*/}
                                {/*    name="cardBackgroundPhoto"*/}
                                {/*    required*/}
                                {/*/>*/}
                                <InputCustom
                                    id="otherCardPropereties"
                                    type="text"
                                    label="otherCardPropereties"
                                    name="otherCardPropereties"
                                    required
                                />
                            </div>

                            <Button
                                variant="contained"
                                type="submit"
                                disabled={loading}
                            >
                                Створити Шаблон
                            </Button>
                        </form>
                    </Grid>
                </Grid>
            </Box>
        </>
    );
};

export default CreateCardTemplateForm;