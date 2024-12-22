<?php

use includes\models\Movie;

require_once '../includes/config/config.php';
require_once '../includes/utility/html_helpers.php';
require_once '../includes/models/Movie.php';
$pageTitle = "Search";
$query =$_REQUEST['q'] ?? '';

if ($query != '')
{
    $query = trim($query);
    $searchResults = Movie::getMoviesByQuery($query, $conn);
}
else
    $searchResults = [];

include "../includes/views/movie/search-view.php";
