<?php

use includes\models\Customer;

require_once "../includes/config/config.php";
require_once "../includes/models/Customer.php";


$customerId = $_SESSION['customerId'] ?? 1;
$pageTitle = "Profile";
$customers = Customer::getAllCustomers($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['customerId'] = $_POST['customerId'];
    header("Location: profile.php");
    exit();
}

include("../includes/views/user/switch-view.php");