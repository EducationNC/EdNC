<?php while (have_posts()) : the_post();

if (has_post_format('video')) {
  get_template_part('templates/content', 'video');
} else {
  get_template_part('templates/content', 'post');
}

endwhile; ?>
