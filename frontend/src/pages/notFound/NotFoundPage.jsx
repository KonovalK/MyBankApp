import { Alert, Breadcrumbs, Link, Typography } from "@mui/material";
import { Helmet } from "react-helmet-async";
import { NavLink } from "react-router-dom";
import React from "react";

const NotFoundPage = () => {
  return (
    <>
      <Helmet>
        <title>
          Page not found
        </title>
      </Helmet>
      <Breadcrumbs mb={3} aria-label="breadcrumb">
        <Link component={NavLink} underline="hover" color="inherit" to="/">
          Home
        </Link>
        <Typography color="text.primary">Page not found</Typography>
      </Breadcrumbs>
      <Alert severity="warning">Page not found</Alert>
    </>
  );
};

export default NotFoundPage;