<footer>
    <a href="index.php" class="logo"><img src="assets/img/logo_dark.png" alt="logo"></a>
    <div class="footer-icons">
        <a href="#"><i class="icon fab fa-instagram text-dark"></i></a>
        <a href="#"><i class="icon fab fa-facebook-square text-dark"></i></a>
        <a href="#"><i class="icon fab fa-twitter-square text-dark"></i></a>
    </div>
</footer>

<!-- Messenger Chat Plugin Code -->
<div id="fb-root"></div>
      <script>
        window.fbAsyncInit = function() {
          FB.init({
            xfbml            : true,
            version          : 'v10.0'
          });
        };

        (function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
      </script>

      <!-- Your Chat Plugin code -->
      <div class="fb-customerchat"
        attribution="page_inbox"
        page_id="109473187963420">
      </div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="assets/js/main.js"></script>
</body>

</html>