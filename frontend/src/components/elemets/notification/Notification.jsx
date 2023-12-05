import { Alert, Snackbar } from "@mui/material";

const Notification = ({ notification, setNotification }) => {
  const handleCloseAlert = () => {
    setNotification({ ...notification, visible: false });
  };

  return (
    <Snackbar
      anchorOrigin={{ vertical: "top", horizontal: "center" }} open={notification.visible} onClose={handleCloseAlert}
    >
      <Alert severity={notification.type}>
        {notification.message}
      </Alert>
    </Snackbar>
  );
};

export default Notification;