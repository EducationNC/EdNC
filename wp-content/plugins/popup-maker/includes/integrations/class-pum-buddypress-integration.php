<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class PUM_BuddyPress_Integration
 */
class PUM_BuddyPress_Integration {

	/**
	 *
	 */
	public static function init() {
		if ( function_exists( 'buddypress' ) || class_exists( 'BuddyPress' ) ) {
			add_filter( 'pum_get_conditions', array( __CLASS__, 'get_conditions' ) );
			add_filter( 'pum_condition_sort_order', array( __CLASS__, 'condition_sort_order' ) );
		}
	}

	/**
	 * @param array $conditions
	 *
	 * @return array
	 */
	public static function get_conditions( $conditions = array() ) {
		global $bp;

		// Add Additional Conditions
		$conditions['is_buddypress'] = array(
			'group'    => __( 'BuddyPress', 'buddypress' ),
			'labels'   => array(
				'name' => __( 'BP: Is a BuddyPress Page', 'popup-maker' ),
			),
			'callback' => 'is_buddypress',
		);

		$conditions['bp_is_user'] = array(
			'group'    => __( 'BuddyPress', 'buddypress' ),
			'labels'   => array(
				'name' => __( 'BP: Is User Page', 'popup-maker' ),
			),
			'callback' => 'bp_is_user',
		);

		$conditions['bp_is_group'] = array(
			'group'    => __( 'BuddyPress', 'buddypress' ),
			'labels'   => array(
				'name' => __( 'BP: Is Group Page', 'popup-maker' ),
			),
			'callback' => 'bp_is_group',
		);

		$conditions['bp_is_user_messages'] = array(
			'group'    => __( 'BuddyPress', 'buddypress' ),
			'labels'   => array(
				'name' => __( 'BP: Is User Messages Page', 'popup-maker' ),
			),
			'callback' => 'bp_is_user_messages',
		);

		$conditions['bp_is_activation_page'] = array(
			'group'    => __( 'BuddyPress', 'buddypress' ),
			'labels'   => array(
				'name' => __( 'BP: Is Activation Page', 'popup-maker' ),
			),
			'callback' => 'bp_is_activation_page',
		);

		$conditions['bp_is_register_page'] = array(
			'group'    => __( 'BuddyPress', 'buddypress' ),
			'labels'   => array(
				'name' => __( 'BP: Is Register Page', 'popup-maker' ),
			),
			'callback' => 'bp_is_register_page',
		);

		$conditions['bp_is_item_admin'] = array(
			'group'    => __( 'BuddyPress', 'buddypress' ),
			'labels'   => array(
				'name' => __( 'BP: Is Item Admin', 'popup-maker' ),
			),
			'callback' => 'bp_is_item_admin',
		);

		$conditions['bp_is_item_mod'] = array(
			'group'    => __( 'BuddyPress', 'buddypress' ),
			'labels'   => array(
				'name' => __( 'BP: Is Item Mod', 'popup-maker' ),
			),
			'callback' => 'bp_is_item_mod',
		);

		$conditions['bp_is_directory'] = array(
			'group'    => __( 'BuddyPress', 'buddypress' ),
			'labels'   => array(
				'name' => __( 'BP: Is Directory', 'popup-maker' ),
			),
			'callback' => 'bp_is_directory',
		);

		$components = array();

		foreach ( $bp->active_components as $component => $key ) {
			$components[ ucfirst( $component ) ] = $component;
		}

		$conditions['bp_is_current_component'] = array(
			'group'    => __( 'BuddyPress', 'buddypress' ),
			'labels'   => array(
				'name' => __( 'BP: Is Current Component', 'popup-maker' ),
			),
			'fields'   => array(
				'selected' => array(
					'type'     => 'select',
					'multiple' => true,
					'as_array' => true,
					'select2'  => true,
					'options'  => $components,
					'label'    => __( 'Which components?' ),
				),
			),
			'callback' => array( __CLASS__, 'bp_is_current_component' ),
		);

		$conditions['bp_is_current_action'] = array(
			'group'    => __( 'BuddyPress', 'buddypress' ),
			'labels'   => array(
				'name' => __( 'BP: Is Current Action', 'popup-maker' ),
			),
			'fields'   => array(
				'selected' => array(
					'type'     => 'text',
					'label'    => __( 'Which actions?' ),
				),
			),
			'callback' => array( __CLASS__, 'bp_is_current_action' ),
		);

		$conditions['bp_is_action_variable'] = array(
			'group'    => __( 'BuddyPress', 'buddypress' ),
			'labels'   => array(
				'name' => __( 'BP: Is Action Variable', 'popup-maker' ),
			),
			'fields'   => array(
				'selected' => array(
					'type'     => 'text',
					'label'    => __( 'Which action variables?' ),
				),
			),
			'callback' => array( __CLASS__, 'bp_is_action_variable' ),
		);

		return $conditions;
	}

	/**
	 * Checks if the current page is the selected bp components.
	 *
	 * @param array $settings
	 *
	 * @return bool
	 */
	public static function bp_is_current_component( $settings = array() ) {
		global $bp;

		if ( empty ( $settings['selected'] ) ) {
			return false;
		}

		if ( ! is_array( $settings['selected'] ) ) {
			$settings['selected'] = array( $settings['selected'] );
		}

		$found = false;

		foreach ( $settings['selected'] as $component ) {
			if ( ! array_key_exists( $component, $bp->active_components ) ) {
				continue;
			}

			if ( bp_is_current_component( $component ) ) {
				$found = true;
			}

		}

		return $found;
	}

	/**
	 * Checks if the current page is the selected bp action.
	 *
	 * @param array $settings
	 *
	 * @return bool
	 */
	public static function bp_is_current_action( $settings = array() ) {

		if ( empty ( $settings['selected'] ) ) {
			return false;
		}

		if ( ! is_array( $settings['selected'] ) ) {
			$settings['selected'] = array_map( 'trim', explode( ',', $settings['selected'] ) );
		}

		$found = false;

		foreach ( $settings['selected'] as $action ) {
			if ( bp_is_current_action( $action ) ) {
				$found = true;
			}
		}

		return $found;
	}


	/**
	 * Checks if the current page is the selected bp action variable.
	 *
	 * @param array $settings
	 *
	 * @return bool
	 */
	public static function bp_is_action_variable( $settings = array() ) {

		if ( empty ( $settings['selected'] ) ) {
			return false;
		}

		if ( ! is_array( $settings['selected'] ) ) {
			$settings['selected'] = array_map( 'trim', explode( ',', $settings['selected'] ) );
		}

		$found = false;

		foreach ( $settings['selected'] as $variable ) {
			if ( bp_is_action_variable( $variable ) ) {
				$found = true;
			}
		}

		return $found;
	}

	/**
	 * @param array $order
	 *
	 * @return array
	 */
	public static function condition_sort_order( $order = array() ) {
		$order[ __( 'BuddyPress', 'buddypress' ) ] = 5.756;

		return $order;
	}

}

add_action( 'init', 'PUM_BuddyPress_Integration::init' );
