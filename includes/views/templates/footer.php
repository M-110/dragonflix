<footer class="movie-footer">
    <div class="social-links">
        <img src="<?php echo $baseURL ?>assets/images/icons/facebook.svg">
        <img src="<?php echo $baseURL ?>assets/images/icons/instagram.svg">
        <img src="<?php echo $baseURL ?>assets/images/icons/twitter.svg">
        <img src="<?php echo $baseURL ?>assets/images/icons/youtube.svg">
    </div>
    <h3 class="footer-header">Sources</h3>
    <ul class="footer-source">
        <li class="footer-source-link"><a href="#">Movie Data from TMDB</a></li>
        <li class="footer-source-link"><a href="#">Movie Images from TMDB</a></li>
        <li class="footer-source-link"><a href="#">MPAA Ratings Icons from Apple</a></li>
        <li class="footer-source-link"><a href="#">Various Icons from Font Awesome</a></li>
        <li class="footer-source-link"><a href="#">Fonts from HBO</a></li>
        <li class="footer-source-link"><a href="#">Gradients generated with ColorGradient</a></li>
        <li class="footer-source-link"><a href="#">Various Icons from Bootstrap</a></li>
        <li class="footer-source-link"><a href="#">Fonts from Google Fonts</a></li>
    </ul>
    <div class="footer-copyright">© 2023 Dragonflix, LLC. All rights reserved.</div>

</footer>
<script>
  // Get the header element
let header = document.getElementById('navbar');

// Listen for scroll events on the window
window.addEventListener('scroll', function() {
    // todo: calculate scrollY to correspond with hero.
    if (window.scrollY > 250) {
        header.classList.add('scrolled');
        console.log("scrolled");
    } else {
        console.log("not scrolled");
        header.classList.remove('scrolled');
    }
});
</script>
</body>
</html>