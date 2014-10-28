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
              <ul class="inline-list text-center">
                <li><img src="<?php echo get_template_directory_uri(); ?>/assets/public/imgs/zsrf-gray-transparent.png" width="153" alt="Z. Smith Reynolds Foundation" />
                <li><img src="<?php echo get_template_directory_uri(); ?>/assets/public/imgs/best-nc-gray-transparent.png" width="78" alt="Best of NC" />
                <li><img src="<?php echo get_template_directory_uri(); ?>/assets/public/imgs/bcbsnclogo-gray-transparent.png" width="209" alt="Blue Cross Blue Shield North Carolina" />
                <li><img src="<?php echo get_template_directory_uri(); ?>/assets/public/imgs/iac-gray-transparent.png" width="239" alt="International Affairs Council" />
              </ul>
            </div>

            <div class="row">
              <div class="medium-3 columns">
                <h4>EdNC.org</h4>
                <ul>
                  <li><a href="#">Home</a></li>
                  <li><a href="#">Issues</a></li>
                  <li><a href="#">Data</a></li>
                  <li><a href="#">Districts</a></li>
                  <li><a href="#">Voices</a></li>
                </ul>
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
                  <li>&nbsp;</li>
                  <li><a href="#">Email Digests &amp; Alerts</a></li>
                </ul>
              </div>

              <div class="medium-3 columns">
                <h4>Support us</h4>
                <ul>
                  <li><a href="#">Make a donation</a></li>
                  <li><a href="#">Sponsorship opportunities</a></li>
                </ul>
              </div>

              <div class="medium-3 columns">
                <h4>EducationNC</h4>
                <ul>
                  <li><a href="#">About us</a></li>
                  <li><a href="#">Board of Directors</a></li>
                  <li><a href="#">Supporters</a></li>
                  <li><a href="#">Contact us</a></li>
                </ul>
              </div>
            </div>

            <div class="below-footer">
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
