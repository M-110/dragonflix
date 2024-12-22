<?php include __DIR__ .  '/../templates/header.php'; ?>
<main>
    <section class="film-section extra-margin-top">
        <?php createFilmRow("My Watchlist", $watchList, $baseURL); ?>
    </section>
</main>
<?php include __DIR__ . '/../templates/footer.php'; ?>