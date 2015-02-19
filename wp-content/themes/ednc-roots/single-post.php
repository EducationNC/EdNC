<?php while (have_posts()) : the_post();

if (has_post_format('video')) {
  get_template_part('templates/content', 'video');
} elseif (has_post_format('chat')) {    // This is actually the feature post format
  get_template_part('templates/content', 'feature');
} else {
  get_template_part('templates/content', 'post');
}

endwhile; ?>
