<?php include __DIR__ .  '/../templates/header.php'; ?>

<main>
    <section class="extra-margin-top">
        <div class="search extra-margin-top">
            <div class="search-icon">
                <img src="<?php echo $baseURL ?>assets/images/icons/magnifying-glass-solid.svg" width="20" alt="magnifying glass">
            </div>
            <div class="search-text">
                <form action="<?php echo $baseURL ?>movies/search.php">
                    <input id="search-input" class="search-input" aria-label="search bar" type="text" name="q"
                           placeholder="Find moves by title, genre, or actor" autocomplete="off" autofocus
                    <?php echo $query ? "value='$query'" : ""?>>
                </form>
            </div>
            <div class="search-reset">
                <button class="clear-button" id="clear"><img src="<?php echo $baseURL ?>assets/images/icons/x.svg" width="20" alt="reset search"></button>
            </div>
        </div>
    </section>
    <section class="results-section">
        <?php if ($query): ?>
            <ul class="search-results-list">
                <?php createSearchResults($searchResults, $baseURL); ?>
            </ul>
         <?php endif ?>
    </section>
</main>


<script>
    const clearButton = document.getElementById('clear');
    const searchInput = document.getElementById('search-input');
    if (searchInput.value.length === 0)
        clearButton.style.display = 'none';
    searchInput.addEventListener('input', function() {
      if (searchInput.value.length > 0) {
        clearButton.style.display = 'block';
      } else {
        clearButton.style.display = 'none';
      }
    });

    clearButton.addEventListener('click', function() {
      searchInput.value = '';
      searchInput.focus();
      clearButton.style.display = 'none';
    });
    clearButton.addEventListener("click", function () {
        searchInput.value = "";
        searchInput.focus();
    });
    window.addEventListener('DOMContentLoaded', () => {
      searchInput.setSelectionRange(searchInput.value.length, searchInput.value.length);
    });
</script>
<?php include __DIR__ . '/../templates/footer.php'; ?>