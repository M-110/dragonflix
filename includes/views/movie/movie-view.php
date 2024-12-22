<?php include '../includes/views/templates/header.php'; ?>
<main>
    <section id="hero" style="background-image: url('https://www.themoviedb.org/t/p/w1920_and_h800_multi_faces/<?php echo $backdropURL ?>')">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="hero-title"><?php echo $movieTitle ?></div>
            <div class="hero-stats">
                <?php echo "$mainGenre • $year • $length"?>
                <img class="rating-icon" src="<?php echo $baseURL;?>assets/images/icons/<?php echo $rating ?>.svg" alt="<?php echo $rating ?>">
            </div>
            <div class="hero-buttons">
                <?php createMovieRentButton($movieId, $isRented, $isAvailable, $baseURL); ?>
                <?php createMovieWatchlistButton($movieId, $isInWatchList, $baseURL); ?>
            </div>
        </div>
    </section>

    <section class="details-section">
        <div class="movie-details">
            <p class="movie-description">
                <?php echo $movieDescription ?>
            </p>
            <?php createCrewDetails("Genres", $genres, $baseURL) ?>
            <?php createCrewDetails("Actors", $actors, $baseURL) ?>
            <?php createCrewDetails("Director", $director, $baseURL) ?>
        </div>
    </section>

    <section class="suggested-movies-sections">
        <?php createFilmRow("You May Also Like", $suggestedMovies, $baseURL) ?>
    </section>

    <section class="review-section">
        <h2 class="review-header">User Reviews (<?php echo $reviewCount ?>)</h2>
        <?php createRatingOverview($ratingOverview) ?>
        <?php createReviewsSection($movieReviews, $customerId, $userHasReviewed, $editReview,  $baseURL) ?>
    </section>
</main>
<?php include '../includes/views/templates/footer.php'; ?>