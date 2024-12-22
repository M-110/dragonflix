<?php

use includes\models\Transaction;

require_once '../includes/config/config.php';
require_once '../includes/models/Transaction.php';

$transactions = Transaction::getTransactionHistory($conn);
$revenue = Transaction::getTotalRevenue($conn);
$rentals = Transaction::getCurrentLateRentals($conn);
$history = Transaction::getRentalCountByMonth($conn);

include "../includes/views/transaction/transactions-view.php";

