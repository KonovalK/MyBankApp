
const MyCard = ({cardInfo, onClick}) => {
    return (
        <div onClick={onClick} style={{border:2, borderStyle:"solid", width:200, height:100, borderRadius:20, marginLeft:50, padding:20}}>
            <h3>{cardInfo.cardNumber}</h3>
            <h5>{cardInfo.expirationDate}</h5>
        </div>
    );
};

export default MyCard;