import * as React from "react";
import Button from "@mui/material/Button";
import Dialog from "@mui/material/Dialog";
import DialogActions from "@mui/material/DialogActions";
import DialogContent from "@mui/material/DialogContent";
import DialogContentText from "@mui/material/DialogContentText";
import DialogTitle from "@mui/material/DialogTitle";

export default function PopupDefault ({
  title = "", description = "",
  acceptLabel = "", declineLabel = "",
  onAccept = null, onDecline = null, handleClose = null,
  isOpen
}) {

  return (
    <div>
      <Dialog
        open={isOpen}
        onClose={handleClose}
        aria-labelledby="alert-dialog-title"
        aria-describedby="alert-dialog-description"
      >
        <DialogTitle id="alert-dialog-title">
          {title}
        </DialogTitle>
        <DialogContent>
          <DialogContentText id="alert-dialog-description">
            {description}
          </DialogContentText>
        </DialogContent>
        <DialogActions>
          <Button onClick={onAccept}>{acceptLabel}</Button>
          <Button onClick={onDecline} autoFocus>
            {declineLabel}
          </Button>
        </DialogActions>
      </Dialog>
    </div>
  );
}