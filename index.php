<?php

use includes\models\Movie;
use includes\models\WatchList;

require_once('includes/config/config.php');
require_once('includes/utility/html_helpers.php');
require_once('includes/models/Movie.php');
require_once('includes/models/WatchList.php');

$pageTitle = 'Home';
$customerId = $_SESSION['customerId'] ?? 1;
$topTenMovies = Movie::getTopTenMovies($customerId, $conn);

$featuredMovies = Movie::getFeaturedMoviesList($conn);
$watchList = WatchList::getMoviesOnWatchListByCustomerId($customerId, $conn);
$popularMovies = Movie::getPopularMoviesList($conn);
$dramaMovies = Movie::getMoviesListByGenre("Drama", $conn);
$actionMovies = Movie::getMoviesListByGenre("Action", $conn);;
$comedyMovies = Movie::getMoviesListByGenre("Comedy", $conn);;
$animationMovies = Movie::getMoviesListByGenre("Animation", $conn);;


include 'includes/views/index-view.php';