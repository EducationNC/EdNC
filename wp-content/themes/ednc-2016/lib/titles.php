<?php

namespace Roots\Sage\Titles;

/**
 * Page titles
 */
function title() {
  if (is_home()) {
    if (get_option('page_for_posts', true)) {
      return get_the_title(get_option('page_for_posts', true));
    } else {
      return __('Latest Posts', 'sage');
    }
  } elseif (is_tax('session') || is_tax('bill-type')) {
    return '<a href="/legislation-tracker/">Legislation Tracker</a>: ' . get_the_archive_title();
  } elseif (is_archive()) {
    return get_the_archive_title();
  } elseif (is_search()) {
    return sprintf(__('Search results for %s', 'sage'), get_search_query());
  } elseif (is_404()) {
    return __('Not Found', 'sage');
  } else {
    return get_the_title();
  }
}

/**
 * Remove prefixes from some titles
 */
add_filter( 'get_the_archive_title', function ($title) {
  if ( is_category() ) {
     $title = single_cat_title( '', false );
  }
  if ( is_tax() ) {
    $title = single_term_title( '', false );
  }
  if ( is_author() ) {
    $title = get_the_author();
  }
  if ( is_post_type_archive('tribe_events') ) {
    $title = 'Events';
  }
  return $title;
});
