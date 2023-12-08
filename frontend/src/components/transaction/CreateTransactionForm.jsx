import React, { useState } from "react";
import {
    Box,
    Button,
    Grid, InputLabel,
    Link, Select,
    Typography
} from "@mui/material";
import InputCustom from "../elemets/input/InputCustom";
import MenuItem from "@mui/material/MenuItem";
import ModalConfirmPinCode from "../elemets/modalConfirmPinCode/ModalConfirmPinCode";

const CreateTransactionForm = ({ setData, loading, cards, setPinCode}) => {

    const [modalOpen, setModalOpen] = useState(false);
    const handleSubmit = (event) => {
        event.preventDefault();

        const data = {
            summa: parseInt(event.target.summa.value),
            description: event.target.description.value,
            senderCard: event.target.senderCard.value,
            receiverCard: event.target.receiverCard.value
        };
        setData(data);
        setModalOpen(true);
    };

    const handleCloseModal = () => {
        setModalOpen(false);
    };
    const [myBalance, setMyBalance]=useState(null);

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
                                Переказ на картку
                            </Typography>

                            <div>
                                <div>
                                    <h3>Баланс на карті: {myBalance}</h3>
                                    <InputLabel id="senderCard">Оберіть карту</InputLabel>
                                    <Select style={{width:400}}
                                        labelId="senderCard"
                                        id="senderCard"
                                        label="sender Card"
                                        name="senderCard"
                                    >
                                        {cards && cards.map((item, key) => (
                                            <MenuItem key={key} value={item.cardNumber} onClick={()=>{setMyBalance(item.balance)}}>{item.cardNumber}</MenuItem>
                                        ))}
                                    </Select>
                                </div>

                                <InputCustom
                                    id="receiverCard"
                                    type="text"
                                    label="Картка отримувача"
                                    name="receiverCard"
                                    required
                                />

                                <InputCustom
                                    id="summa"
                                    type="text"
                                    label="Сума переказу"
                                    name="summa"
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
                                Створити переказ
                            </Button>
                        </form>
                    </Grid>
                </Grid>
            </Box>
            <ModalConfirmPinCode
                open={modalOpen}
                onClose={() => setModalOpen(false)}
                onNot={handleCloseModal}
                setPinCode={setPinCode}
            />
        </>
    );
};

export default CreateTransactionForm;