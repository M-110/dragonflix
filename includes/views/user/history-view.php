<?php include __DIR__ .  '/../templates/header.php'; ?>

<main>
    <header class="rental-page-header">
        <h1 class="rental-page-heading">My Rental History</h1>
    </header>
    <section class="rental-section">
        <?php if (count($pastRentals) == 0) : ?>
            <div class="rental-empty">
                <h1 class="rental-empty-heading">You have no rental history.</h1>
            </div>
        <?php endif; ?>
        <?php createHistoryTable($pastRentals, $baseURL); ?>
    </section>
</main>

<?php include __DIR__ . '/../templates/footer.php'; ?>