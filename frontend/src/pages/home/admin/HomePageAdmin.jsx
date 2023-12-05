import BanksContainer from "../../../components/banks/admin/BanksContainer";
import {Button, Pagination} from "@mui/material";
import CardsTemplatesContainer from "../../../components/cardsTemplates/admin/CardsTemplatesContainer";
import React, {useState} from "react";
import {useNavigate} from "react-router-dom";

const HomePageAdmin = () => {
    const navigate = useNavigate();
    const [myComponent, setMyComponent]=useState(1);
    const [banks, setBanks]=useState(null);
    return (
        <div>
            <h1>Головна сторінка адміністратора</h1>
            {myComponent === 1 &&
                <>
                    <Button onClick={()=>{setMyComponent(2)}} variant="outlined" style={{marginBottom:20}}>До списку шаблонів</Button>
                    <Button onClick={()=>{navigate("/card-aprove")}} variant="outlined" style={{marginBottom:20}}>До підтвердження карток</Button>
                    <BanksContainer banks={banks} setBanks={setBanks}/>
                </>
            }
            {myComponent === 2 &&
                <>
                    <Button onClick={()=>{setMyComponent(1)}} variant="outlined" style={{marginBottom:20}}>До списку банків</Button>
                    <Button onClick={()=>{navigate("/card-aprove")}} variant="outlined" style={{marginBottom:20}}>До підтвердження карток</Button>
                    <CardsTemplatesContainer banks={banks}/>
                </>
            }
        </div>
    );
};

export default HomePageAdmin;