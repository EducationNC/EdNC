<?php
/*
Copyright 2013 Google Inc. All Rights Reserved.

This file is part of the AdSense Plugin.

The AdSense Plugin is free software:
you can redistribute it and/or modify it under the terms of the
GNU General Public License as published by the Free Software Foundation,
either version 2 of the License, or (at your option) any later version.

The AdSense Plugin is distributed in the hope that it
will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty
of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General
Public License for more details.

You should have received a copy of the GNU General Public License
along with the AdSense Plugin.
If not, see <http://www.gnu.org/licenses/>.
*/

if(!defined('ABSPATH')) {
  exit;
}

require_once 'ClassAutoloader.php';

class GooglePublisherPluginTags {

  // Data attributes to be placed in a metatag describing the plugin and page
  // details.
  const VERSION_DATA_ATTRIBUTE = 'data-pso-pv';
  const PAGE_TYPE_DATA_ATTRIBUTE = 'data-pso-pt';
  const THEME_HASH_DATA_ATTRIBUTE = 'data-pso-th';
  const EXCLUDE_ADS_DATA_ATTRIBUTE = 'data-pso-ea';

  private $configuration;
  private $current_page_type;
  private $exclude_ads;
  private $theme_hash;

  /**
   * @param Configuration $configuration The configuration object to use
   *        (required).
   * @param boolean $preview_mode True if in preview mode.
   */
  public function __construct($configuration, $preview_mode) {
    $this->configuration = $configuration;

    // Note we can't compute the theme hash here, since this is executed before
    // before plugins like WP touch have had a chance to change the theme.
    $this->theme_hash = '';

    // To determine the current page type, WordPress needs to have
    // initialized wp_query. The template_redirect hook is the first
    // action hook after that initialization.
    add_action('template_redirect', array($this,
        'determineCurrentPageDetails'));
    add_action('wp_head', array($this, 'printMetaTag'), PHP_INT_MAX);

    if (!$preview_mode) {
      add_action('wp_head', array($this, 'wpHead'), PHP_INT_MAX);
      add_filter('the_content', array($this, 'wpRepeating'), PHP_INT_MAX, 1);
      add_filter('the_excerpt', array($this, 'wpRepeating'), PHP_INT_MAX, 1);
      add_action('wp_footer', array($this, 'wpFooter'), ~PHP_INT_MAX);
    }
  }

  /**
   * Prints the page details in a meta tag on the header. This is expected to be
   * called from the wp_head action hook.
   */
  public function printMetaTag() {
    echo '<meta';
    printf(' %s="%s"', self::VERSION_DATA_ATTRIBUTE,
        GooglePublisherPlugin::PLUGIN_VERSION);
    printf(' %s="%s"', self::PAGE_TYPE_DATA_ATTRIBUTE,
        htmlspecialchars($this->current_page_type));
    printf(' %s="%s"', self::THEME_HASH_DATA_ATTRIBUTE, $this->getThemeHash());
    if ($this->exclude_ads) {
      printf(' %s="true"', self::EXCLUDE_ADS_DATA_ATTRIBUTE);
    }
    echo '>';
  }

  /**
   * Computes the md5 digest hash of the current active theme directory and the
   * site id.  NOTE we don't use get_template here because we can't use that to
   * detect when WP Touch v3.1.5 is active.
   */
  public function computeThemeHash() {
    return md5(
        parse_url(get_bloginfo('template_directory'), PHP_URL_PATH) . '#' .
        $this->configuration->getSiteId());
  }

  /**
   * Inserts tags into the <head> section. Expected to be called on the wp_head
   * action hook.
   */
  public function wpHead() {
    if ($this->exclude_ads) {
      return;
    }
    // Inserts a js script tag which don't need escaping.
    echo $this->configuration->getTag(
        $this->current_page_type, 'head', $this->getThemeHash());
  }

  /**
   * Inserts the repeating tag before the content of every post and excerpt.
   * Executed as a filter on the_content and the_excerpt.
   *
   * @return string The given $content prefixed with the repeating tag for the
   *         current configuration.
   */
  public function wpRepeating($content) {
    if ($this->exclude_ads) {
      return $content;
    }
    $repeatingTag = $this->configuration->getTag(
        $this->current_page_type, 'repeating', $this->getThemeHash());

    return $repeatingTag . $content;
  }

  /**
   * Inserts tags at the end of the <body> section. Expected to be called on the
   * wp_footer action hook.
   */
  public function wpFooter() {
    if ($this->exclude_ads) {
      return;
    }
    // Inserts js script tags which don't need escaping.
    echo $this->configuration->getTag(
        $this->current_page_type, 'repeating', $this->getThemeHash());
    echo $this->configuration->getTag(
        $this->current_page_type, 'bodyEnd', $this->getThemeHash());
  }

  /**
   * Determines the current page details. This should be called after WordPress
   * has initialized wp_query.
   */
  public function determineCurrentPageDetails() {
    if (!isset($this->current_page_type)) {
      $this->current_page_type =
          GooglePublisherPluginUtils::getWordPressPageType();
    }
    if (!isset($this->exclude_ads)) {
      $this->exclude_ads =
          GooglePublisherPluginUtils::getExcludeAds();
    }
  }

  public function getThemeHash() {
    if ($this->theme_hash == '') {
      $this->theme_hash = $this->computeThemeHash();
    }
    return $this->theme_hash;
  }
}
