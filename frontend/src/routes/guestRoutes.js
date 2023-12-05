import React, { lazy } from "react";
import routes from "./routes";
const HomePage = lazy(() => import("../pages/home/guest/HomePageGuest"));

const guestRoutes = [
    {
        path: "/",
        element: <HomePage />
    }
];

const guestRoutesConcat = guestRoutes.concat(routes);

export default guestRoutesConcat;