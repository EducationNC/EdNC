<?php while (have_posts()) : the_post();
  $image_id = get_post_thumbnail_id();
  $featured_image_src = wp_get_attachment_image_src($image_id, 'full');
  $featured_image_lg = wp_get_attachment_image_src($image_id, 'large');
  $title_overlay = get_field('title_overlay');
  ?>

  <article <?php post_class('article'); ?>>
    <header class="entry-header hero-image">
      <div class="photo-overlay">
        <div class="parallax-img hidden-xs" style="background-image:url('<?php echo $featured_image_src[0]; ?>')"></div>
        <img class="visible-xs-block" src="<?php echo $featured_image_lg[0]; ?>" />
        <img class="title-image-overlay" src="<?php echo $title_overlay['url']; ?>" alt="<?php the_title(); ?>" />
        <h1 class="entry-title hidden"><?php the_title(); ?></h1>
      </div>
    </header>

    <div id="chapters" class="chapters container">
      <div class="row">
        <div class="col-md-8 col-centered">
          <ul class="nav"></ul>
        </div>
      </div>
    </div>

    <?php get_template_part('templates/layouts/content', 'page'); ?>

  </article>

  <?php get_template_part('templates/components/social-share'); ?>
<?php endwhile; ?>
