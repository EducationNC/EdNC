<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the .container div and all content after
 *
 * @package EducationNC
 */
?>
          </div><!-- .content -->

          <footer class="footer">
            <div class="above-footer">
              <div class="row">
                <div class="medium-3 columns">
                  <img class="logo-dark" src="<?php echo get_template_directory_uri(); ?>/assets/public/imgs/logo-white.svg" alt="EducationNC" />
                </div>

                <div class="medium-9 columns">
                  Sponsors logos
                </div>
              </div>
            </div>
            <div class="row">
              <div class="medium-3 columns">
                <nav>
                  <ul>
                    <li><a href="#">Research</a></li>
                    <li><a href="#">Data</a></li>
                    <li><a href="#">Districts</a></li>
                    <li><a href="#">Voices</a></li>
                  </ul>
                </nav>
              </div>

              <div class="medium-3 columns">
                <h4>Stay Connected</h4>
                <ul>
                  <li><a href="#">Facebook</a></li>
                  <li><a href="#">Twitter</a></li>
                  <li><a href="#">Instagram</a></li>
                  <li><a href="#">YouTube</a></li>
                  <li><a href="#">Google+</a></li>
                  <li><a href="#">LinkedIn</a></li>
                </ul>
                <a href="#">Email Digests &amp; Alerts</a>
              </div>

              <div class="medium-3 columns">
                <h4>Support</h4>
                <ul>
                  <li><a href="#">Donations</a></li>
                  <li><a href="#">Underwriting Opportunities</a></li>
                </ul>
              </div>

              <div class="medium-3 columns">
                <h4>EducationNC</h4>
                <ul>
                  <li><a href="#">About Us</a></li>
                  <li><a href="#">Board of Directors</a></li>
                  <li><a href="#">Supporters</a></li>
                  <li><a href="#">Contact Us</a></li>
                </ul>
              </div>
            </div>

            <div class="below-footer row">
              <p class="text-center small">
                &copy; <?php echo date('Y'); ?> EducationNC. All rights reserved.<br />
                <a href="#">Terms of Service</a> | <a href="#">Privacy Policy</a>
              </p>
            </div>
          </footer>

        </div><!-- .scroller-inner -->
      </div><!-- .scroller -->
    </div><!-- .oc-pusher -->
  </div><!-- .container -->
<?php wp_footer(); ?>

</body>
</html>
