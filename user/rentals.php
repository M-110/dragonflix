<?php

use includes\models\Rental;

require_once "../includes/config/config.php";
require_once "../includes/utility/html_helpers.php";
require_once "../includes/models/Rental.php";

$customerId = $_SESSION['customerId'] ?? 1;
$pageTitle = "My Rentals";
$currentRentals = Rental::getNonLateRentalsByCustomerId($customerId, $conn);
$lateRentals = Rental::getLateRentalsByCustomerId($customerId, $conn);

include("../includes/views/user/rentals-view.php");