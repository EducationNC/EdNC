<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'Tribe__App_Shop' ) ) {
	/**
	 * Class that handles the integration with our Shop App API
	 */
	class Tribe__App_Shop {

		/**
		 * Version of the data model
		 */
		const API_VERSION = '1.0';
		/**
		 * URL of the API
		 */
		const API_ENDPOINT = 'http://tri.be/api/app-shop/';

		/**
		 * Base name for the transients key
		 */
		const CACHE_KEY_BASE = 'tribe-app-shop';
		/**
		 * Duration of the transients, in seconds.
		 */
		const CACHE_EXPIRATION = 300; //5 min

		/**
		 * Slug of the WP admin menu item
		 */
		const MENU_SLUG = 'tribe-app-shop';

		/**
		 * Singleton instance
		 *
		 * @var null or Tribe__App_Shop
		 */
		private static $instance = null;
		/**
		 * The slug for the new admin page
		 *
		 * @var string
		 */
		private $admin_page = null;


		/**
		 * Class constructor
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'add_menu_page' ), 100 );
			add_action( 'wp_before_admin_bar_render', array( $this, 'add_toolbar_item' ), 20 );
		}

		/**
		 * Adds the page to the admin menu
		 */
		public function add_menu_page() {
			if ( ! Tribe__Settings::instance()->should_setup_pages() ) {
				return;
			}

			$page_title = esc_html__( 'Event Add-Ons', 'tribe-common' );
			$menu_title = esc_html__( 'Event Add-Ons', 'tribe-common' );
			$capability = apply_filters( 'tribe_events_addon_page_capability', 'install_plugins' );

			$where = Tribe__Settings::instance()->get_parent_slug();

			$this->admin_page = add_submenu_page( $where, $page_title, $menu_title, $capability, self::MENU_SLUG, array( $this, 'do_menu_page' ) );

			add_action( 'admin_print_styles-' . $this->admin_page, array( $this, 'enqueue' ) );
		}

		/**
		 * Adds a link to the shop app to the WP admin bar
		 */
		public function add_toolbar_item() {

			$capability = apply_filters( 'tribe_events_addon_page_capability', 'install_plugins' );

			// prevent users who cannot install plugins from seeing addons link
			if ( current_user_can( $capability ) ) {
				global $wp_admin_bar;

				$wp_admin_bar->add_menu( array(
					'id'     => 'tribe-events-app-shop',
					'title'  => esc_html__( 'Event Add-Ons', 'tribe-common' ),
					'href'   => Tribe__Settings::instance()->get_url( array( 'page' => self::MENU_SLUG ) ),
					'parent' => 'tribe-events-settings-group',
				) );
			}
		}

		/**
		 * Enqueue the styles and script
		 */
		public function enqueue() {
			wp_enqueue_style( 'app-shop', tribe_resource_url( 'app-shop.css', false, 'common' ), array(), apply_filters( 'tribe_events_css_version', Tribe__Main::VERSION ) );
			wp_enqueue_script( 'app-shop', tribe_resource_url( 'app-shop.js', false, 'common' ), array(), apply_filters( 'tribe_events_js_version', Tribe__Main::VERSION ) );
		}

		/**
		 * Renders the Shop App page
		 */
		public function do_menu_page() {
			$remote = $this->get_all_products();

			if ( ! empty( $remote ) ) {
				$products = null;
				if ( property_exists( $remote, 'data' ) ) {
					$products = $remote->data;
				}
				$banner = null;
				if ( property_exists( $remote, 'banner' ) ) {
					$banner = $remote->banner;
				}

				if ( empty( $products ) ) {
					return;
				}

				$categories = array_unique( wp_list_pluck( $products, 'category' ) );

				include_once Tribe__Main::instance()->plugin_path . 'src/admin-views/app-shop.php';
			}

		}

		/**
		 * Get's all products from the API
		 *
		 * @return array|WP_Error
		 */
		private function get_all_products() {

			$cache_key = self::CACHE_KEY_BASE . '-products';
			$products  = get_transient( $cache_key );

			if ( ! $products ) {
				$products = $this->remote_get( 'get-products' );
				if ( $products && ! $products->error ) {
					set_transient( $cache_key, $products, self::CACHE_EXPIRATION );
				}
			}

			if ( is_string( $products ) ) {
				$products = json_decode( $products );
			}

			return $products;

		}

		/**
		 * Makes the remote call to the API endpoint
		 *
		 * @param            $action
		 * @param array|null $args
		 *
		 * @return array|WP_Error
		 */
		private function remote_get( $action, $args = null ) {

			$url = trailingslashit( self::API_ENDPOINT . self::API_VERSION ) . $action;

			$ret = wp_remote_get( $url );

			if ( ! is_wp_error( $ret ) && isset( $ret['body'] ) ) {
				return json_decode( $ret['body'] );
			}

			return null;

		}

		/**
		 * Static Singleton Factory Method
		 *
		 * @return Tribe__App_Shop
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) ) {
				$className      = __CLASS__;
				self::$instance = new $className;
			}

			return self::$instance;
		}

	}
}
