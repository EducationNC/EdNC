<?php

if ( ! class_exists('XmlExportUser') ){

	final class XmlExportUser
	{		
		private $init_fields = array(			
			array(
				'label' => 'id',
				'name'  => 'ID',
				'type'  => 'id'
			),						
			array(
				'label' => 'user_email',
				'name'  => 'User Email',
				'type'  => 'user_email'
			),			 			
			array(
				'label' => 'user_login',
				'name'  => 'User Login',
				'type'  => 'user_login'
			)			
		);

		private $default_fields = array(
			array(
				'label' => 'id',
				'name'  => 'ID',
				'type'  => 'id'
			),
			array(
				'label' => 'user_login',
				'name'  => 'User Login',
				'type'  => 'user_login'
			),			
			array(
				'label' => 'user_email',
				'name'  => 'User Email',
				'type'  => 'user_email'
			),
			array(
				'label' => 'first_name',
				'name'  => 'First Name',
				'type'  => 'first_name'
			),
			array(
				'label' => 'last_name',
				'name'  => 'Last Name',
				'type'  => 'last_name'
			),
			array(
				'label' => 'user_registered',
				'name'  => 'User Registered',
				'type'  => 'user_registered'
			),
			array(
				'label' => 'user_nicename',
				'name'  => 'User Nicename',
				'type'  => 'user_nicename'
			),
			array(
				'label' => 'user_url',
				'name'  => 'User URL',
				'type'  => 'user_url'
			),
			array(
				'label' => 'display_name',
				'name'  => 'Display Name',
				'type'  => 'display_name'
			),
			array(
				'label' => 'nickname',
				'name'  => 'Nickname',
				'type'  => 'nickname'
			),
			array(
				'label' => 'description',
				'name'  => 'Description',
				'type'  => 'description'
			)
		);		

		private $advanced_fields = array(					
			array(
				'label' => 'rich_editing',
				'name'  => 'rich_editing',
				'type'  => 'cf'
			),
			array(
				'label' => 'comment_shortcuts',
				'name'  => 'comment_shortcuts',
				'type'  => 'cf'
			),
			array(
				'label' => 'admin_color',
				'name'  => 'admin_color',
				'type'  => 'cf'
			),
			array(
				'label' => 'use_ssl',
				'name'  => 'use_ssl',
				'type'  => 'cf'
			),
			array(
				'label' => 'show_admin_bar_front',
				'name'  => 'show_admin_bar_front',
				'type'  => 'cf'
			),
			array(
				'label' => 'wp_capabilities',
				'name'  => 'wp_capabilities',
				'type'  => 'wp_capabilities'
			),
			array(
				'label' => 'wp_user_level',
				'name'  => 'wp_user_level',
				'type'  => 'cf'
			),
			array(
				'label' => 'show_welcome_panel',
				'name'  => 'show_welcome_panel',
				'type'  => 'cf'
			),
			array(
				'label' => 'user_pass',
				'name'  => 'user_pass',
				'type'  => 'user_pass'
			),			
			array(
				'label' => 'dismissed_wp_pointers',
				'name'  => 'dismissed_wp_pointers',
				'type'  => 'cf'
			),										
			array(
				'label' => 'session_tokens',
				'name'  => 'session_tokens',
				'type'  => 'cf'
			),
			array(
				'label' => 'wp_user-settings',
				'name'  => 'wp_user-settings',
				'type'  => 'cf'
			),
			array(
				'label' => 'wp_user-settings-time',
				'name'  => 'wp_user-settings-time',
				'type'  => 'cf'
			),
			array(
				'label' => 'wp_dashboard_quick_press_last_post_id',
				'name'  => 'wp_dashboard_quick_press_last_post_id',
				'type'  => 'cf'
			),			
			array(
				'label' => 'user_activation_key',
				'name'  => 'user_activation_key',
				'type'  => 'user_activation_key'
			),			
			array(
				'label' => 'user_status',
				'name'  => 'user_status',
				'type'  => 'user_status'
			)
		);

		public static $is_active = true;

		public static $is_export_shop_customer = false;

		public function __construct()
		{			

			if ( ( XmlExportEngine::$exportOptions['export_type'] == 'specific' and ! in_array('users', XmlExportEngine::$post_types)  and ! in_array('shop_customer', XmlExportEngine::$post_types) ) 
					or ( XmlExportEngine::$exportOptions['export_type'] == 'advanced' and XmlExportEngine::$exportOptions['wp_query_selector'] != 'wp_user_query' ) ){ 
				self::$is_active = false;
				return;
			}

			self::$is_active = true;

			if (in_array('shop_customer', XmlExportEngine::$post_types)) self::$is_export_shop_customer = true;			
			
			add_filter("wp_all_export_available_data", 		array( &$this, "filter_available_data"), 10, 1);
			add_filter("wp_all_export_available_sections", 	array( &$this, "filter_available_sections" ), 10, 1);
			add_filter("wp_all_export_init_fields", 		array( &$this, "filter_init_fields"), 10, 1);
			add_filter("wp_all_export_default_fields", 		array( &$this, "filter_default_fields"), 10, 1);
			add_filter("wp_all_export_other_fields", 		array( &$this, "filter_other_fields"), 10, 1);
			
		}

		// [FILTERS]
			
			/**
			*
			* Filter Init Fields
			*
			*/
			public function filter_init_fields($init_fields){
				return $this->init_fields;
			}

			/**
			*
			* Filter Default Fields
			*
			*/
			public function filter_default_fields($default_fields){
				return $this->default_fields;
			}

			/**
			*
			* Filter Other Fields
			*
			*/
			public function filter_other_fields($other_fields){
				return $this->advanced_fields;
			}	

			/**
			*
			* Filter Available Data
			*
			*/	
			public function filter_available_data($available_data){

				if (self::$is_export_shop_customer)
				{
					$available_data['address_fields'] = $this->available_customer_data();
				}
				elseif (self::$is_woo_custom_founded)
				{
					$available_data['customer_fields'] = $this->available_customer_data();
				}

				return $available_data;
			}

			/**
			*
			* Filter Sections in Available Data
			*
			*/
			public function filter_available_sections($available_sections){	
										
				unset($available_sections['cats']);
				unset($available_sections['media']);		

				if (self::$is_export_shop_customer)
				{
					$customer_data = array(
						'address' => array(
							'title'   => __("Address", "wp_all_export_plugin"), 
							'content' => 'address_fields'					
						)
					);

					return array_merge(array_slice($available_sections, 0, 1), $customer_data, array_slice($available_sections, 1));	
				}			
				elseif (self::$is_woo_custom_founded)
				{
					$customer_data = array(
						'customer' => array(
							'title'   => __("Customer", "wp_all_export_plugin"), 
							'content' => 'customer_fields'					
						)
					);
					$available_sections = array_merge(array_slice($available_sections, 0, 1), $customer_data, array_slice($available_sections, 1));	
				}	

				self::$is_export_shop_customer or $available_sections['other']['title'] = __("Advanced", "wp_all_export_plugin");			

				return $available_sections;
			}					

		// [\FILTERS]
			
		public static $meta_keys;		
		public static $is_woo_custom_founded = false;
		public function init( & $existing_meta_keys = array() )
		{
			if ( ! self::$is_active ) return;

			global $wpdb;
			$table_prefix = $wpdb->prefix;
			self::$meta_keys = $wpdb->get_results("SELECT DISTINCT {$table_prefix}usermeta.meta_key FROM {$table_prefix}usermeta, {$table_prefix}users WHERE {$table_prefix}usermeta.user_id = {$table_prefix}users.ID LIMIT 500");			
			
			if ( ! empty(self::$meta_keys)){

				$address_fields = $this->available_customer_data();
				// detect if at least one filtered user is a WooCommerce customer
				foreach (self::$meta_keys as $meta_key) {
					if ( $meta_key->meta_key == '_customer_user'){
						self::$is_woo_custom_founded = true;
						break;
					}
				}

				$exclude_keys = array('_first_variation_attributes', '_is_first_variation_created');
				foreach (self::$meta_keys as $meta_key) {
					if ( ! in_array($meta_key->meta_key, $exclude_keys))
					{
						$to_add = true;
						foreach ($this->default_fields as $default_value) {
							if ( $meta_key->meta_key == $default_value['name'] || $meta_key->meta_key == $default_value['type'] ){
								$to_add = false;
								break;
							}
						}
						if ( $to_add ){
							foreach ($this->advanced_fields as $advanced_value) {
								if ( $meta_key->meta_key == $advanced_value['name'] || $meta_key->meta_key == $advanced_value['type']){
									$to_add = false;
									break;
								}
							}
						}
						if ( $to_add && ( self::$is_export_shop_customer || self::$is_woo_custom_founded ) )
						{							
							foreach ($address_fields as $address_value) {										
								if ( $meta_key->meta_key == $address_value['label']){
									$to_add = false;
									break;
								}
							}
						}
						if ( $to_add )
						{									
							$existing_meta_keys[] = $meta_key->meta_key;
						} 
					}						
				}
			}			
		}

		public function available_customer_data()
		{
			
			$main_fields = array(
				array(
					'name' => __('Customer User ID', 'wp_all_export_plugin'),
					'label' => '_customer_user',
					'type' => 'cf'
				)						
			);

			$data = array_merge($main_fields, $this->available_billing_information_data(), $this->available_shipping_information_data());

			return apply_filters('wp_all_export_available_user_data_filter', $data);
		
		}

		public function available_billing_information_data()
		{
			
			$keys = array(
				'billing_first_name',  'billing_last_name', 'billing_company',
				'billing_address_1', 'billing_address_2', 'billing_city',
				'billing_postcode', 'billing_country', 'billing_state', 
				'billing_email', 'billing_phone'
			);

			$data = $this->generate_friendly_titles($keys, 'billing');

			return apply_filters('wp_all_export_available_billing_information_data_filter', $data);
		
		}

		public function available_shipping_information_data()
		{
			
			$keys = array(
				'shipping_first_name', 'shipping_last_name', 'shipping_company', 
				'shipping_address_1', 'shipping_address_2', 'shipping_city', 
				'shipping_postcode', 'shipping_country', 'shipping_state'
			);

			$data = $this->generate_friendly_titles($keys, 'shipping');

			return apply_filters('wp_all_export_available_shipping_information_data_filter', $data);
		
		}

		public function generate_friendly_titles($keys, $keyword = ''){
			$data = array();
			foreach ($keys as $key) {				
									
				$key1 = $this->fix_titles(str_replace('_', ' ', $key));
						$key2 = '';

						if(strpos($key1, $keyword)!== false)
						{
							$key1 = str_replace($keyword, '', $key1);
							$key2 = ' ('.__($keyword, 'wp_all_export_plugin').')';
						}
				
				$data[] = array(
					'name'  => __(trim($key1), 'woocommerce').$key2,
					'label' => $key,
					'type'  => 'cf'
				);
										
			}
			return $data;
		}
			/**
			*
			* Helper method to fix fields title
			*
			*/
			protected function fix_titles($field)
			{
				if (is_array($field))
				{
					$field['name'] = $this->fix_title($field['name']);
				}
				else
				{
					$field = $this->fix_title($field);
				}					
				return $field;
			}
				/**
				*
				* Helper method to fix single title
				*
				*/
				protected function fix_title($title)
				{
					$uc_title = ucwords(trim(str_replace("_", " ", $title)));

					return stripos($uc_title, "width") === false ? str_ireplace(array('id', 'url', 'sku'), array('ID', 'URL', 'SKU'), $uc_title) : $uc_title;
				}			

		/**
	     * __get function.
	     *
	     * @access public
	     * @param mixed $key
	     * @return mixed
	     */
	    public function __get( $key ) {
	        return $this->get( $key );
	    }	

	    /**
	     * Get a session variable
	     *
	     * @param string $key
	     * @param  mixed $default used if the session variable isn't set
	     * @return mixed value of session variable
	     */
	    public function get( $key, $default = null ) {        
	        return isset( $this->{$key} ) ? $this->{$key} : $default;
	    }
	}
}