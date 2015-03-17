<?php
$column = wp_get_post_terms(get_the_id(), 'column');
?>

<article <?php post_class('article'); ?>>
  <?php
  if ($column) {
    ?>
    <div class="column-banner <?php echo $column[0]->slug; ?>">
      <div class="container">
        <div class="row">
          <div class="col-md-9 col-centered">
            <div class="column-name"><?php echo $column[0]->name; ?></div>
          </div>
        </div>
      </div>
    </div>
    <?php
  }
  ?>
  <header class="entry-header container">
    <div class="row">
      <div class="col-lg-7 col-md-9 col-centered">
        <h1 class="entry-title"><?php the_title(); ?></h1>
        <?php get_template_part('templates/entry-meta'); ?>
        <?php get_template_part('templates/social', 'share'); ?>
      </div>
    </div>
  </header>

  <div class="entry-content container">
    <div class="row">
      <div class="col-lg-7 col-md-9 col-centered">
        <?php the_content(); ?>

        <?php get_template_part('templates/social', 'share'); ?>
      </div>
    </div>
  </div>

  <footer class="container">
  </footer>
</article>
