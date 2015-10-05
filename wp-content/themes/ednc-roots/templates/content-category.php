<?php
$term = get_queried_object();
$desc = category_description();
$cat_id = $term->term_id;
$term_image = $term->term_image;

if ($term_image) { ?>

  <div class="hentry">
    <header class="entry-header photo-overlay">
      <?php echo wp_get_attachment_image($term_image, 'full'); ?>
      <div class="article-title-overlay">
        <div class="container">
          <div class="row">
            <div class="col-md-9 col-centered">
              <h1 class="entry-title"><?php echo roots_title(); ?></h1>
            </div>
          </div>
        </div>
      </div>
    </header>

    <?php if ($desc) { ?>
      <div class="longform-intro">
        <div class="container">
          <div class="row">
            <div class="col-md-9 col-centered intro">
              <?php echo $desc ?>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>

<?php } else { ?>
  
  <?php if ($term->slug == 'powered-schools') { ?>
    <div class="column-banner <?php echo $term->slug; ?>">
      <div class="container">
        <div class="row">
          <div class="col-md-9 col-centered">
            <div class="column-name"></div>
          </div>
        </div>
      </div>
    </div>
  <?php } ?>

  <div class="container">
    <?php get_template_part('templates/page', 'header'); ?>
    <div class="row">
      <div class="col-lg-7 col-md-9 col-centered">

        <?php
        if ($desc) { ?>
          <div class="extra-bottom-margin">
            <?php echo $desc; ?>
          </div>
        <?php } ?>

      </div>
    </div>
  </div>

<?php } ?>

<div class="container">
  <div class="row">
    <div class="col-lg-7 col-md-9 col-centered">
      <div class="row hentry issue-flash-card">

        <?php
        $args = array(
          'post_type' => 'flash-cards',
          'posts_per_page' => -1,
          'cat' => $cat_id
        );

        $fc = new WP_Query($args);

        if ($fc->have_posts()) : while ($fc->have_posts()): $fc->the_post(); ?>

          <div class="col-sm-6 col-xs-9 col-centered has-photo-overlay">
            <div class="photo-overlay">
              <?php the_post_thumbnail('featured-thumbnail'); ?>
              <a class="mega-link" href="<?php the_permalink(); ?>"></a>
              <h3 class="post-title"><?php the_title(); ?></h3>
              <div class="line"></div>
            </div>
          </div>

        <?php endwhile; endif; wp_reset_query(); ?>

      </div>

      <?php if (!have_posts()) : ?>
        <div class="alert alert-warning">
          <?php _e('Sorry, no results were found.', 'roots'); ?>
        </div>
        <?php get_search_form(); ?>
      <?php endif; ?>

      <?php while (have_posts()) : the_post(); ?>
        <?php get_template_part('templates/content', 'excerpt'); ?>
      <?php endwhile; ?>

      <?php if ($wp_query->max_num_pages > 1) : ?>
        <nav class="post-nav">
          <?php wp_pagenavi(); ?>
        </nav>
      <?php endif; ?>
    </div>
  </div>
</div>
