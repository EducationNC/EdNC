<?php

/**
 * Custom feed templates
 *
 */

// Register custom feeds
function ednc_custom_rss() {
  add_feed('ednews', 'feed_ednews');
  add_feed('features', 'feed_features');
}
add_action('init', 'ednc_custom_rss');

// Function for EdNews feed
function feed_ednews() {
  get_template_part('templates/feed', 'ednews');
}

// Function for Featured stories feed
function feed_features() {
  get_template_part('templates/feed', 'features');
}
