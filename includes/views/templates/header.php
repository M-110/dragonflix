<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle ?></title>
    <link rel="stylesheet" href="<?php echo $baseURL; ?>css/styles.css">
</head>
<body>
<nav id="navbar">
    <div class="navbar-background"></div>
    <div class="navbar-brand">
        <a href="<?php echo $baseURL; ?>index.php">
            <img src="<?php echo $baseURL; ?>assets/images/logo.png" alt="Dragonflix" width="150">
        </a>
    </div>
    <ul class="nav-list">
        <li class="nav-item"><a class="<?php echo $currentPage === 'index.php' ? 'active' : '' ?> nav-link" href="<?php echo $baseURL; ?>index.php">Home</a></li>
        <li class="nav-item"><a class="<?php echo $currentPage === 'search.php' ? 'active' : '' ?> nav-link" href="<?php echo $baseURL; ?>movies/search.php">Search</a></li>
        <li class="nav-item"><a class="<?php echo $currentPage === 'watchlist.php' ? 'active' : '' ?> nav-link" href="<?php echo $baseURL; ?>user/watchlist.php">Watchlist</a></li>
        <li class="nav-item"><a class="<?php echo $currentPage === 'rentals.php' ? 'active' : '' ?> nav-link" href="<?php echo $baseURL; ?>user/rentals.php">Current Rentals</a></li>
        <li class="nav-item"><a class="<?php echo $currentPage === 'history.php' ? 'active' : '' ?> nav-link" href="<?php echo $baseURL; ?>user/history.php">History</a></li>
    </ul>
    <div class="nav-profile">
        <a href="<?php echo $baseURL; ?>user/profile.php">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="currentColor" class="bi bi-person-circle profile-icon" viewBox="0 0 16 16">
              <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
              <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
            </svg>
            Profile
        </a>
    </div>
</nav>