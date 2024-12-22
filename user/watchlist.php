<?php

use includes\models\WatchList;

require_once "../includes/config/config.php";
require_once "../includes/utility/html_helpers.php";
require_once "../includes/models/WatchList.php";

$customerId = $_SESSION['customerId'] ?? 1;
$pageTitle = "My Watch List";
$watchList = WatchList::getMoviesOnWatchListByCustomerId($customerId, $conn);

//if ($_SERVER["REQUEST_METHOD"] === 'POST') {
//    if (isset($_POST['movieId'])) {
//        $movieId = $_REQUEST['movieId'];
//        WatchList::deleteWatchListEntryForUser($customerId, $movieId, $conn);
//    }
//    header("Location: " . $_SERVER['REQUEST_URI']);
//    exit();
//}

include("../includes/views/user/watchlist-view.php");