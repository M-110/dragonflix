<?php

use includes\models\Customer;

require_once "../includes/config/config.php";
require_once "../includes/models/Customer.php";

$customerId = $_SESSION['customerId'] ?? 1;
$pageTitle = "Profile";
$customerDetails = Customer::getCustomerById($customerId, $conn);
$email = $customerDetails['CUSTOMER_EMAIL'];
$name = $customerDetails["CUSTOMER_FIRST_NAME"] . " " . $customerDetails["CUSTOMER_LAST_NAME"];
$phone = $customerDetails["CUSTOMER_PHONE"];

include("../includes/views/user/customer-view.php");