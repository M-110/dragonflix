<?php include 'templates/header.php'; ?>
<main>
    <section id="hero" style='background-image: url("<?php echo $baseURL . 'assets/images/background3.jpg'?>")'>
        <div class="hero-content">
            <div class="hero-title"><img class="movie-logo" src="<?php echo $baseURL;?>assets/images/soul.png" alt="Soul Movie Logo"></div>
            <div class="hero-stats">
                Family • 1 hr 36 min  <img class="rating-icon" src="<?php echo $baseURL;?>assets/images/icons/PG.svg" alt="PG">
            </div>
            <div class="hero-description">
                After landing the gig of a lifetime, a New York jazz pianist suddenly
                finds himself trapped in a strange land between Earth and the afterlife..
            </div>
            <div class="hero-buttons">
                <?php createMovieRentButton(37, 0, 1, $baseURL) ?>
<!--                <button class="hero-button hero-button-rent"><img class="play-icon" src="--><?php //echo $baseURL;?><!--assets/images/icons/play.svg" alt="play"> Rent</button>-->
                <a href="<?php echo "{$baseURL}movie/details.php?id=37"?>">
                    <button class="hero-button hero-button-details"><img class="info-icon" src="<?php echo $baseURL;?>assets/images/icons/info.svg" alt="more info"> Movie Details</button>
                </a>
            </div>
        </div>
    </section>
    <section class="film-section">
        <?php createFilmRow("Featured", $featuredMovies, $baseURL); ?>
        <?php createFilmRow("Your Watchlist", $watchList, $baseURL); ?>
        <?php createFilmRow("Popular Movies", $popularMovies, $baseURL); ?>
        <?php createFilmRow("Drama", $dramaMovies, $baseURL); ?>
        <?php createFilmRow("Action", $actionMovies, $baseURL); ?>
        <?php createFilmRow("Comedy", $comedyMovies, $baseURL); ?>
        <?php createFilmRow("Animation", $animationMovies, $baseURL); ?>
    </section>
</main>
<?php include 'templates/footer.php'; ?>