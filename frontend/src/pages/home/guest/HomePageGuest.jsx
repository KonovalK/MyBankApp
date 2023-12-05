
import {useNavigate} from "react-router-dom";
import axios from "axios";
import userAuthenticationConfig from "../../../utils/userAuthenticationConfig";
import {responseStatus} from "../../../utils/consts";
import {useEffect, useState} from "react";

const HomePageGuest = () => {
    const navigate = useNavigate();
    const [error, setError] = useState(null);
    const [loading, setLoading] = useState(false);
    const [verificationCode, setVerificationCode] = useState("");
    const [notification, setNotification] = useState({
        visible: false,
        type: "",
        message: ""
    });

    return (
        <div>
            <h1>Тут тіпа буде форма підтвердження</h1>
        </div>
    );
};

export default HomePageGuest;