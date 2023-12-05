import * as React from "react";
import AppBar from "@mui/material/AppBar";
import Box from "@mui/material/Box";
import Toolbar from "@mui/material/Toolbar";
import Typography from "@mui/material/Typography";
import IconButton from "@mui/material/IconButton";
import AccountCircle from "@mui/icons-material/AccountCircle";
import Menu from "@mui/material/Menu";
import DropdownLogged from "./DropdownLogged";
import DropdownUnlogged from "./DropdownUnlogged";
import { Button } from "@mui/material";
import { NavLink, useNavigate } from "react-router-dom";
import { useContext } from "react";
import { AppContext } from "../../../../App";

export default function MenuAppBar () {

  const navigate = useNavigate();
  const { user, authenticated } = useContext(AppContext);
  const [anchorEl, setAnchorEl] = React.useState(null);

  const isUserOrAdmin = authenticated && user.roles && (user.roles.includes('ROLE_USER') || user.roles.includes('ROLE_ADMIN'));

  const handleMenu = (event) => {
    setAnchorEl(event.currentTarget);
    console.log(user)
  };

  const goTo = (route) => {
    navigate(route);
    handleClose();
  };

  const handleClose = () => {
    setAnchorEl(null);
  };

  return (
    <Box sx={{ flexGrow: 1 }}>
      <AppBar
        position="static" style={{
        display: "flex",
        width: "100%",
        justifyContent: "space-between",
        alignItems: "center",
        color: "black",
        backgroundColor: "rgba(0, 0, 255, 0)",
        opacity: 100,
        boxShadow: "none"
      }}
      >
        <Toolbar style={{ width: "100%" }}>
          <Typography variant="h6" component="div" sx={{ flexGrow: 1 }} style={{ fontSize: "25px", fontWeight: 900 }}>
            <IconButton onClick={() => goTo("main")} sx={{ color: "black" }}>
              My Bank App
            </IconButton>
          </Typography>
            <div>
              <IconButton
                size="large"
                aria-label="account of current user"
                aria-controls="menu-appbar"
                aria-haspopup="true"
                onClick={handleMenu}
                color="inherit"
              >
                <AccountCircle />
              </IconButton>
              <Menu
                id="menu-appbar"
                anchorEl={anchorEl}
                anchorOrigin={{
                  vertical: "top",
                  horizontal: "right"
                }}
                keepMounted
                transformOrigin={{
                  vertical: "top",
                  horizontal: "right"
                }}
                open={Boolean(anchorEl)}
                onClose={handleClose}
              >
                {authenticated &&
                  <DropdownLogged goTo={goTo} />
                }
                {!authenticated &&
                  <DropdownUnlogged goTo={goTo} />
                }
              </Menu>
            </div>
        </Toolbar>
      </AppBar>
    </Box>
  );
}