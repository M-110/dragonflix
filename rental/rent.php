<?php

use includes\models\Copy;
use includes\models\Customer;
use includes\models\Movie;
use includes\models\Rental;
use includes\models\Transaction;

require_once '../includes/config/config.php';
require_once '../includes/models/Copy.php';
require_once '../includes/models/Customer.php';
require_once '../includes/models/Movie.php';
require_once '../includes/models/Rental.php';
require_once '../includes/models/Transaction.php';
include_once '../includes/utility/helpers.php';
include_once '../includes/utility/html_helpers.php';
include_once '../includes/utility/sql_transactions.php';


$movieId = $_REQUEST['id'] ?? 1;
$customerId = $_SESSION['customerId'] ?? 1;
$title = Movie::getTitleByMovieId($movieId, $conn);
$posterURL = Movie::getPosterURLByMovieId($movieId, $conn);
$date = getCurrentDate();
$dueDate = getDueDate();
$rentalFee = 5.99;
$category = "Rent";


if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $status = "confirm";
    $message = "";
} else if ($_SERVER["REQUEST_METHOD"] == "POST")  {
    $error = createRental($conn, $movieId, $rentalFee);
    if ($error){
        $status = "failure";
        $message = $error;
    }
    else {
        $status = "success";
        $message = "Your rental was successful";
    }
}

include '../includes/views/rental/rent-view.php';