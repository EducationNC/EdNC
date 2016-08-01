<?php

namespace Roots\Sage\Widgets;

// Include new widget classes
$includes = [
  'lib/widgets/columns.php',
  'lib/widgets/editors-picks.php',
  'lib/widgets/events.php',
  'lib/widgets/features-recent-news.php',
  'lib/widgets/perspectives.php',
  'lib/widgets/press-releases.php',
  'lib/widgets/social-media.php',
  'lib/widgets/spotlight.php'
];

foreach ($includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'sage'), $file), E_USER_ERROR);
  }

  require_once $filepath;
}
unset($file, $filepath);

// Register widgets
add_action( 'widgets_init', function(){
  register_widget( __NAMESPACE__ . '\\EdNC_Columns' );
  register_widget( __NAMESPACE__ . '\\Editors_Picks' );
  register_widget( __NAMESPACE__ . '\\Events' );
  register_widget( __NAMESPACE__ . '\\Features_Recent_News' );
  register_widget( __NAMESPACE__ . '\\Perspectives' );
  register_widget( __NAMESPACE__ . '\\Press_Releases' );
  register_widget( __NAMESPACE__ . '\\Social_Media' );
  register_widget( __NAMESPACE__ . '\\Spotlight' );
});
