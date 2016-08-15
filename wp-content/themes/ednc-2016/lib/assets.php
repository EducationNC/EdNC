<?php

namespace Roots\Sage\Assets;

/**
 * Get paths for assets
 */
class JsonManifest {
  private $manifest;

  public function __construct($manifest_path) {
    if (file_exists($manifest_path)) {
      $this->manifest = json_decode(file_get_contents($manifest_path), true);
    } else {
      $this->manifest = [];
    }
  }

  public function get() {
    return $this->manifest;
  }

  public function getPath($key = '', $default = null) {
    $collection = $this->manifest;
    if (is_null($key)) {
      return $collection;
    }
    if (isset($collection[$key])) {
      return $collection[$key];
    }
    foreach (explode('.', $key) as $segment) {
      if (!isset($collection[$segment])) {
        return $default;
      } else {
        $collection = $collection[$segment];
      }
    }
    return $collection;
  }
}

function asset_path($filename) {
  $dist_path = get_template_directory_uri() . '/dist/';
  $directory = dirname($filename) . '/';
  $file = basename($filename);
  static $manifest;

  if (empty($manifest)) {
    $manifest_path = get_template_directory() . '/dist/' . 'assets.json';
    $manifest = new JsonManifest($manifest_path);
  }

  if (array_key_exists($file, $manifest->get())) {
    return $dist_path . $directory . $manifest->get()[$file];
  } else {
    return $dist_path . $directory . $file;
  }
}

// add async and defer to javascripts
// http://wpcodesnippet.com/add-async-and-defer-attributes-javascript-elements/
function defer_javascripts ( $url ) {
  if ( is_admin() ) return $url;
  if ( FALSE === strpos( $url, '.js' ) ) return $url;
  if ( strpos( $url, 'jquery.js' ) || strpos( $url, 'charts/loader.js' ) ) return $url;
  if ( is_single() && strpos( $url, 'ednc-2016') ) return $url;
  if ( is_page('story-2015-16') ) return $url;
  if ( strpos( $url, 'mediaelement' ) ) return $url;
  return "$url' async='async";
}
add_filter( 'clean_url', __NAMESPACE__ . '\\defer_javascripts', 11, 1 );
