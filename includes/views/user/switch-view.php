<?php include __DIR__ .  '/../templates/header.php'; ?>

<main>
    <header class="profile-page-header extra-margin-top">
        <h1 class="profile-page-heading">Switch User</h1>
    </header>
    <section class="profile-page-section">
        <div class="profile-container">
            <div class="profile-user-list-header">
                Select a user to switch to:
            </div>
                <ul class="profile-user-list">
                    <?php foreach ($customers as $customer): ?>
                        <li class="profile-user-item <?php echo ($customer['CUSTOMER_ID'] == $customerId) ? 'selected' : ''?>">
                            <div class="profile-user-id"> <?php echo $customer["CUSTOMER_ID"] ?></div>
                            <div class="profile-user-name">
                                <?php echo $customer["CUSTOMER_EMAIL"] ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
        <div class="profile-buttons">
                 <form method="POST">
                    <input type="hidden" name="customerId" value="1">
                    <button type="submit" class="checkout-button">Switch User</button>
                </form>
                <a href="profile.php"><button class="checkout-button-cancel">Cancel</button></a>
        </div>
        </div>
    </section>
</main>

<script>
    const hiddenInput = document.querySelector("input[name='customerId']");
    hiddenInput.value = <?php echo $customerId ?>;

    document.addEventListener("DOMContentLoaded", function() {
        let profileUserItems = document.querySelectorAll(".profile-user-item");
        profileUserItems.forEach((item) => {
            item.addEventListener("click", () => {
                profileUserItems.forEach((item) => {
                    item.classList.remove("selected");
                });
                hiddenInput.value = item.querySelector(".profile-user-id").innerHTML;
                item.classList.add("selected");
            });
        });
    });
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>