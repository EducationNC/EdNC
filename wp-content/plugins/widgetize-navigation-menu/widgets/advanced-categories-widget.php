<?php
class Advanced_Categories_Widget_widgetized extends WP_Widget {
	public function __construct() {
		parent::__construct(
	 		'advcat', // Base ID
			'Advanced Categories', // Name
			array( 'description' => __( 'List specific Categories' ), ) // Args
		);
	}
    ///START WIDGET
	function widget($args, $advcat_instance) {
           
			extract( $args );
		
			$title = apply_filters( 'widget_title', empty($advcat_instance['title']) ? 'Categories' : $advcat_instance['title'], $advcat_instance, $this->id_base);	
			$numCols = isset( $advcat_instance['numCols'] ) ? $advcat_instance['numCols'] : false;
						
			if( ! $cats = $advcat_instance["cats"] )  $cats='';
					
			$advcat_widget = null;
			$advcat_widget = new WP_Query($advcat_args);
			echo $before_widget;
			echo '<div id="adv-recent-cats" class="widget-inner">';

			
			// Widget title
			if ($advcat_instance["title"]!=''){
				echo $before_title;
				echo $advcat_instance["title"];
				echo $after_title;
			}
			
			// Post list in widget
			if($cats!=''){
			echo "<ul class='row'>\n";
			
			foreach ( $cats as $cat ){
				if ( $numCols ){
				echo '<li class="advwidget-item col-xs-6">';
				}
				else {
				echo '<li class="advwidget-item col-xs-12">';
				}
		    	// Get the ID of a given category
		    	$category_id = $cat;

		    	// Get the URL of this category
		    	$category_link = get_category_link( $category_id );

		    	// Get te Name of this category
		    	$category_name = get_cat_name( $category_id );
				?>
				<!-- Print a link to this category -->
				<a href="<?php echo esc_url( $category_link ); ?>"><?php echo $category_name;?></a>
				</li>
			<?php }//end for each?>
		<?php //endif; ?>	
		<?php
		 wp_reset_query();

		echo "</ul>\n";
		}//end if array not empty
		echo '</div>';
		echo $after_widget;
	}
	
	function update( $new_instance, $old_instance ) {
		$advcat_instance = $old_instance;
		$advcat_instance['title'] = strip_tags($new_instance['title']);
        $advcat_instance['cats'] = $new_instance['cats'];
		$advcat_instance['numCols'] = (bool) $new_instance['numCols'];
	     
        		return $advcat_instance;
	}
	
	function form( $advcat_instance ) {
		$title = isset($advcat_instance['title']) ? esc_attr($advcat_instance['title']) : 'Categories';
		$numCols = isset( $advcat_instance['numCols'] ) ? (bool) $advcat_instance['numCols'] : false;		
?>
        <style>
        .list_all_categories.advwidgetlist{
        	max-height:220px;
        	overflow-y:auto;
        	padding-bottom:10px;
        }
        .list_all_categories.advwidgetlist .list_cat_input{
        	margin:7px 0;
        }
        </style>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
                                
	 <p><input class="checkbox" type="checkbox" <?php checked( $numCols ); ?> id="<?php echo $this->get_field_id( 'numCols' ); ?>" name="<?php echo $this->get_field_name( 'numCols' ); ?>" />
	<label for="<?php echo $this->get_field_id( 'numCols' ); ?>"><?php _e( 'Split into 2 columns?' ); ?></label></p>
        
         <p>
            <label for="<?php echo $this->get_field_id('cats'); ?>"><?php _e('Categories to list:');?> 
            
                <?php
                   $categories=  get_categories('hide_empty=0');
                    echo '<div class="list_all_categories advwidgetlist">';

                     foreach ($categories as $cat) {
                         $option='<div class="list_cat_input"><input type="checkbox" id="'. $this->get_field_id( 'cats' ) .'[]" name="'. $this->get_field_name( 'cats' ) .'[]"';
                            if (is_array($advcat_instance['cats'])) {
                                foreach ($advcat_instance['cats'] as $cats) {
                                    if($cats==$cat->term_id) {
                                         $option=$option.' checked="checked"';
                                    }
                                }
                            }
                            $option .= ' value="'.$cat->term_id.'" />';
			    $option .= '&nbsp;';
                            $option .= $cat->cat_name;
                            $option .= '<br />';
                            echo $option;
                            echo '</div>';
                         }
                         echo '</div>';                    
                    ?>
            </label>
        </p>
        
<?php
	}
}
/*=============================================================
	END THE WIDGET
* ============================================================= */
add_action('widgets_init', create_function('', 'return register_widget("Advanced_Categories_Widget_widgetized");'));
?>
