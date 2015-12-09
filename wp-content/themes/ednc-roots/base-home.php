<?php get_template_part('templates/head'); ?>
<body <?php body_class(); ?>>
  <?php get_template_part('templates/facebook-sdk'); ?>

  <div class="wrapper">
    <div id="oc-pusher" class="oc-pusher">

      <!--[if lt IE 9]>
        <div class="alert alert-warning">
          <?php _e('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'roots'); ?>
        </div>
      <![endif]-->

      <div class="inner-wrapper">

        <?php
          do_action('get_header');
          get_template_part('templates/header-mobile');
          get_template_part('templates/header');
        ?>

        <div class="wrap" role="document">
          <div class="content clearfix">
            <main class="main" role="main">
              <?php include roots_template_path(); ?>
            </main><!-- /.main -->
            <?php if (roots_display_sidebar()) : ?>
              <aside class="sidebar" role="complementary">
                <?php include roots_sidebar_path(); ?>
              </aside><!-- /.sidebar -->
            <?php endif; ?>
          </div><!-- /.content -->
        </div><!-- /.wrap -->

        <?php get_template_part('templates/footer'); ?>

      </div>
    </div>
  </div>

  <?php wp_footer(); ?>

</body>
</html>
