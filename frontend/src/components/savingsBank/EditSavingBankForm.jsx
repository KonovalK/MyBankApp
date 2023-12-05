import React, {useEffect, useState} from "react";
import {
    Box,
    Button,
    Grid, InputLabel, Select,
    Typography
} from "@mui/material";
import InputCustom from "../elemets/input/InputCustom";
import MenuItem from "@mui/material/MenuItem";

const EditSavingBankForm = ({ setData, cards, action}) => {

    const data = {
        card: null,
        summa: null,
    };
    const handleSubmit = (event) => {
        event.preventDefault();

        data.card = event.target.card.value;
        data.summa = event.target.summa.value;

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
                            {action === 'replenish' && (
                                <Typography variant="h4" component="h1">
                                    Поповніть банку
                                </Typography>
                            )}
                            {action === 'withdraw' && (
                                <Typography variant="h4" component="h1">
                                    Зніміть кошти з накопичувальної банки
                                </Typography>
                            )}
                            <div>
                                <InputLabel id="card">Оберіть карту</InputLabel>
                                <Select style={{width:400}}
                                        labelId="card"
                                        id="card"
                                        label=""
                                        name="card"
                                >
                                    {cards && cards.map((item, key) => (
                                        <MenuItem key={key} value={item.cardNumber} >{item.cardNumber}</MenuItem>
                                    ))}
                                </Select>
                            </div>
                            <div>
                                <InputCustom
                                    id="summa"
                                    type="text"
                                    label="Введіть сумму"
                                    name="summa"
                                    required
                                />
                            </div>
                            {action === 'replenish' && (
                                <Button
                                    variant="contained"
                                    type="submit"
                                >
                                    Поповнити банку
                                </Button>
                            )}
                            {action === 'withdraw' && (
                                <Button
                                    variant="contained"
                                    type="submit"
                                >
                                    Зняти кошти
                                </Button>
                            )}
                        </form>
                    </Grid>
                </Grid>
            </Box>
        </>
    );
};

export default EditSavingBankForm;