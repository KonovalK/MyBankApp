import * as React from "react";
import Button from "@mui/material/Button";
import Dialog from "@mui/material/Dialog";
import DialogActions from "@mui/material/DialogActions";
import DialogContent from "@mui/material/DialogContent";
import DialogContentText from "@mui/material/DialogContentText";
import DialogTitle from "@mui/material/DialogTitle";
import { TextField } from "@mui/material";

export default function PopupPayment ({
  onAccept = null, handleClose = null,
  isOpen, loading
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
          Buy Ticket
        </DialogTitle>
        <DialogContent>
          <DialogContentText id="alert-dialog-description">
            Enter you credit card:
            <p></p>
            <TextField
              name="Credit card"
              placeholder="Credit card number"
            />
          </DialogContentText>
        </DialogContent>
        <DialogActions>
          <Button
            onClick={onAccept}
            disabled={loading}
          >Buy</Button>
        </DialogActions>
      </Dialog>
    </div>
  );
}