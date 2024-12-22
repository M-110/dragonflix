<?php include __DIR__ .  '/../templates/header.php'; ?>

<main>
    <header class="profile-page-header extra-margin-top">
        <h1 class="profile-page-heading">My Account Details</h1>
    </header>
    <section class="profile-page-section">
        <div class="profile-container">
            <ul class="profile-info-list">
                <li class="profile-info-item">
                    <div class="profile-info-label">
                        Email Address
                    </div>
                    <div class="profile-info-content">
                        <?php echo $email ?>
                    </div>
                </li>
                <li class="profile-info-item">
                    <div class="profile-info-label">
                        Name
                    </div>
                    <div class="profile-info-content">
                        <?php echo $name ?>
                    </div>
                </li>
                <li class="profile-info-item">
                    <div class="profile-info-label">
                        Phone Number
                    </div>
                    <div class="profile-info-content">
                        <?php echo $phone ?>
                    </div>
                </li>
                <li class="profile-info-item">
                    <div class="profile-info-label">
                        Customer ID
                    </div>
                    <div class="profile-info-content">
                        <?php echo $customerId ?>
                    </div>
                </li>
            </ul>
        </div>
        <a href="<?php echo $baseURL . "user/switch.php"?>">
            <button class="change-button">Switch User</button>
        </a>
    </section>
</main>

<?php include __DIR__ . '/../templates/footer.php'; ?>