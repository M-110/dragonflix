<?php $pageTitle = "Rent " . $title ?>
<?php include '../includes/views/templates/header.php'; ?>
<main>
    <section class="extra-margin-top">
    </section>
    <section class="checkout-section">
        <div class="checkout-container">
            <div class="checkout-title">
                Return <?php echo $title ?>
            </div>
            <?php createToastFeedback($status, $message) ?>
            <div class="checkout-body">
                <div class="checkout-poster">
                    <img class="checkout-poster-img" src="https://www.themoviedb.org/t/p/w300/<?php echo $posterURL ?>"
                         alt="<?php echo $title ?>">
                </div>
                <div class="checkout-info">
                    <div class="checkout-info-row">
                        <div class="checkout-info-label">Movie:</div>
                        <div class="checkout-info-value"><?php echo $title ?></div>
                    </div>
                    <div class="checkout-info-row">
                        <div class="checkout-info-label">Rental Date:</div>
                        <div class="checkout-info-value"><?php echo $rentalDate ?></div>
                    </div>
                    <div class="checkout-info-row">
                        <div class="checkout-info-label">Due Date:</div>
                        <div class="checkout-info-value"><?php echo $dueDate ?></div>
                    </div>
                    <div class="checkout-info-row">
                        <div class="checkout-info-label">Date Returned:</div>
                        <div class="checkout-info-value"><?php echo $date ?></div>
                    </div>
                    <div class="checkout-info-row">
                        <div class="checkout-info-label">Late Fee:</div>
                        <div class="checkout-info-value">$<?php echo $lateFee?>.00</div>
                    </div>
                </div>
            </div>
            <?php createCheckoutButtons($status, $category, $movieId, $customerId, $baseURL) ?>
        </div>
   </section>
</main>

<?php include '../includes/views/templates/footer.php'; ?>