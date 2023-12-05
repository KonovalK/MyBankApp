import React, { lazy } from "react";
import RegistrationPage from "../pages/registration/RegistrationPage";

const HomePage = lazy(() => import("../pages/home/user/HomePageUser"));
const LoginPage = lazy(() => import("../pages/login/LoginPage"));

const routes = [
  {
    path: "/",
    element: <LoginPage />
  },
  {
    path: "/register",
    element: <RegistrationPage />
  },
  {
    path: "/login",
    element: <LoginPage />
  },
];

export default routes;