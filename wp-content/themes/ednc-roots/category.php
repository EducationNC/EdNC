<?php
if (is_category('stem')) {
  get_template_part('templates/content', 'stem');
} else {
  get_template_part('templates/content', 'category');
}
?>
