import {Button, TableCell, TableRow} from "@mui/material";

const Transaction = ({ align = "left", transaction}) => {

    return <>
        <TableRow>
            <TableCell align={align}>
                {transaction.sender}
            </TableCell>
            <TableCell align={align}>
                {transaction.receiver}
            </TableCell>
            <TableCell align={align}>
                {transaction.summa}
            </TableCell>
            <TableCell align={align}>
                {transaction.description}
            </TableCell>
            <TableCell align={align}>
                {new Date(transaction.date * 1000).toLocaleDateString()}
            </TableCell>
        </TableRow>
    </>;
};

export default Transaction;