import React, {useEffect, useState} from "react";
import {
    Box,
    Button,
    Grid,
    Typography
} from "@mui/material";
import InputCustom from "../elemets/input/InputCustom";

const CreateSavingsBankForm = ({ setData, loading}) => {

    const data = {
        name: null,
        description: null,
    };
    const handleSubmit = (event) => {
        event.preventDefault();

        data.name = event.target.name.value;
        data.description = event.target.description.value;

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
                                Відправте заявку на створення картки
                            </Typography>

                            <div>
                                <InputCustom
                                    id="name"
                                    type="text"
                                    label="Назва банки"
                                    name="name"
                                    required
                                />
                                <InputCustom
                                    id="description"
                                    type="text"
                                    label="Опис"
                                    name="description"
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

export default CreateSavingsBankForm;