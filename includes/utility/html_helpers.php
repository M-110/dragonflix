<?php

function createFilmRow(string $category, array $movies, string $baseURL): void
{
    echo <<<HTML
    <div class='film-row'>
        <h2 class='film-row-header'>$category</h2>
        <ul class='film-list'>
HTML;
    foreach ($movies as $movie) {
        $id = $movie['MOVIE_ID'];
        $posterURL = $movie['MOVIE_POSTER'];
        $title = $movie['MOVIE_TITLE'];
        echo <<<HTML
        <li class='film-item'>
            <a href='{$baseURL}movie/details.php?id=$id'>
                <img class='film-poster' src='https://www.themoviedb.org/t/p/w300/$posterURL' alt='$title'>
            </a>
        </li>
HTML;
    }
    echo "</ul>";
    echo "</div>";
}

function createRentalTable(array $rentals, string $baseURL, bool $isLate = false): void
{
    $rentalCount = count($rentals);
    $title = $isLate ? "Late Rentals ($rentalCount)" : "Rentals ($rentalCount)";
    $id = $isLate ? "late-rentals" : "rentals";
    if ($rentalCount == 0)
        return;

    echo <<<HTML
             <div id="$id" class="rental-category">
            <h1 class="rental-list-header">$title</h1>
            <ul class="rental-list">
HTML;
    foreach ($rentals as $rental)
    {
        $id = $rental['MOVIE_ID'];
        $title = $rental['MOVIE_TITLE'];
        $date = date("m/d/Y", strtotime($rental['RENTAL_DATE']));
        $dueDate = date("M d", strtotime($rental['DUE_DATE']));
        $days = $isLate ? $rental['DAYS_LATE'] . " Days Late" : $rental['DAYS_LEFT'] . " Days Left";
        $posterURL = "https://www.themoviedb.org/t/p/w300/{$rental['MOVIE_POSTER']}";
        echo <<<HTML
            <li class="rental-list-item">
                    <div class="rental-list-poster">
                        <a href="{$baseURL}movie/details.php?id=$id">
                            <img src="$posterURL" alt="$title">
                        </a>        
                    </div>
                    <div class="rental-list-item-content">
                        <div class="rental-list-item-action item-row">
                            <div><a href="{$baseURL}movie/details.php?id=$id">$title</a></div>
                            <div>$days</div>
                            <div><a href="{$baseURL}rental/return.php?id=$id"><button class="return-button">Return</button></a></div>
                        </div>
                        <div class="rental-list-item-info item-row">
                            <div>$date</div>
                            <div>Due: $dueDate</div>
                            <div></div>
                        </div>
                    </div>
                </li>
HTML;
    }
    echo "</ul>";
    echo "</div>";
}

function createHistoryTable(array $rentals, string $baseURL): void
{
    $rentalCount = count($rentals);
    $title = "Rental History ($rentalCount)";
    if ($rentalCount == 0)
        return;

    echo <<<HTML
             <div class="rental-category">
            <h1 class="rental-list-header">$title</h1>
            <ul class="rental-list">
HTML;
    foreach ($rentals as $rental)
    {
        $id = $rental['MOVIE_ID'];
        $title = $rental['MOVIE_TITLE'];
        $date = date("m/d/y", strtotime($rental['RENTAL_DATE']));
        $returnedDate = date("m/d/y", strtotime($rental['RENTAL_RETURNED']));
        $status = $rental['DAYS_LATE'] > 0 ? "Late" : "On Time";
        $posterURL = "https://www.themoviedb.org/t/p/w300/{$rental['MOVIE_POSTER']}";
        echo <<<HTML
            <li class="rental-list-item">
                    <div class="rental-list-poster">
                        <a href="{$baseURL}movie/details.php?id=$id">
                            <img src="$posterURL" alt="$title">
                        </a>        
                    </div>
                    <div class="rental-list-item-content">
                        <div class="rental-list-item-action item-row">
                            <div><a href="{$baseURL}movie/details.php?id=$id">$title</a></div>
                            <div>Returned Date</div>
                            <div>Status</div>
                        </div>
                        <div class="rental-list-item-info item-row">
                            <div>$date</div>
                            <div>$returnedDate</div>
                            <div>$status</div>
                        </div>
                    </div>
                </li>
HTML;
    }
    echo "</ul>";
    echo "</div>";
}

function createSearchResults(array $searchResults, string $baseURL): void
{
    foreach ($searchResults as $movie)
    {
        $id = $movie['MOVIE_ID'];
        $title = $movie['MOVIE_TITLE'];
        $posterURL = $movie['MOVIE_POSTER'];
        echo <<<HTML
           <li class="search-item">
               <a href="{$baseURL}movie/details.php?id=$id"><img src="https://www.themoviedb.org/t/p/w300/$posterURL" alt="$title"></a>
           </li>
HTML;

    }
}

function createMovieRentButton(int $movieId, int $isRented, int $isAvailable, string $baseURL): void
{
    $icon = '';
    $isRentable = $isRented === 0 && $isAvailable === 1;
    if ($isRented === 1) {
        $buttonClass = "hero-button-rented";
        $buttonText = "In Your Rentals";
    } else if ($isAvailable === 1) {
        $buttonClass = "";
        $buttonText = "Rent Now";
        $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-film play-icon" viewBox="0 0 16 16">
                     <path d="M0 1a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V1zm4 0v6h8V1H4zm8 8H4v6h8V9zM1 1v2h2V1H1zm2 3H1v2h2V4zM1 7v2h2V7H1zm2 3H1v2h2v-2zm-2 3v2h2v-2H1zM15 1h-2v2h2V1zm-2 3v2h2V4h-2zm2 3h-2v2h2V7zm-2 3v2h2v-2h-2zm2 3h-2v2h2v-2z"/>
                </svg>';
    } else {
        $buttonClass = "hero-button-unavailable";
        $buttonText = "Unavailable";
    }
    if ($isRentable)
        echo "<a href='{$baseURL}rental/rent.php?id=$movieId'>";
    echo "<button class='hero-button hero-button-rent $buttonClass'>$icon $buttonText</button>";
    if ($isRentable)
        echo "</a>";
}

function createMovieWatchlistButton(int $movieId, int $isInWatchList, string $baseURL): void
{

    if ($isInWatchList) {
        $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle watchlist-icon" viewBox="0 0 16 16">
                      
                      <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                    </svg>';
        $action = "removeFromWatchList";
        $tooltip = "Remove from Watchlist";
    } else {
        $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle watchlist-icon" viewBox="0 0 16 16">
                  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>';
        $action = "addToWatchList";
        $tooltip = "Add to Watchlist";
    }
    echo <<<HTML
        <form method="POST">
            <input type="hidden" name="$action">
            <button type="submit" class="watchlist-button">
                $icon
                <span class="watchlist-tooltip">$tooltip</span>
            </button>
        </form>
HTML;

}

function createCrewDetails(string $category, string $crew, string $baseURL): void
{
    echo '<p class="movie-crew">';
    echo "<span class='movie-description-category'>$category: </span>";
    $crewItems = explode(', ', $crew);
    $crewLinks = [];
    foreach ($crewItems as $item)
        $crewLinks[] = "<a href='{$baseURL}movies/search.php?q=$item'>$item</a>";
    $crewString = implode(', ', $crewLinks);
    echo $crewString;
    echo '</p>';
}

function createRatingOverview(array $ratingOverview): void
{
    $ratingColumns = ["FIVE_STAR_COUNT", "FOUR_STAR_COUNT", "THREE_STAR_COUNT", "TWO_STAR_COUNT", "ONE_STAR_COUNT"];
    $average = round($ratingOverview['AVERAGE_STARS'], 1);
    $stars = round($ratingOverview['AVERAGE_STARS']);
    $starsFilled = str_repeat("★", $stars);
    $starsEmpty = str_repeat("★", 5 - $stars);
    echo <<<HTML
        <div class="review-overview">
            <div class="review-overview-header">
                AVERAGE RATING
        </div>
            <div class="review-overview-content">
                <div class="review-average-column">
                    <div class="review-average-header">$average</div>
                    <div class="review-average-stars"><span class="review-star-filled">$starsFilled</span>$starsEmpty</div>
                    <div class="review-average-subheader">({$ratingOverview["RATING_COUNT"]} ratings)</div>
                </div>
                <div class="review-breakdown">
                    <ul class="review-breakdown-list">
HTML;

    foreach ($ratingColumns as $i => $column) {
        $i = 5 - $i;
        $starCount = $ratingOverview[$column];
        $percent = round($starCount / $ratingOverview['RATING_COUNT'] * 100);
        echo <<<HTML
            <li class="review-breakdown-item">
                <div class="review-breakdown-item-title">$i ★</div>
                <div class="review-breakdown-item-bar">
                    <div class="review-breakdown-item-bar-fill" style="width: $percent%"></div>
                </div>
            </li>
HTML;
    }

    echo <<<HTML
                    </ul>
                </div>
            </div>
        </div>
HTML;
}

function createReviewsSection(array $movieReviews, int $customerId, bool $userHasReviewed, bool $editReview,
                              string $baseURL): void
{
    echo "<ul class='review-list'>";
    foreach ($movieReviews as $review) {
        createReviewItem($review, $customerId, $editReview, $baseURL);
    }
    if (!$userHasReviewed) {
        createUserReviewForm($customerId);
    }
    echo "</ul>";
}

function createUserReviewForm(int $customerId)
{
    $starButtons = createStarRadioButtons(0);
    echo <<<HTML
            <li class="review-item">
                <form method="POST">
                    <div class="review-item-header">
                        <div class="review-item-author">Write your review</div>
                        <div class="review-item-edit-rating star-rating">
                            $starButtons
                        </div>
                    </div>
                    <textarea aria-label="review text" class="review-item-content-edit"
                    placeholder="Enter your review." rows="6" required
                    name="review-content"></textarea>
                    <div class="review-item-footer">
                    <div>
    
                    </div>
                        <div class="review-edit-buttons">
                            <input type="hidden" name="addReview">
                            <input type="hidden" name="anchor" value="#$customerId">
                            <button type="submit" class="checkout-button">Submit Review</button>
                        </div>
                    </div>
                </form>
            </li>
HTML;
}

function createReviewItem(array $review, int $customerId, bool $editReview, string $baseURL): void
{
    if ($customerId == $review['CUSTOMER_ID'] && $editReview) {
        createEditReviewForm($review, $baseURL);
        return;
    }
    $stars = str_repeat("★", $review['REVIEW_STARS']);
    $name = $review["CUSTOMER_FIRST_NAME"] . " " . $review["CUSTOMER_LAST_NAME"];
    $date = date("M d, Y", strtotime($review['REVIEW_DATE_ADDED']));
    $edit = $customerId == $review['CUSTOMER_ID'] ? createEditDeleteButtons($review, $customerId, $baseURL) : "";
    echo <<<HTML
            <li class="review-item" id="{$review['CUSTOMER_ID']}">
                <div class="review-item-header">
                    <div class="review-item-author">$name</div>
                    <div class="review-item-rating">$stars</div>
                </div>
                <div class="review-item-content">
                    {$review['REVIEW_CONTENT']}
                </div>
                <div class="review-item-footer">
                    <div class="review-item-date">$date</div>
                    <div class="review-item-options">
                        $edit
                    </div>
                </div>
            </li>
HTML;
}

function createEditReviewForm(array $review, string $baseURL): void
{
    $name = $review["CUSTOMER_FIRST_NAME"] . " " . $review["CUSTOMER_LAST_NAME"];
    $rating = $review['REVIEW_STARS'];
    $content = $review['REVIEW_CONTENT'];
    $id = $review['MOVIE_ID'];
    $date = date("M d, Y", strtotime($review['REVIEW_DATE_ADDED']));
    $starButtons = createStarRadioButtons($rating);
    echo <<<HTML
            <li class="review-item">
                <form action="{$baseURL}movie/details.php?id=$id" method="POST">
                    <div class="review-item-header">
                        <div class="review-item-author">$name</div>
                        <div class="review-item-edit-rating star-rating">
                        $starButtons
                        </div>
                    </div>
                    <textarea aria-label="review text" class="review-item-content-edit"
                    placeholder="Enter your review." autofocus rows="6" required
                    name="review-content">$content</textarea>
                    <div class="review-item-footer">
                    <div>
                        <div class="review-item-date">$date</div>
                    </div>
                        <div class="review-edit-buttons">
                            <input type="hidden" name="editReview">
                            <input type="hidden" name="anchor" value="#{$review['CUSTOMER_ID']}">
                            <button type="submit" class="checkout-button">Confirm Changes</button>
                            <a href="{$baseURL}movie/details.php?id=$id"><div class="checkout-button-cancel">Cancel</div></a>
                        </div>
                    </div>
                </form>
            </li>
HTML;
}

function createStarRadioButtons($rating): string
{
    $result = "";
    for ($i = 5; $i >= 1; $i--) {
        $checked = $i == $rating ? "checked" : "";
        $result .= <<<HTML
            <input type="radio" id="$i-star" name="rating" value="$i" class="star-input" $checked/>
            <label for="$i-star" class="star-label">★</label>
HTML;
    }
    return $result;
}

function createEditDeleteButtons(array $review, int $customerId, string $baseURL): string
{
    $id = $review['MOVIE_ID'];
    if ($customerId != $review['CUSTOMER_ID'])
        return "";
    return <<<HTML
            <div title="Edit Review" class="review-item-modify">
                <a href="{$baseURL}movie/details.php?id=$id&openEditReview=true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                      <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                      <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                    </svg>
                </a>
            </div>
            <form method="POST">
                <input type="hidden" name="deleteReview">
                <button title="Delete Review" class="review-item-modify" type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                      <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
                    </svg>
                </button>
            </form>
        </div>
HTML;
}

function createToastFeedback(string $status, string $message): void
{
    if ($status === "confirm")
        return;
    $successIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-check-circle toast-success-icon" viewBox="0 0 16 16">
                  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                  <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                </svg>';
    $failureIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-x-circle toast-failure-icon" viewBox="0 0 16 16">
                  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                  <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                </svg>';
    $iconVariableName = $status . "Icon";
    $icon = $$iconVariableName;
    echo <<<HTML
        <div class="toast toast-$status">
                $icon
            <div class="toast-message">$message</div>
        </div>
HTML;
}

function createCheckoutButtons(string $status, string $category, int $movieId, int $customerId, string $baseURL): void
{
    echo '<div class="checkout-buttons">';
    if ($status === "confirm" || $status == "failure") {
        $message = $category === "Rent" ? "Confirm Rental" : "Confirm Return";
        echo <<<HTML
        <form method="POST">
            <input type="hidden" name="movieId" value="$movieId">
            <input type="hidden" name="customerId" value="$customerId">
            <button type="submit" class="checkout-button">$message</button>
        </form>
        <a href="{$baseURL}movie/details.php?id=$movieId">
            <button class="checkout-button-cancel">Cancel</button>
        </a>
HTML;
    } else {
        echo <<<HTML
            <a href="{$baseURL}user/rentals.php">
                <button class="checkout-button-cancel">View My Rentals</button>
            </a>
HTML;
    }
    echo "</div>";
}
