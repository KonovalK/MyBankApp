import { Route, Routes, useLocation, useNavigate } from "react-router-dom";
import { createContext, Suspense, useEffect, useMemo, useState } from "react";
import { HelmetProvider } from "react-helmet-async";
import { CircularProgress } from "@mui/material";
import nprogress from "nprogress";
import NotFoundPage from "./pages/notFound/NotFoundPage";
import routes from "./routes/routes";
import userRoutesConcat from "./routes/userRoutes";
import getUserInfo from "./utils/getUserInfo";
import eventBus from "./utils/eventBus";
import "nprogress/nprogress.css";
import "./assets/css/main.css";
import MenuAppBar from "./components/elemets/global/menu/MenuAppBar";
import { roles } from "./utils/consts";
import adminRoutesConcat from "./routes/adminRoutes";
import guestRoutesConcat from "./routes/guestRoutes";

export const AppContext = createContext({});

function App () {
  const [authenticated, setAuthenticated] = useState(localStorage.getItem("token"));
  const location = useLocation();
  const navigate = useNavigate();

  const authRouteRender = () => {
    if (!authenticated) {

      return (
        routes.map((route, index) => (
          <Route key={index} path={route.path} element={route.element} />
        ))
      );
    }
    else {
      const userInfo = getUserInfo();

      if (userInfo) {
        //if user logged in
        const userRoles = userInfo.roles;

        //USER
        if (userRoles.includes(roles.CLIENT)) {
          return (
            userRoutesConcat.map((route, index) => (
              <Route key={index} path={route.path} element={route.element} />
            )));
        }
        //ADMIN
        else if (userRoles.includes(roles.ADMIN)) {
          return (
            adminRoutesConcat.map((route, index) => (
              <Route key={index} path={route.path} element={route.element} />
            )));
        }
        //GUEST
        else if (userRoles.includes(roles.GUEST)) {
          return (
              guestRoutesConcat.map((route, index) => (
                  <Route key={index} path={route.path} element={route.element} />
              )));
        }
      }
    }
  };

  const handleOnIdle = () => {
    eventBus.on("logout", (data) => {

      localStorage.removeItem("clientId");
      localStorage.removeItem("token");

      setAuthenticated(false);
      navigate("/");
    });
  };

  useMemo(() => {
    nprogress.start();
  }, [location]);

  useEffect(() => {
    nprogress.done();
  }, [location]);

  useEffect(() => {
    handleOnIdle();
  }, []);

  return (
    <AppContext.Provider
      value={{
        authenticated,
        setAuthenticated,
        user: getUserInfo()
      }}
    >
      <MenuAppBar />
      <HelmetProvider>
        <div className="container" style={{ width: "100%" }}>
          <Suspense fallback={<CircularProgress />}>
            <Routes>
              {authRouteRender()}
              <Route path="*" element={<NotFoundPage />} />
            </Routes>
          </Suspense>
        </div>
      </HelmetProvider>
    </AppContext.Provider>
  );
}

export default App;
