<?php include __DIR__ .  '/../templates/header.php'; ?>

<main>
    <header class="rental-page-header">
        <h1 class="rental-page-heading">My Rentals</h1>
    </header>
    <section class="rental-section">
        <?php if (count($currentRentals) == 0 && count($lateRentals) == 0) : ?>
            <div class="rental-empty">
                <h1 class="rental-empty-heading">You have no rentals.</h1>
            </div>
        <?php endif; ?>
        <?php createRentalTable($lateRentals, $baseURL, true); ?>
        <?php createRentalTable($currentRentals, $baseURL); ?>
    </section>
</main>

<?php include __DIR__ . '/../templates/footer.php'; ?>