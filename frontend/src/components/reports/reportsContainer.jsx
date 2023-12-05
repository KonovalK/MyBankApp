import React, { useEffect, useState } from "react";
import axios from "axios";
import userAuthenticationConfig from "../../utils/userAuthenticationConfig";
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
} from "chart.js";
import { Line } from "react-chartjs-2";
import { responseStatus } from "../../utils/consts";

const ReportsContainer = ({cardNumber}) => {
    const [myData, setMyData] = useState([]);

    const loadData = () => {

        axios.get("/api/get-transactions-stat?card=" + cardNumber, userAuthenticationConfig()).then(response => {
            if (response.status === responseStatus.HTTP_OK && response.data) {
                setMyData(response.data);
            }
        }).catch(error => {
            console.log("error");
        });
    };

    useEffect(() => {
        loadData();
    }, []);

    ChartJS.register(
        CategoryScale,
        LinearScale,
        PointElement,
        LineElement,
        Title,
        Tooltip,
        Legend
    );

    const options = {
        responsive: true,
        plugins: {
            legend: {
                position: "top"
            }
        }
    };

    let labels = [];
    labels = myData.map((data) => data.date);

    const chartData = {
        labels,
        datasets: [
            {
                label: "Щоденне коливання суми переказів",
                data: myData.map((data) => data.summa),
                borderColor: "rgb(255, 99, 132)",
                backgroundColor: "rgba(255, 99, 132, 0.5)"
            }
        ]
    };

    return (
        <>
            <h1>Статистика транзакцій по поточній карті</h1>
            <Line options={options} data={chartData} />
        </>
    );
};

export default ReportsContainer;