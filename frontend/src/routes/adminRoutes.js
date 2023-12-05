import React, { lazy } from "react";
import routes from "./routes";
const HomePage = lazy(() => import("../pages/home/admin/HomePageAdmin"));
const CreateBankPage = lazy(() => import("../pages/bank/admin/CreateBankPage"));
const CreateCardTemplatePage = lazy(() => import("../pages/cardsTemplates/admin/CreateCardTemplatePage"));
const EditBankPage = lazy(() => import("../pages/bank/admin/EditBankPage"));
const AproveCardPage = lazy(() => import("../pages/cards/admin/AproveCardPage"));

const adminRoutes = [
    {
        path: "/",
        element: <HomePage />
    },
    {
        path: "/main",
        element: <HomePage />
    },
    {
        path: "/create-bank",
        element: <CreateBankPage />
    },
    {
        path: "/create-template",
        element: <CreateCardTemplatePage />
    },
    {
        path: "/banks/edit/:id",
        element: <EditBankPage/>
    },
    {
        path: "/card-aprove",
        element: <AproveCardPage/>
    },
];

const adminRoutesConcat = adminRoutes.concat(routes);

export default adminRoutesConcat;