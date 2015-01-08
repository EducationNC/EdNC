<?php

/**
 * Custom feed templates
 *
 */

// Register custom feeds
function ednc_custom_rss() {
  add_feed('ednews', 'feed_ednews');
  add_feed('features', 'feed_features');
  add_feed('recent', 'feed_recent');
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

// Function for Posts Since Yesterday feed
function feed_recent() {
  get_template_part('templates/feed', 'recent-posts');
}

// Modify author feeds to include any custom post type
function feedFilter($query) {
  if ($query->is_feed && $query->is_author) {
    $query->set('post_type', 'any');
  }
  return $query;
}
add_filter('pre_get_posts','feedFilter');
