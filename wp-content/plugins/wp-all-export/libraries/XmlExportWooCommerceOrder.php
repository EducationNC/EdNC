<?php

if ( ! class_exists('XmlExportWooCommerceOrder') )
{
	final class XmlExportWooCommerceOrder
	{		
		public static $is_active = true;

		public static $order_sections = array();		
		public static $order_items_per_line = false;
		public static $orders_data = null;
		public static $exportQuery = null;

		private $init_fields = array(			
			array(
				'name'    => 'Order ID',
				'type'    => 'woo_order',
				'options' => 'order',
				'label'   => 'ID'
			),
			array(
				'name'    => 'Order Key',
				'type'    => 'woo_order',
				'options' => 'order',
				'label'   => '_order_key'
			),			
			array(
				'name'    => 'Title',
				'type'    => 'woo_order',
				'options' => 'order',
				'label'   => 'post_title'
			)			
		);		

		private $order_core_fields = array();

		public function __construct()
		{			
			$this->order_core_fields = array('_prices_include_tax', '_customer_ip_address', '_customer_user_agent', '_created_via', '_order_version', '_payment_method', '_cart_discount_tax', '_order_shipping_tax', '_recorded_sales', '_order_stock_reduced', '_recorded_coupon_usage_counts', '_transaction_id');

			if ( ! class_exists('WooCommerce') 
					or ( XmlExportEngine::$exportOptions['export_type'] == 'specific' and ! in_array('shop_order', XmlExportEngine::$post_types) ) 
						or ( XmlExportEngine::$exportOptions['export_type'] == 'advanced' and strpos(XmlExportEngine::$exportOptions['wp_query'], 'shop_order') === false ) ) {
				self::$is_active = false;
				return;			
			}	

			self::$is_active = true;										

			if ( empty(PMXE_Plugin::$session) ) // if cron execution
			{
				$id = $_GET['export_id'];
				$export = new PMXE_Export_Record();
				$export->getById($id);	
				if ( ! $export->isEmpty() and $export->options['export_to'] == 'csv'){	
					$this->init_additional_data();
				}
			} 
			else
			{
				$this->init_additional_data();								
			}

			add_filter("wp_all_export_available_sections", 			array( &$this, "filter_available_sections" ), 10, 1);			
			add_filter("wp_all_export_csv_rows", 					array( &$this, "filter_csv_rows"), 10, 2);
			add_filter("wp_all_export_init_fields", 				array( &$this, "filter_init_fields"), 10, 1);			

			self::$order_sections = $this->available_sections();

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
			* Filter Sections in Available Data
			*
			*/
			public function filter_available_sections($sections){						
				return array();
			}								

		// [\FILTERS]

		public function init( & $existing_meta_keys = array() ){

			if ( ! self::$is_active ) return;	

			if ( ! empty($existing_meta_keys) )
			{
				foreach (self::$order_sections as $slug => $section) :
					
					foreach ($section['meta'] as $cur_meta_key => $cur_meta_label) 
					{	
						foreach ($existing_meta_keys as $key => $record_meta_key) 
						{
							if ( $record_meta_key == $cur_meta_key )
							{
								unset($existing_meta_keys[$key]);
								break;
							}
						}									
					}

				endforeach;		

				foreach ( $this->order_core_fields as $core_field ):

					foreach ($existing_meta_keys as $key => $record_meta_key) 
					{
						if ( $record_meta_key == $core_field )
						{
							unset($existing_meta_keys[$key]);
							break;
						}
					}

				endforeach;

				foreach ($existing_meta_keys as $key => $record_meta_key) 
				{							
					self::$order_sections['cf']['meta'][$record_meta_key] = array(
						'name' => $record_meta_key,
						'label' => $record_meta_key,
						'options' => '',
						'type' => 'cf'
					);
				}
			}				

			global $wpdb;
			$table_prefix = $wpdb->prefix;

			$product_data = $this->available_order_default_product_data();

			$meta_keys = $wpdb->get_results("SELECT DISTINCT {$table_prefix}woocommerce_order_itemmeta.meta_key FROM {$table_prefix}woocommerce_order_itemmeta");
			if ( ! empty($meta_keys)){
				foreach ($meta_keys as $meta_key) {
					if (strpos($meta_key->meta_key, "pa_") !== 0 and empty(self::$order_sections['cf']['meta'][$meta_key->meta_key]) and empty($product_data[$meta_key->meta_key])) 
						self::$order_sections['other']['meta'][$meta_key->meta_key] = $this->fix_titles(array(
							'name'    => $meta_key->meta_key,
							'label'   => $meta_key->meta_key,
							'options' => 'items',
							'type'    => 'woo_order'
						));
				}
			}	

			foreach ( $this->order_core_fields as $core_field ):

				self::$order_sections['other']['meta'][$core_field] = $this->fix_titles(array(
					'name'    => $core_field,
					'label'   => $core_field,
					'options' => '',
					'type'    => 'cf'
				));

			endforeach;
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

		public function init_additional_data(){

			if ( ! self::$is_active ) return;											

		}

		private $order_items  		= null;
		private $order_taxes  		= null;
		private $order_shipping 	= null;
		private $order_coupons 		= null;
		private $order_surcharge 	= null;
		private $__total_fee_amount = null;	
		private $__coupons_used     = null;
		private $order_id 			= null;

		// csv headers
		private $woo 		= array();
		private $woo_order 	= array();
		private $acfs 		= array();
		private $taxes 		= array();
		private $attributes = array();
		private $articles 	= array();

		protected function prepare_export_data( $record, $options, $elId, $preview ){						

			// an array with data to export
			$data = array(
				'items' => array(),
				'taxes' => array(),
				'shipping'  => array(),
				'coupons'   => array(),
				'surcharge' => array()
			); 

			global $wpdb;
			$table_prefix = $wpdb->prefix;								

			if ( ! empty($options['cc_value'][$elId]) ){						

				switch ($options['cc_options'][$elId]) {
					
					case 'order':
					case 'customer':					
						
						$data[$options['cc_name'][$elId]] = ( strpos($options['cc_value'][$elId], "_") === 0 ) ? get_post_meta($record->ID, $options['cc_value'][$elId], true) : $record->{$options['cc_value'][$elId]};

						if ($options['cc_value'][$elId] == "post_title")
						{							
							$data[$options['cc_name'][$elId]] = str_replace("&ndash;", '-', $data[$options['cc_name'][$elId]]);
						}

						$data[$options['cc_name'][$elId]] = pmxe_filter( $data[$options['cc_name'][$elId]], $fieldSnipped);	

						break;								
				}

			}

			return $data;
		}

		private $additional_articles = array();		

		public function export_csv( & $article, & $titles, $record, $options, $elId, $preview ){		

			if ( ! self::$is_active ) return;		

			$data_to_export = $this->prepare_export_data( $record, $options, $elId, $preview );

			foreach ($data_to_export as $key => $data) {
				
				if ( ! in_array($key, array('items', 'taxes', 'shipping', 'coupons', 'surcharge')) )
				{					
					// $article[$key] = $data;
					wp_all_export_write_article( $article, $key, $data );		
					// if ( ! in_array($key, $titles) ) $titles[] = $key;	
				}
			}			
		}

		public function filter_csv_rows($articles, $options){

			if ( ! empty($this->additional_articles) and $options['order_item_per_row'] and $options['export_to'] == 'csv')
			{
				$base_article = $articles[count($articles) - 1];				
				array_shift($this->additional_articles);								
				if ( ! empty($this->additional_articles ) ){
					foreach ($this->additional_articles as $article) {	
						if ($options['order_item_fill_empty_columns'])
						{
							foreach ($article as $key => $value) {
								unset($base_article[$key]);				
							}									
							$articles[] = @array_merge($base_article, $article);
						}
						else
						{
							$articles[] = $article;
						}						
					}
					$this->additional_articles = array();
				}				
			}			

			return $articles;
		}

		public function get_element_header( & $headers, $options, $element_key ){

			switch ($options['cc_value'][$element_key]) 
			{
				// Rate Code (per tax)
				case 'tax_order_item_name':
				// Rate Percentage (per tax)	
				case 'tax_rate':
				// Amount (per tax)	
				case 'tax_amount':

					if ( ! empty(self::$orders_data['taxes']))
					{
						foreach ( self::$orders_data['taxes'] as $tax) {
							$friendly_name = str_replace("per tax", $this->get_rate_friendly_name($tax->order_item_id), $options['cc_name'][$element_key]);							
							if ( ! in_array($friendly_name, $headers)) $headers[] = $friendly_name;
						}
					}

					break;
				// Discount Amount (per coupon)
				case 'discount_amount':

					if ( ! empty(self::$orders_data['coupons']))
					{
						foreach ( self::$orders_data['coupons'] as $coupon) {
							$friendly_name = str_replace("per coupon", $coupon->order_item_name, $options['cc_name'][$element_key]);
							if ( ! in_array($friendly_name, $headers)) $headers[] = $friendly_name;
						}
					}

					break;
				// Fee Amount (per surcharge)	
				case 'fee_line_total':
					
					if ( ! empty(self::$orders_data['fees']))
					{
						foreach ( self::$orders_data['fees'] as $fee) {
							$friendly_name = str_replace("Amount (per surcharge)", "(" . $fee->order_item_name . ")", $options['cc_name'][$element_key]);
							if ( ! in_array($friendly_name, $headers)) $headers[] = $friendly_name;
						}
					}

					break;	

				// Product Variation Details	
				case '__product_variation':
					
					if ( ! empty(self::$orders_data['line_items_max_count']) and ! empty(self::$orders_data['variations']))
					{
						if ($options['order_item_per_row']){
							foreach ( self::$orders_data['variations'] as $variation) {																
								$friendly_name = $options['cc_name'][$element_key] . " (" . sanitize_title(str_replace("pa_", "", $variation->meta_key)) . ")";									
								if ( ! in_array($friendly_name, $headers)) $headers[] = $friendly_name;
							}
						}
						else{							
							for ($i = 1; $i <= self::$orders_data['line_items_max_count']; $i++){
								foreach ( self::$orders_data['variations'] as $variation) {																
									$friendly_name = $options['cc_name'][$element_key] . " #" . $i . " (" . sanitize_title(str_replace("pa_", "", $variation->meta_key)) . ")";									
									if ( ! in_array($friendly_name, $headers)) $headers[] = $friendly_name;
								}
							}
						}
					}

					break;

				default:

					switch ($options['cc_options'][$element_key]) 
					{
						// Order's product basic data headers
						case 'items':
							
							if ($options['order_item_per_row'])
							{
								if ( ! in_array($options['cc_name'][$element_key], $headers)) $headers[] = $options['cc_name'][$element_key];
							}
							else
							{
								if ( ! empty(self::$orders_data['line_items_max_count'])){
									for ($i = 1; $i <= self::$orders_data['line_items_max_count']; $i++){
										$friendly_name = $options['cc_name'][$element_key] . " #" . $i;
										if ( ! in_array($friendly_name, $headers)) $headers[] = $friendly_name;
									}
								}
							}

							break;
												
						default:

							// Order's product advanced data headers
							if ( strpos($options['cc_label'][$element_key], 'item_data__') !== false)
							{
								$element_label = str_replace("item_data__", "", $options['cc_label'][$element_key]);

								$element_headers = array();

								$element_name = ( ! empty($options['cc_name'][$element_key]) ) ? $options['cc_name'][$element_key] : 'untitled_' . $element_key;							

								switch ($options['cc_type'][$element_key]) 
								{																		
									
									case 'woo':
										
										XmlExportEngine::$woo_export->get_element_header( $element_headers, $options, $element_key );		
										
										break;								

									case 'acf':

										if ( ! empty($this->acfs) ){
											$single_acf_field = array_shift($this->acfs);							
											if ( is_array($single_acf_field))
											{
												foreach ($single_acf_field as $acf_header) {
													if ( ! in_array($acf_header, $element_headers)) $element_headers[] = $acf_header;													
												}
											}
											else
											{
												if ( ! in_array($single_acf_field, $element_headers)) $element_headers[] = $single_acf_field;												
											}
										}
										
										break;
									
									default:

										if ( ! in_array($element_name, $element_headers)) 
										{
											$element_headers[] = $element_name;
										}
										else
										{
											$is_added = false;
											$i = 0;
											do
											{
												$new_element_name = $element_name . '_' . md5($i);

												if ( ! in_array($new_element_name, $element_headers) )
												{
													$element_headers[] = $new_element_name;
													$is_added = true;
												}

												$i++;
											}
											while ( ! $is_added );						
										}
										
										// $element_headers[] = $element_name;

										break;
								}

								if ( ! empty($element_headers) )
								{
									foreach ($element_headers as $header) 
									{
										if ($options['order_item_per_row'])
										{
											if ( ! in_array($header, $headers)) $headers[] = $header;
										}
										else
										{										
											if ( ! empty(self::$orders_data['line_items_max_count'])){
												for ($i = 1; $i <= self::$orders_data['line_items_max_count']; $i++){
													$friendly_name = $header . " #" . $i;												
													if ( ! in_array($friendly_name, $headers)) $headers[] = $friendly_name;
												}
											}
										}
									}
								}
							}						
							else
							{
								if ( ! in_array($options['cc_name'][$element_key], $headers))
								{
									$headers[] = $options['cc_name'][$element_key];
								}
								else
								{
									$is_added = false;
									$i = 0;
									do
									{
										$new_element_name = $options['cc_name'][$element_key] . '_' . md5($i);

										if ( ! in_array($new_element_name, $headers) )
										{
											$headers[] = $new_element_name;
											$is_added = true;
										}

										$i++;
									}
									while ( ! $is_added );	
								}
							}

							break;
					}					

					break;

			}

		}

		public function get_rate_friendly_name( $order_item_id ){

			global $wpdb;			
			$table_prefix = $wpdb->prefix;		

			$rate_details = null;
			$meta_data = $wpdb->get_results("SELECT * FROM {$table_prefix}woocommerce_order_itemmeta WHERE order_item_id = {$order_item_id}", ARRAY_A);			
			foreach ($meta_data as $meta) {
				if ($meta['meta_key'] == 'rate_id'){
					$rate_id = $meta['meta_value'];														
					$rate_details = $wpdb->get_row("SELECT * FROM {$table_prefix}woocommerce_tax_rates WHERE tax_rate_id = {$rate_id}");																																	
					break;
				}	
			}

			return $rate_details ? $rate_details->tax_rate_name : '';

		}

		public function export_xml( & $xmlWriter, $record, $options, $elId, $preview ){

			if ( ! self::$is_active ) return;	

			$data_to_export = $this->prepare_export_data( $record, $options, $elId, $preview );

			foreach ($data_to_export as $key => $data) {
				
				$element_name_ns = '';	
				$element_name = str_replace("-", "_", preg_replace('/[^a-z0-9:_]/i', '', $key));
				if (strpos($element_name, ":") !== false)
				{
					$element_name_parts = explode(":", $element_name);
					$element_name_ns = (empty($element_name_parts[0])) ? '' : $element_name_parts[0];
					$element_name = (empty($element_name_parts[1])) ? 'untitled_' . $ID : $element_name_parts[1];							
				}
				$xmlWriter->beginElement($element_name_ns, $element_name, null);
					$xmlWriter->writeData($data, $element_name);
				$xmlWriter->endElement();				
			}				
		}

		public static function prepare_child_exports( $export, $is_cron = false )
		{
			return array();
		}

		public function render( & $i ){			
				
			if ( ! self::$is_active ) return;

			foreach (self::$order_sections as $slug => $section) :
				if ( ! empty($section['meta']) or ! empty($section['additional']) ): 
				?>										
				<p class="wpae-available-fields-group"><?php echo $section['title']; ?><span class="wpae-expander">+</span></p>
				<div class="wpae-custom-field">
					<?php if ( ! in_array($slug, array('order', 'customer', 'cf', 'other'))) : ?>
					<div class="wpallexport-free-edition-notice">									
						<a class="upgrade_link" target="_blank" href="http://www.wpallimport.com/upgrade-to-wp-all-export-pro/?utm_source=wordpress.org&amp;utm_medium=wooco+orders&amp;utm_campaign=free+wp+all+export+plugin"><?php _e('Upgrade to the Pro edition of WP All Export to export custom order data.','wp_all_export_plugin');?></a>
					</div>
					<?php endif; ?>
					<ul>
						<?php if ( ! empty($section['meta']) ): ?>
						<li <?php if ( ! in_array($slug, array('order', 'customer', 'cf', 'other'))) : ?>class="wpallexport_disabled"<?php endif; ?>>
							<div class="default_column" rel="">								
								<label class="wpallexport-element-label"><?php echo __("All", "wp_all_export_plugin") . ' ' . $section['title'] . ' ' . __("Data", "wp_all_export_plugin"); ?></label>
								<input type="hidden" name="rules[]" value="pmxe_<?php echo $slug;?>"/>
							</div>
						</li>
						<?php
						foreach ($section['meta'] as $cur_meta_key => $field) {									
							?>
							<li class="pmxe_<?php echo $slug; ?> <?php if ( ! in_array($slug, array('order', 'customer', 'cf', 'other'))) : ?>wpallexport_disabled<?php endif;?>">
								<div class="custom_column" rel="<?php echo ($i + 1);?>">
									<label class="wpallexport-xml-element"><?php echo (is_array($field)) ? $field['name'] : $field; ?></label>
									<input type="hidden" name="ids[]" value="1"/>
									<input type="hidden" name="cc_label[]" value="<?php echo (is_array($field)) ? $field['label'] : $cur_meta_key; ?>"/>										
									<input type="hidden" name="cc_php[]" value=""/>										
									<input type="hidden" name="cc_code[]" value=""/>
									<input type="hidden" name="cc_sql[]" value=""/>
									<input type="hidden" name="cc_options[]" value="<?php echo (is_array($field)) ? $field['options'] : $slug;?>"/>										
									<input type="hidden" name="cc_type[]" value="<?php echo (is_array($field)) ? $field['type'] : 'woo_order'; ?>"/>
									<input type="hidden" name="cc_value[]" value="<?php echo (is_array($field)) ? $field['label'] : $cur_meta_key; ?>"/>
									<input type="hidden" name="cc_name[]" value="<?php echo (is_array($field)) ? $field['name'] : $field;?>"/>
									<input type="hidden" name="cc_settings[]" value=""/>
								</div>
							</li>
							<?php
							$i++;												
						}
						endif;																		

						if ( ! empty($section['additional']) )
						{
							foreach ($section['additional'] as $sub_slug => $sub_section) 
							{
								?>
								<li class="available_sub_section">
									<p class="wpae-available-fields-group"><?php echo $sub_section['title']; ?><span class="wpae-expander">+</span></p>
									<div class="wpae-custom-field">
									<ul>
										<li class="wpallexport_disabled">
											<div class="default_column" rel="">								
												<label class="wpallexport-element-label"><?php echo __("All", "wp_all_export_plugin") . ' ' . $sub_section['title']; ?></label>
												<input type="hidden" name="rules[]" value="pmxe_<?php echo $slug;?>_<?php echo $sub_slug;?>"/>
											</div>
										</li>
										<?php
										foreach ($sub_section['meta'] as $field) {
											?>
											<li class="pmxe_<?php echo $slug; ?>_<?php echo $sub_slug;?> wpallexport_disabled">
												<div class="custom_column" rel="<?php echo ($i + 1);?>">
													<label class="wpallexport-xml-element"><?php echo (is_array($field)) ? $field['name'] : $field; ?></label>
													<input type="hidden" name="ids[]" value="1"/>
													<input type="hidden" name="cc_label[]" value="item_data__<?php echo (is_array($field)) ? $field['label'] : $field; ?>"/>										
													<input type="hidden" name="cc_php[]" value=""/>										
													<input type="hidden" name="cc_code[]" value=""/>
													<input type="hidden" name="cc_sql[]" value=""/>
													<input type="hidden" name="cc_options[]" value="item_data"/>
													<input type="hidden" name="cc_type[]" value="<?php echo (is_array($field)) ? $field['type'] : $sub_slug; ?>"/>
													<input type="hidden" name="cc_value[]" value="item_data__<?php echo (is_array($field)) ? $field['label'] : $field; ?>"/>
													<input type="hidden" name="cc_name[]" value="<?php echo (is_array($field)) ? $field['name'] : $field;?>"/>
													<input type="hidden" name="cc_settings[]" value=""/>
												</div>
											</li>
											<?php
											$i++;												
										}																		
										?>
									</ul>
								</li>
								<?php
							}
						}

						?>
					</ul>
				</div>									
				<?php
				endif;
			endforeach;
		}

		public function render_new_field(){

			if ( ! self::$is_active ) return;

			?>
			<select class="wp-all-export-chosen-select" name="column_value_type" style="width:350px;">
				<?php
				foreach (self::$order_sections as $slug => $section) :
					?>										
					<optgroup label="<?php echo $section['title']; ?>">					
						<?php
						foreach ($section['meta'] as $cur_meta_key => $field) 
						{
							$field_label = is_array($field) ? $field['label'] : $cur_meta_key;
							$field_type = is_array($field) ? $field['type'] : 'woo_order';
							$field_name = is_array($field) ? $field['name'] : $field;
							$field_options = is_array($field) ? $field['options'] : $slug;								
							?>
							<option 
								value="<?php echo $field_type;?>" 
								label="<?php echo $field_label;?>" 									
								options="<?php echo $field_options; ?>"><?php echo $field_name;?></option>						
							<?php						
						}		
						?>
					</optgroup>
					<?php																
					if ( ! empty($section['additional']) )
					{
						foreach ($section['additional'] as $sub_slug => $sub_section) 
						{
							?>
							<optgroup label="<?php echo $sub_section['title']; ?>">		
													
								<?php foreach ($sub_section['meta'] as $field): ?>
									
									<?php
									$field_label = 'item_data__' . ( is_array($field) ? $field['label'] : $field );
									$field_type = is_array($field) ? $field['type'] : $sub_slug;
									$field_name = is_array($field) ? $field['name'] : $field;
									$field_options = 'item_data';
									?>
									<option 
										value="<?php echo $field_type;?>" 
										label="<?php echo $field_label;?>" 											
										options="<?php echo $field_options; ?>"><?php echo $field_name;?></option>								
																											
								<?php endforeach; ?>		

							</optgroup>
							<?php
						}
					}

				endforeach;

				// Render Available ACF
				XmlExportEngine::$acf_export->render_new_field();	

				?>
				
				<optgroup label="Advanced">
					<option value="sql" label="sql"><?php _e("SQL Query", "wp_all_export_plugin"); ?></option>					
				</optgroup>

			</select>
			<?php
		}

		public function render_filters(){
			
			if ( ! self::$is_active ) return;
			
			foreach (self::$order_sections as $slug => $section) :
				?>										
				<optgroup label="<?php echo $section['title']; ?>">					
					<?php
					foreach ($section['meta'] as $cur_meta_key => $field) 
					{
						$field_label = is_array($field) ? $field['label'] : $cur_meta_key;
						$field_type = is_array($field) ? $field['type'] : 'woo_order';
						$field_name = is_array($field) ? $field['name'] : $field;
						$field_options = is_array($field) ? $field['options'] : $slug;		

						switch ($field_options) 
						{				
							case 'order':
							case 'customer':								
								if ( strpos($field_label, '_') === 0):
								?>
								<option value="<?php echo 'cf_' . $field_label; ?>"><?php echo $field_name; ?></option>
								<?php
								else:
								?>
								<option value="<?php echo $field_label; ?>"><?php echo $field_name; ?></option>
								<?php
								endif;
								break;
							case 'notes':
							case 'items':
							case 'taxes':
							case 'fees':
								break;							
							default:
								switch ($field_type) 
								{
									case 'cf':									
										?>
										<option value="<?php echo 'cf_' . $field_label; ?>"><?php echo $field_name; ?></option>							
										<?php
										break;
									case 'cats':
									case 'attr':
										?>
										<option value="<?php echo 'tx_' . $field_label; ?>"><?php echo $field_name; ?></option>							
										<?php
										break;
									default:
										?>
										<option value="<?php echo  $field_label; ?>"><?php echo $field_name; ?></option>
										<?php
										break;
								}								
								break;
						}																							
					}		
					?>
				</optgroup>
				<?php																					

			endforeach;		

		}

		public function available_sections(){

			$sections = array(
				'order'    => array(
					'title' => __('Order', 'wp_all_export_plugin'),
					'meta'  => $this->available_order_data()
				),
				'customer' => array(
					'title' => __('Customer', 'wp_all_export_plugin'),
					'meta'  => $this->available_customer_data()
				),
				'items'    => array(
					'title' => __('Items', 'wp_all_export_plugin'),
					'meta'  => $this->available_order_default_product_data(),
					'additional' => $this->available_order_items_data()
				),
				'taxes'    => array(
					'title' => __('Taxes & Shipping', 'wp_all_export_plugin'),
					'meta'  => $this->available_order_taxes_data()
				),
				'fees'     => array(
					'title' => __('Fees & Discounts', 'wp_all_export_plugin'),
					'meta'  => $this->available_order_fees_data()
				),
				'notes'    => array(
					'title' => __('Notes', 'wp_all_export_plugin'),
					'meta' => array(
						'comment_content' 	 => __('Note Content', 'wp_all_export_plugin'),
						'comment_date'  	 => __('Note Date', 'wp_all_export_plugin'),
						'visibility' => __('Note Visibility', 'wp_all_export_plugin'),
						'comment_author'   => __('Note User Name', 'wp_all_export_plugin'),
						'comment_author_email'  => __('Note User Email', 'wp_all_export_plugin')
					)
				),
				'cf'       => array(
					'title' => __('Custom Fields', 'wp_all_export_plugin'),
					'meta'  => array()
				),
				'other'       => array(
					'title' => __('Other', 'wp_all_export_plugin'),
					'meta'  => array()
				)
			);

			return apply_filters('wp_all_export_available_order_sections_filter', $sections);

		}

		/*
		 * Define the keys for orders informations to export
		 */
		public function available_order_data()
		{
			$data = array(			   
				'ID' 					=> __('Order ID', 'wp_all_export_plugin'),
				'_order_key' 			=> __('Order Key', 'wp_all_export_plugin'),				
				'post_date' 			=> __('Order Date', 'wp_all_export_plugin'),
				'_completed_date' 		=> __('Completed Date', 'wp_all_export_plugin'),
				'post_title' 			=> __('Title', 'wp_all_export_plugin'),
				'post_status' 			=> __('Order Status', 'wp_all_export_plugin'),
				'_order_currency' 		=> __('Order Currency', 'wp_all_export_plugin'),				
				'_payment_method_title' => __('Payment Method', 'wp_all_export_plugin'),
				'_order_total' 			=> __('Order Total', 'wp_all_export_plugin')
			);
				
			return apply_filters('wp_all_export_available_order_data_filter', $data);
		}

		/*
		 * Define the keys for general product informations to export
		 */
		public function available_order_default_product_data()
		{			

			$data = array(
				'_product_id'  			=> __('Product ID', 'wp_all_export_plugin'),
				'__product_sku' 		=> __('SKU', 'wp_all_export_plugin'),
				'__product_title' 		=> __('Product Name', 'wp_all_export_plugin'),
				'__product_variation' 	=> __('Product Variation Details', 'wp_all_export_plugin'),
				'_qty' 					=> __('Quantity', 'wp_all_export_plugin'),
				'_line_subtotal' 		=> __('Item Cost', 'wp_all_export_plugin'),
				'_line_total' 			=> __('Item Total', 'wp_all_export_plugin')
			);			

			return apply_filters('wp_all_export_available_order_default_product_data_filter', $data);
		}

			public function available_order_items_data()
			{			
				
				$data = XmlExportEngine::$woo_export->get_all_fields_for_order_items();

				return apply_filters('wp_all_export_available_order_additional_product_data_filter', $data);
			}

		public function available_order_taxes_data(){
			
			$data = array(
				'tax_order_item_name'  		=> __('Rate Code (per tax)', 'wp_all_export_plugin'),
				'tax_rate' 					=> __('Rate Percentage (per tax)', 'wp_all_export_plugin'),
				'tax_amount' 				=> __('Amount (per tax)', 'wp_all_export_plugin'),
				'_order_tax' 				=> __('Total Tax Amount', 'wp_all_export_plugin'),
				'shipping_order_item_name' 	=> __('Shipping Method', 'wp_all_export_plugin'),
				'_order_shipping' 			=> __('Shipping Cost', 'wp_all_export_plugin')
			);

			return apply_filters('wp_all_export_available_order_default_taxes_data_filter', $data);
		}

		public function available_order_fees_data(){

			$data = array(
				'discount_amount'  		=> __('Discount Amount (per coupon)', 'wp_all_export_plugin'),
				'__coupons_used' 		=> __('Coupons Used', 'wp_all_export_plugin'),
				'_cart_discount' 		=> __('Total Discount Amount', 'wp_all_export_plugin'),
				'fee_line_total' 		=> __('Fee Amount (per surcharge)', 'wp_all_export_plugin'),
				'__total_fee_amount' 	=> __('Total Fee Amount', 'wp_all_export_plugin')				
			);

			return apply_filters('wp_all_export_available_order_fees_data_filter', $data);
		}

		public function available_customer_data()
		{
			
			$main_fields = array(
				'_customer_user' => __('Customer User ID', 'wp_all_export_plugin'),
				'post_excerpt'   => __('Customer Note', 'wp_all_export_plugin')				
			);

			$data = array_merge($main_fields, $this->available_billing_information_data(), $this->available_shipping_information_data());

			return apply_filters('wp_all_export_available_user_data_filter', $data);
		
		}

		public function available_billing_information_data()
		{
			
			$keys = array(
				'_billing_first_name',  '_billing_last_name', '_billing_company',
				'_billing_address_1', '_billing_address_2', '_billing_city',
				'_billing_postcode', '_billing_country', '_billing_state', 
				'_billing_email', '_billing_phone'
			);

			$data = $this->generate_friendly_titles($keys, 'billing');

			return apply_filters('wp_all_export_available_billing_information_data_filter', $data);
		
		}

		public function available_shipping_information_data()
		{
			
			$keys = array(
				'_shipping_first_name', '_shipping_last_name', '_shipping_company', 
				'_shipping_address_1', '_shipping_address_2', '_shipping_city', 
				'_shipping_postcode', '_shipping_country', '_shipping_state'
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
				
				$data[$key] = __(trim($key1), 'woocommerce').$key2;	
										
			}
			return $data;
		}

		public static function prepare_import_template( $exportOptions, &$templateOptions, $element_name, $ID )
		{			
			
			if ( ! self::$is_active ) return;

			$options = $exportOptions;

			$element_type = $options['cc_value'][$ID];

			$is_xml_template = $options['export_to'] == 'xml';

			$implode_delimiter = ($options['delimiter'] == ',') ? '|' : ',';		

			switch ($element_type) 
			{
				case 'ID':
					$templateOptions['unique_key'] = '{'. $element_name .'[1]}';
					$templateOptions['tmp_unique_key'] = '{'. $element_name .'[1]}';
					break;

				case 'post_status':
					$templateOptions['is_update_status'] = 1;
					$templateOptions['pmwi_order']['status'] = 'xpath';	
					$templateOptions['pmwi_order']['status_xpath'] = '{'. $element_name .'[1]}';	
					break;

				case 'post_date':
					$templateOptions['is_update_dates'] = 1;					
					$templateOptions['pmwi_order']['date'] = '{'. $element_name .'[1]}';						
					break;

				case '_billing_email':
					$templateOptions['pmwi_order']['billing_source_match_by'] = 'email';
					$templateOptions['pmwi_order']['billing_source_email'] = '{'. $element_name .'[1]}';	
					$templateOptions['pmwi_order']['is_update_billing_details'] = 1;
					$templateOptions['pmwi_order']['is_update_shipping_details'] = 1;
					break;

				case 'post_excerpt':
					$templateOptions['is_update_excerpt'] = 1;
					$templateOptions['pmwi_order']['customer_provided_note'] = '{'. $element_name .'[1]}';	
					break;

				case '_transaction_id':
					$templateOptions['pmwi_order']['transaction_id'] = '{'. $element_name .'[1]}';	
					break;

				case '_payment_method':
					$templateOptions['pmwi_order']['payment_method'] = 'xpath';
					$templateOptions['pmwi_order']['payment_method_xpath'] = '{'. $element_name .'[1]}';	
					$templateOptions['pmwi_order']['is_update_payment'] = 1;
					break;

				case '__product_sku':
					$templateOptions['pmwi_order']['is_update_products'] = 1;
					$templateOptions['pmwi_order']['products_repeater_mode'] = $options['export_to'];
					if ($is_xml_template)
					{
						$templateOptions['pmwi_order']['products_repeater_mode_foreach'] = '{OrderItems[1]/Item}';
						$templateOptions['pmwi_order']['products'][0]['sku'] = '{'. $element_name .'[1]}';	
					}
					break;

				case '_qty':
					if ($is_xml_template)
					{						
						$templateOptions['pmwi_order']['products'][0]['qty'] = '{'. $element_name .'[1]}';	
					}
					break;

				// prepare template for fee line items
				case 'fee_line_total':
					$templateOptions['pmwi_order']['is_update_fees'] = 1;
					$templateOptions['pmwi_order']['fees_repeater_mode'] = $options['export_to'];
					if ($is_xml_template)
					{	
						$templateOptions['pmwi_order']['fees_repeater_mode_foreach'] = '{OrderSurcharge[1]/Surcharge}';
						$templateOptions['pmwi_order']['fees'][0]['name'] = '{fee_name[1]}';	
						$templateOptions['pmwi_order']['fees'][0]['amount'] = '{fee_amount[1]}';	
					}
					break;

				// prepare template for coupon line items
				case 'discount_amount':
					$templateOptions['pmwi_order']['is_update_coupons'] = 1;
					$templateOptions['pmwi_order']['coupons_repeater_mode'] = $options['export_to'];
					if ($is_xml_template)
					{	
						$templateOptions['pmwi_order']['coupons_repeater_mode_foreach'] = '{OrderCoupons[1]/Coupon}';
						$templateOptions['pmwi_order']['coupons'][0]['code'] = '{coupon_code[1]}';	
						$templateOptions['pmwi_order']['coupons'][0]['amount'] = '{discount_amount[1]}';	
						$templateOptions['pmwi_order']['coupons'][0]['amount_tax'] = '';	
					}					
					break;

				// prepare template for shipping line items
				case 'shipping_order_item_name':
					$templateOptions['pmwi_order']['is_update_shipping'] = 1;
					$templateOptions['pmwi_order']['shipping_repeater_mode'] = $options['export_to'];
					if ($is_xml_template)
					{	
						$templateOptions['pmwi_order']['shipping_repeater_mode_foreach'] = '{OrderShipping[1]/Shipping}';
						$templateOptions['pmwi_order']['shipping'][0]['name'] = '{'. $element_name.'[1]}';							
						$templateOptions['pmwi_order']['shipping'][0]['class'] = 'xpath';	
						$templateOptions['pmwi_order']['shipping'][0]['class_xpath'] = '{'. $element_name .'}';	
					}	
					break;

				case '_order_shipping':
					$templateOptions['pmwi_order']['shipping'][0]['amount'] = '{'. $element_name .'[1]}';	
					break;

				// prepare template for tax line items
				case 'tax_order_item_name':	
					$templateOptions['pmwi_order']['is_update_taxes'] = 1;
					$templateOptions['pmwi_order']['taxes_repeater_mode'] = $options['export_to'];
					if ($is_xml_template)
					{	
						$templateOptions['pmwi_order']['taxes_repeater_mode_foreach'] = '{OrderTaxes[1]/Tax}';						
						$templateOptions['pmwi_order']['taxes'][0]['shipping_tax_amount'] = '';
						$templateOptions['pmwi_order']['taxes'][0]['code'] = 'xpath';	
						$templateOptions['pmwi_order']['taxes'][0]['code_xpath'] = '{'. $element_name .'[1]}';	
					}

					// $tax_data[$element_name] = pmxe_filter( $order_tax->order_item_name, $TaxesfieldSnipped);															
					break;

				case 'tax_rate':
					// $tax_data[$element_name] = pmxe_filter(( ! empty($rate_details)) ? $rate_details->tax_rate : '', $TaxesfieldSnipped);					
					break;

				case 'tax_amount':
					$templateOptions['pmwi_order']['taxes'][0]['tax_amount'] = '{'. $element_name.'[1]}';							
					break;

				// order notes
				case 'comment_content':
					$templateOptions['pmwi_order']['is_update_notes'] = 1;
					$templateOptions['pmwi_order']['order_note_content'] = '{'. $element_name .'[1]}';	
					break;

				case 'comment_date':
					$templateOptions['pmwi_order']['order_note_date'] = '{'. $element_name .'[1]}';	
					break;

				case 'visibility':
					$templateOptions['pmwi_order']['order_note_visibility'] = '{xpath[1]}';	
					$templateOptions['pmwi_order']['order_note_visibility_xpath'] = '{'. $element_name .'[1]}';	
					break;

				case 'comment_author':
					$templateOptions['pmwi_order']['order_note_author'][0]['username'] = '{'. $element_name .'[1]}';	
					break;
					
				case 'comment_author_email':
					$templateOptions['pmwi_order']['order_note_author'][0]['email'] = '{'. $element_name .'[1]}';	
					break;
			}

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