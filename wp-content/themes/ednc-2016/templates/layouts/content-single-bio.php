<?php

$twitter = get_field('twitter');
$email = get_field('email');
$website = get_field('website');

?>

<?php while (have_posts()) : the_post(); ?>
  <div <?php post_class('container'); ?>>
    <div class="page-header">
      <div class="row">
        <div class="col-md-12">
          <h1 class="entry-title"><?php the_title(); ?></h1>
          <?php
          $title = get_field('title');
          if ($title) {
            ?>
            <h2><?php echo $title; ?></h2>
            <?php
          }
          ?>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-9 col-lg-8">
        <div class="entry-content">
          <h3><?php the_field('tagline'); ?></h3>
          <?php the_content(); ?>
        </div>
      </div>

      <div class="col-md-3 col-lg-push-1 meta">
        <?php the_post_thumbnail('bio-headshot'); ?>
        <?php
        if ($email) {
          echo '<div class="nowrap overflow-ellipsis"><span class="big icon-email"></span> <a href="mailto:' . antispambot($email) . '" target="_blank">' . antispambot($email) . '</a></div>';
        }

        if ($twitter) {
          echo '<div class="nowrap overflow-ellipsis"><span class="big icon-twitter"></span> <a href="http://twitter.com/' . $twitter . '" target="_blank">@' . $twitter . '</a></div>';
        }

        if ($website) {
          echo '<div class="nowrap overflow-ellipsis"><span class="big icon-website"></span> <a href="' . $website . '" target="_blank">Website</a></div>';
        }
        ?>
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
    </div>
  </div>
<?php endwhile; ?>
