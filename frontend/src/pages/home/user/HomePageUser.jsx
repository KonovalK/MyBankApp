import MyCardsContainer from "../../../components/cards/user/myCardsContainer";
import {Button} from "@mui/material";
import {useNavigate} from "react-router-dom";


const HomePageUser = () => {
    const navigate = useNavigate();
  return (
    <div>
      <MyCardsContainer/>
        <div style={{marginTop:20, marginLeft:20}}>
            <Button style={{marginLeft:20}} onClick={()=>navigate("/create-transaction")} variant="contained">Створити переказ</Button>
            <Button style={{marginLeft:20}} onClick={()=>navigate("/create-card")} variant="contained">Створити картку</Button>
            <Button style={{marginLeft:20}} onClick={()=>navigate("/savings-banks-list")} variant="contained">До накопичувальних банок</Button>
        </div>
    </div>
  );
};

export default HomePageUser;