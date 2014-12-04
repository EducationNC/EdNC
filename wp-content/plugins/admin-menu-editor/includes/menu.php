<?php
abstract class ameMenu {
	const format_name = 'Admin Menu Editor menu';
	const format_version = '5.41';

	/**
	 * Load an admin menu from a JSON string.
	 *
	 * @static
	 *
	 * @param string $json A JSON-encoded menu structure.
	 * @param bool $assume_correct_format Skip the format header check and assume everything is fine. Defaults to false.
	 * @param bool $always_normalize Always normalize the menu structure, even if format[is_normalized] is true.
	 * @throws InvalidMenuException
	 * @return array
	 */
	public static function load_json($json, $assume_correct_format = false, $always_normalize = false) {
		$arr = json_decode($json, true);
		if ( !is_array($arr) ) {
			throw new InvalidMenuException('The input is not a valid JSON-encoded admin menu.');
		}
		return self::load_array($arr, $assume_correct_format, $always_normalize);
	}

	/**
	 * Load an admin menu structure from an associative array.
	 *
	 * @static
	 *
	 * @param array $arr
	 * @param bool $assume_correct_format
	 * @param bool $always_normalize
	 * @throws InvalidMenuException
	 * @return array
	 */
	public static function load_array($arr, $assume_correct_format = false, $always_normalize = false){
		$is_normalized = false;
		if ( !$assume_correct_format ) {
			if ( isset($arr['format']) && ($arr['format']['name'] == self::format_name) ) {
				$compared = version_compare($arr['format']['version'], self::format_version);
				if ( $compared > 0 ) {
					throw new InvalidMenuException(sprintf(
						"Can't load a menu created by a newer version of the plugin. Menu format: '%s', newest supported format: '%s'.",
						$arr['format']['version'],
						self::format_version
					));
				}
				//We can skip normalization if the version number matches exactly and the menu is already normalized.
				if ( ($compared === 0) && isset($arr['format']['is_normalized']) ) {
					$is_normalized = $arr['format']['is_normalized'];
				}
			} else {
				return self::load_menu_40($arr);
			}
		}

		if ( !(isset($arr['tree']) && is_array($arr['tree'])) ) {
			throw new InvalidMenuException("Failed to load a menu - the menu tree is missing.");
		}

		$menu = array('tree' => array());
		$menu = self::add_format_header($menu);

		if ( $is_normalized && !$always_normalize ) {
			$menu['tree'] = $arr['tree'];
		} else {
			foreach($arr['tree'] as $file => $item) {
				$menu['tree'][$file] = ameMenuItem::normalize($item);
			}
			$menu['format']['is_normalized'] = true;
		}

		if ( isset($arr['color_css']) && is_string($arr['color_css']) ) {
			$menu['color_css'] = $arr['color_css'];
			$menu['color_css_modified'] = isset($arr['color_css_modified']) ? intval($arr['color_css_modified']) : 0;
		}

		return $menu;
	}

	/**
	 * "Pre-load" an old menu structure.
	 *
	 * In older versions of the plugin, the entire menu consisted of
	 * just the menu tree and nothing else. This was internally known as
	 * menu format "4".
	 *
	 * To improve portability and forward-compatibility, newer versions
	 * use a simple dictionary-based container instead, with the menu tree
	 * being one of the possible entries.
	 *
	 * @static
	 * @param array $arr
	 * @return array
	 */
	private static function load_menu_40($arr) {
		//This is *very* basic and might need to be improved.
		$menu = array('tree' => $arr);
		return self::load_array($menu, true);
	}

	private static function add_format_header($menu) {
		$menu['format'] = array(
			'name' => self::format_name,
			'version' => self::format_version,
		);
		return $menu;
	}

	/**
	 * Serialize an admin menu as JSON.
	 *
	 * @static
	 * @param array $menu
	 * @return string
	 */
	public static function to_json($menu) {
		$menu = self::add_format_header($menu);
		return json_encode($menu);
	}

  /**
   * Sort the menus and menu items of a given menu according to their positions
   *
   * @param array $tree A menu structure in the internal format (just the tree).
   * @return array Sorted menu in the internal format
   */
	public static function sort_menu_tree($tree){
		//Resort the tree to ensure the found items are in the right spots
		uasort($tree, 'ameMenuItem::compare_position');
		//Resort all submenus as well
		foreach ($tree as &$topmenu){
			if (!empty($topmenu['items'])){
				uasort($topmenu['items'], 'ameMenuItem::compare_position');
			}
		}

		return $tree;
	}

   /**
	* Convert the WP menu structure to the internal representation. All properties set as defaults.
	*
	* @param array $menu
	* @param array $submenu
	* @return array Menu in the internal tree format.
	*/
	public static function wp2tree($menu, $submenu){
		$tree = array();
		foreach ($menu as $pos => $item){

			$tree_item = ameMenuItem::blank_menu();
			$tree_item['defaults'] = ameMenuItem::fromWpItem($item, $pos);
			$tree_item['separator'] = $tree_item['defaults']['separator'];

			//Attach sub-menu items
			$parent = $tree_item['defaults']['file'];
			if ( isset($submenu[$parent]) ){
				foreach($submenu[$parent] as $position => $subitem){
					$tree_item['items'][] = array_merge(
						ameMenuItem::blank_menu(),
						array('defaults' => ameMenuItem::fromWpItem($subitem, $position, $parent))
					);
				}
			}

			$tree[$parent] = $tree_item;
		}

		$tree = self::sort_menu_tree($tree);

		return $tree;
	}

	/**
	 * Check if a menu contains any items with the "hidden" flag set to true.
	 *
	 * @param array $menu
	 * @return bool
	 */
	public static function has_hidden_items($menu) {
		if ( !is_array($menu) || empty($menu) || empty($menu['tree']) ) {
			return false;
		}

		foreach($menu['tree'] as $item) {
			if ( ameMenuItem::get($item, 'hidden') ) {
				return true;
			}
			if ( !empty($item['items']) ) {
				foreach($item['items'] as $child) {
					if ( ameMenuItem::get($child, 'hidden') ) {
						return true;
					}
				}
			}
		}

		return false;
	}

	/**
	 * Recursively filter a list of menu items and remove items flagged as missing.
	 *
	 * @param array $items An array of menu items to filter.
	 * @return array
	 */
	public static function remove_missing_items($items) {
		$items = array_filter($items, array(__CLASS__, 'is_not_missing'));

		foreach($items as &$item) {
			if ( !empty($item['items']) ) {
				$item['items'] = self::remove_missing_items($item['items']);
			}
		}

		return $items;
	}

	protected static function is_not_missing($item) {
		return empty($item['missing']);
	}
}


class InvalidMenuException extends Exception {}