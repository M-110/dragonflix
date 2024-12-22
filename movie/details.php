<?php

use includes\models\Customer;
use includes\models\Movie;
use includes\models\Review;
use includes\models\WatchList;

require_once '../includes/config/config.php';
require_once '../includes/utility/html_helpers.php';
require_once '../includes/models/Customer.php';
require_once '../includes/models/Movie.php';
require_once '../includes/models/Review.php';
require_once '../includes/models/WatchList.php';

$movieId = $_REQUEST['id'] ?? 1;
$customerId = $_SESSION['customerId'] ?? 1;
$editReview = isset($_GET['openEditReview']);
$anchor = $_REQUEST['anchor'] ?? '';


if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    if (isset($_POST['addToWatchList']))
        WatchList::createWatchListEntryForUser($customerId, $movieId, $conn);
    if (isset($_POST['removeFromWatchList']))
        WatchList::deleteWatchListEntryForUser($customerId, $movieId, $conn);
    if (isset($_POST['addReview']))
        Review::createReview($customerId, $movieId, $_POST['rating'], $_POST['review-content'], $conn);
    if (isset($_POST['deleteReview']))
        Review::deleteReview($customerId, $movieId, $conn);
    if (isset($_POST['editReview']))
        Review::editReview($customerId, $movieId, $_POST['rating'], $_POST['review-content'], $conn);
    header("Location: " . $_SERVER['REQUEST_URI'] . $anchor);
    exit();
}

$movieDetails = Movie::getMovieDetailsByMovieId($movieId, $customerId, $conn);
$suggestedMovies = Movie::getSuggestedMovies($movieId, $conn);
$ratingOverview = Review::getRatingOverview($movieId, $conn);
$movieReviews = Review::getReviewsByMovieId($movieId, $conn);
$userHasReviewed = in_array($customerId, array_column($movieReviews, 'CUSTOMER_ID'));

$pageTitle = $movieDetails["MOVIE_TITLE"];

$backdropURL = $movieDetails["MOVIE_BACKDROP"];
$movieTitle = $movieDetails["MOVIE_TITLE"];
$mainGenre = $movieDetails["GENRES"][0];
$year = $movieDetails["MOVIE_YEAR"];
$length = $movieDetails["MOVIE_RUNTIME"];
$rating = $movieDetails["MOVIE_RATING"];
$isRented = $movieDetails["IS_RENTED"];
$isAvailable = $movieDetails["IS_AVAILABLE"];
$isInWatchList = $movieDetails["IN_WATCHLIST"];
$movieDescription = $movieDetails["MOVIE_OVERVIEW"];
$genres = $movieDetails["GENRES"];
$actors = $movieDetails["ACTORS"];
$director = $movieDetails["DIRECTOR_NAME"];

$reviewCount = $ratingOverview["REVIEW_COUNT"];

include "../includes/views/movie/movie-view.php";