import React, { lazy } from "react";
import routes from "./routes";

const HomePage = lazy(() => import("../pages/home/user/HomePageUser"));
const TransfersPage = lazy(() => import("../pages/transfers/TransfersPage"));
const TransactionsPage = lazy(() => import("../pages/transactions/TransactionsPage"));
const TreasuryPage = lazy(() => import("../pages/treasury/TreasuryPage"));
const CreateTransactionPage = lazy(() => import("../pages/transactions/CreateTransactionPage"));
const CreateCardPage = lazy(() => import("../pages/cards/user/CreateCardPage"));
const SelectedSavingBankPlusPage = lazy(() => import("../pages/savingsBanks/user/SelectedSavingBankPlusPage"));
const SelectedSavingBankMinusPage = lazy(() => import("../pages/savingsBanks/user/SelectedSavingBankMinusPage"));
const CreateSavingBankPage = lazy(() => import("../pages/savingsBanks/user/CreateSavingBankPage"));
const SavingsBanksPage = lazy(() => import("../pages/savingsBanks/user/SavingsBanksPage"));

const userRoutes = [
  {
    path: "/",
    element: <HomePage />
  },
  {
    path: "/main",
    element: <HomePage />
  },
  {
    path: "/transfers",
    element: <TransfersPage />
  },
  {
    path: "/transactions/:cardNumber",
    element: <TransactionsPage />
  },
  {
    path: "/treasury",
    element: <TreasuryPage />
  },
  {
    path: "/create-transaction",
    element: <CreateTransactionPage />
  },
  {
    path: "/create-card",
    element: <CreateCardPage />
  },
  {
    path: "/saving-bank-withdraw/:savingBankNum",
    element: <SelectedSavingBankMinusPage />
  },
  {
    path: "/saving-bank-replenish/:savingBankNum",
    element: <SelectedSavingBankPlusPage />
  },
  {
    path: "/create-saving-bank",
    element: <CreateSavingBankPage />
  },
  {
    path: "/savings-banks-list",
    element: <SavingsBanksPage />
  },
];

const userRoutesConcat = userRoutes.concat(routes);

export default userRoutesConcat;