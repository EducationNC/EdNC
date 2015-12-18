<?php

namespace Roots\Sage\Nav;

use Walker_Nav_Menu;

/**
 * Cleaner walker for wp_nav_menu()
 *
 * Walker_Nav_Menu (WordPress default) example output:
 *   <li id="menu-item-8" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-8"><a href="/">Home</a></li>
 *   <li id="menu-item-9" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-9"><a href="/sample-page/">Sample Page</a></l
 *
 * Roots_Nav_Walker example output:
 *   <li class="menu-home"><a href="/">Home</a></li>
 *   <li class="menu-sample-page"><a href="/sample-page/">Sample Page</a></li>
 */
class Widgets_Nav_Walker extends Walker_Nav_Menu {
  function check_current($classes) {
    return preg_match('/(current[-_])|active|dropdown/', $classes);
  }

  function start_lvl(&$output, $depth = 0, $args = array()) {
    $output .= "\n<ul class=\"dropdown-menu\">\n";
  }

  function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
    $item_html = '';
    parent::start_el($item_html, $item, $depth, $args);

    // Check if this is a widgetized item
    $item_widgetized = false;
    $item_sidebars = get_post_meta($item->ID, '_widgetized_nav_sidebars', true);
    if (!empty($item_sidebars) && $item_sidebars !== ' ') {
      $item_widgetized = true;
    }

    if (($item->is_dropdown || $item_widgetized) && ($depth === 0)) {
      $item_html = str_replace('<a', '<a class="dropdown-toggle" data-toggle="dropdown" data-target="#"', $item_html);
      // $item_html = str_replace('</a>', ' <b class="caret"></b></a>', $item_html);
    }
    elseif (stristr($item_html, 'li class="divider')) {
      $item_html = preg_replace('/<a[^>]*>.*?<\/a>/iU', '', $item_html);
    }
    elseif (stristr($item_html, 'li class="dropdown-header')) {
      $item_html = preg_replace('/<a[^>]*>(.*)<\/a>/iU', '$1', $item_html);
    }

    $item_html = apply_filters('roots/wp_nav_menu_item', $item_html);
    $output .= $item_html;

    // Display sidebars for widgetized menus
    if ($item_widgetized == true) {

      // Get sidebar ids from menu item custom field and put into array
      $sidebars = explode(',', $item_sidebars);

      // Count sidebars to determine width of columns
      $sidebar_count = sizeof($sidebars);
      $column_size = 12 / $sidebar_count;

      ob_start();
      ?>
      <ul class="dropdown-menu widgetized-menu sidebars-<?php echo $sidebar_count; ?>">
        <?php
        foreach ($sidebars as $sidebar) {
          echo '<li class="menu-widget-column col-md-' . $column_size . '">';
            dynamic_sidebar(trim($sidebar));
          echo '</li>';
        }
        ?>
      </ul>
      <?php
      $output .= ob_get_clean();
    }
  }

  function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) {

    // Check if this is a widgetized item
    $item_widgetized = false;
    $item_sidebars = get_post_meta($element->ID, '_widgetized_nav_sidebars', true);
    if (!empty($item_sidebars) && $item_sidebars !== ' ') {
      $item_widgetized = true;
    }

    $element->is_dropdown = (($item_widgetized == true) || ((!empty($children_elements[$element->ID]) && (($depth + 1) < $max_depth || ($max_depth === 0)))));

    if ($element->is_dropdown) {
      $element->classes[] = 'dropdown';
    }

    parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
  }
}

/**
 * Utility function
 */
function is_element_empty($element) {
  $element = trim($element);
  return !empty($element);
}

/**
 * Remove the id="" on nav menu items
 * Return 'menu-slug' for nav menu classes
 */
function nav_menu_css_class($classes, $item) {
  $slug = sanitize_title($item->title);
  $classes = preg_replace('/(current(-menu-|[-_]page[-_])(item|parent|ancestor))/', 'active', $classes);
  $classes = preg_replace('/^((menu|page)[-_\w+]+)+/', '', $classes);

  $classes[] = 'menu-' . $slug;

  $classes = array_unique($classes);

  return array_filter($classes, __NAMESPACE__ . '\\is_element_empty');
}
add_filter('nav_menu_css_class', __NAMESPACE__ . '\\nav_menu_css_class', 10, 2);
add_filter('nav_menu_item_id', '__return_null');


/**
 * Mobile widgetized menu walker
 *
 */
class Mobile_Nav_Walker extends Walker_Nav_Menu {
 function check_current($classes) {
   return preg_match('/(current[-_])|active|dropdown/', $classes);
 }

 function start_lvl(&$output, $depth = 0, $args = array()) {
   $output .= "\n<ul>\n";
 }

 function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
   $item_html = '';
   parent::start_el($item_html, $item, $depth, $args);

       // Check if this is a widgetized item
       $item_widgetized = false;
       $item_sidebars = get_post_meta($item->ID, '_widgetized_nav_sidebars', true);
       if (!empty($item_sidebars) && $item_sidebars !== ' ') {
         $item_widgetized = true;
       }

   if (($item->is_dropdown || $item_widgetized) && ($depth === 0)) {
     $item_html = str_replace('<a', '<a class="expandable-title" href="#expandable' . $item->ID . '"', $item_html);
     $item_html = str_replace('</a>', ' <span class="caret"></span></a>', $item_html);
   }
   elseif (stristr($item_html, 'li class="divider')) {
     $item_html = preg_replace('/<a[^>]*>.*?<\/a>/iU', '', $item_html);
   }
   elseif (stristr($item_html, 'li class="dropdown-header')) {
     $item_html = preg_replace('/<a[^>]*>(.*)<\/a>/iU', '$1', $item_html);
   }

   $item_html = apply_filters('roots/wp_nav_menu_item', $item_html);
   $output .= $item_html;

   // Display sidebars for widgetized menus
   if ($item_widgetized == true) {

     // Get sidebar ids from menu item custom field and put into array
     $sidebars = explode(',', $item_sidebars);

     ob_start();
     ?>
     <ul id="expandable<?php echo $item->ID; ?>" class="expandable">
       <?php
       foreach ($sidebars as $sidebar) {
         echo '<li>';
           dynamic_sidebar(trim($sidebar));
         echo '</li>';
       }
       ?>
     </ul>
     <?php
     $output .= ob_get_clean();
   }
 }

 function end_lvl( &$output, $depth = 0, $args = array() ) {
   $indent = str_repeat("\t", $depth);
   $output .= "$indent</ul>\n";
 }

 function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) {
   $element->is_dropdown = ((!empty($children_elements[$element->ID]) && (($depth + 1) < $max_depth || ($max_depth === 0))));

   parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
 }
}
