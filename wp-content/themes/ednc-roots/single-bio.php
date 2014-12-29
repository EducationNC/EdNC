<?php while (have_posts()) : the_post(); ?>
  <div class="row">
    <div class="col-md-4">
      <?php the_post_thumbnail('full'); ?>
    </div>
    <article <?php post_class('col-md-8'); ?>>
      <header class="entry-header">
        <h1 class="entry-title no-top-margin"><?php the_title(); ?></h1>
        <h2><?php the_field('title'); ?></h2>
        <h4><?php the_field('tagline'); ?></h4>
        <?php
        $twitter = get_field('twitter');
        if ($twitter) {
          echo '<p><span class="big icon-twitter"></span><a href="http://twitter.com/' . $twitter . '" target="_blank">@' . $twitter . '</a></p>';
        }
        ?>
        <?php
        $website = get_field('website');
        if ($website) {
          echo '<p><a href="' . $website . '" target="_blank">' . $website . '</a></p>';
        }
        ?>
      </header>
      <div class="entry-content">
        <?php the_content(); ?>
      </div>
      <footer>
        <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
      </footer>
    </article>
  </div>
<?php endwhile; ?>