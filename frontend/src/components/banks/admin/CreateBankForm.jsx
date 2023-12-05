import React, { useState } from "react";
import {
    Box,
    Button,
    Grid,
    Typography
} from "@mui/material";
import InputCustom from "../../elemets/input/InputCustom";
import MenuItem from "@mui/material/MenuItem";

const CreateBankForm = ({ setData, loading, selectedBank={bankName: "", adress: ""}}) => {

    const handleSubmit = (event) => {
        event.preventDefault();

        const data = {
            bankName: event.target.bankName.value,
            adress: event.target.adress.value,
        };

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
                                Створити банк
                            </Typography>

                            <div>
                                <InputCustom
                                    id="bankName"
                                    type="text"
                                    label="Назва банку"
                                    name="bankName"
                                    myValue={selectedBank.bankName}
                                    required
                                />

                                <InputCustom
                                    id="adress"
                                    type="text"
                                    label="Адреса банку"
                                    name="adress"
                                    myValue={selectedBank.adress}
                                    required
                                />
                            </div>

                            <Button
                                variant="contained"
                                type="submit"
                                disabled={loading}
                            >
                                Створити банк
                            </Button>
                        </form>
                    </Grid>
                </Grid>
            </Box>
        </>
    );
};

export default CreateBankForm;