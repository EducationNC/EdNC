<?php while (have_posts()) : the_post(); ?>
  <div class="row">
    <div class="col-md-4">
      <?php the_post_thumbnail('bio-headshot'); ?>
      <h3>Links</h3>
      <?php
      $user = get_field('user');
      ?>
      <p><a class="btn btn-default" href="<?php echo get_author_posts_url($user['ID']); ?>">See posts by <?php the_title(); ?></a></p>
      <?php
      $extras = get_field('author_extras');
      if ($extras) {
        foreach ($extras as $e) {
          if ($e['acf_fc_layout'] == 'file') {
            echo '<p><a class="btn btn-default" href="' . $e['file']['url'] . '" target="_blank">';
            echo $e['link_text'];
            echo '</a></p>';
          } elseif ($e['acf_fc_layout'] == 'link') {
            echo '<p><a class="btn btn-default" href="' . $e['url'] . '" target="_blank">';
            echo $e['link_text'];
            echo '</a></p>';
          }
        }
      }
      ?>
    </div>
    <article <?php post_class('col-md-8'); ?>>
      <header class="entry-header">
        <h1 class="entry-title no-top-margin"><?php the_title(); ?></h1>
        <h2><?php the_field('title'); ?></h2>
        <h4><?php the_field('tagline'); ?></h4>
        <?php
        $email = get_field('email');
        if ($email) {
          echo '<p><span class="big icon-email"></span> <a href="mailto:' . antispambot($email) . '" target="_blank">' . antispambot($email) . '</a></p>';
        }
        ?>
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
