<?php

use includes\models\Rental;

require_once '../includes/config/config.php';
require_once '../includes/models/Rental.php';
require_once '../includes/utility/helpers.php';
require_once '../includes/utility/html_helpers.php';
require_once '../includes/utility/sql_transactions.php';

$movieId = $_REQUEST['id'] ?? 1;
$customerId = $_SESSION['customerId'] ?? 1;
try {
    $rental = Rental::getRentalByMovieIdAndCustomerId($movieId, $customerId, $conn);
} catch (\Exception $e) {
    $error = $e->getMessage();
    $pageTitle = "Invalid Return Request";
    include "{$baseURL}includes/views/error-view.php";
    exit;
}
$title = $rental['MOVIE_TITLE'];
$posterURL = $rental['MOVIE_POSTER'];
$rentalId = $rental['RENTAL_ID'];
$rentalDate = $rental['RENTAL_DATE'];
$dueDate = $rental['DUE_DATE'];
$date = getCurrentDate();
$lateFee = $rental["LATE_FEE"];
$category = "Return";


if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $status = "confirm";
    $message = "";
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $error = updateRentalReturn($rentalId, $conn);
    if ($error) {
        $status = "failure";
        $message = $error;
    } else {
        $status = "success";
        $message = "Your return was successful";
    }
}

include '../includes/views/rental/return-view.php';
