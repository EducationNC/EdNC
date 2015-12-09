<?php
class Advanced_Pages_Widget_widgetized extends WP_Widget {
	public function __construct() {
		parent::__construct(
	 		'advpages', // Base ID
			'Advanced Pages', // Name
			array( 'description' => __( 'List specific pages' ), ) // Args
		);
	}
    ///START WIDGET
	function widget($args, $advpage_instance) {
           
			extract( $args );
		
			$title = apply_filters( 'widget_title', empty($advpage_instance['title']) ? 'Pages' : $advpage_instance['title'], $advpage_instance, $this->id_base);	
			$numCols = isset( $advpage_instance['numCols'] ) ? $advpage_instance['numCols'] : false;
			$show_image = isset( $advpage_instance['show_image'] ) ? $advpage_instance['show_image'] : false;

						
			if( ! $pages = $advpage_instance["the_pages"] )  $pages='';
					
			$advpage_widget = null;
			$advpage_widget = new WP_Query($advpage_args);
			echo '<style>',
			'#adv-recent-pages .advpages-image.advpages-img-width-'.absint($advpage_instance["image_size"]).' img{width:'.$advpage_instance["image_size"].'px;}',
			'</style>';

			
			echo $before_widget;
			echo '<div id="adv-recent-pages" class="widget-inner">';
			
			// Widget title
			if ($advpage_instance["title"]!=''){
				echo $before_title;
				echo $advpage_instance["title"];
				echo $after_title;
			}
			
			// Post list in widget
			if($pages!=''){
			echo "<ul class='row'>\n";
			
			foreach ( $pages as $page ){
				if ( $numCols ){
				echo '<li class="advwidget-item col-xs-6">';
				}
				else {
				echo '<li class="advwidget-item col-xs-12">';
				}
		    	// Get the ID of a given category
		    	$page_id = $page;

		    	// Get the URL of this category
		    	$page_link = get_page_link( $page_id );

		    	// Get te Name of this category
		    	$page_name = get_the_title( $page_id );
				?>
				<!-- Print a link to this category -->
				<?php if ( $show_image ) : ?>
				<a  href="<?php echo esc_url( $page_link ); ?>">
					<div class="advpages-image advpages-img-width-<?php echo absint($advpage_instance['image_size']);?>">
						<?php 
						if (absint($advpage_instance["image_size"]) <= 150){
						 echo get_the_post_thumbnail($page_id,'thumbnail'); 
						}
						else if (absint($advpage_instance["image_size"]) <= 300){
						 echo get_the_post_thumbnail($page_id,'medium'); 
						}
						else{
						 echo get_the_post_thumbnail($page_id,'large'); 
						}
					?>
					</div>
				</a>
				<?php endif; ?>
				<div class="advpages-content
				<?php if ( $show_image ) :
				echo 'advpages-hasimage';
				endif; ?>
				">
				<a href="<?php echo esc_url( $page_link ); ?>"><?php echo $page_name;?></a>
				</div>


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
		$advpage_instance = $old_instance;
		$advpage_instance['title'] = strip_tags($new_instance['title']);
        $advpage_instance['the_pages'] = $new_instance['the_pages'];
        $advpage_instance['show_image'] = (bool) $new_instance['show_image'];
		$advpage_instance['image_size']  = absint( $new_instance['image_size'] );
		$advpage_instance['numCols'] = (bool) $new_instance['numCols'];
	     
        		return $advpage_instance;
	}
	
	function form( $advpage_instance ) {
		$title = isset($advpage_instance['title']) ? esc_attr($advpage_instance['title']) : 'Pages';
		$numCols = isset( $advpage_instance['numCols'] ) ? (bool) $advpage_instance['numCols'] : false;	
		$show_image = isset( $advpage_instance['show_image'] ) ? (bool) $advpage_instance['show_image'] : false;
		$image_size = isset($advpage_instance['image_size']) ? absint($advpage_instance['image_size']) : 50;	
?>
		<style>
        .list_all_pages.advwidgetlist{
        	max-height:220px;
        	overflow-y:auto;
        	padding-bottom:10px;
        }
        .list_all_pages.advwidgetlist .list_page_input{
        	margin:7px 0;
        }
        </style>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
        
        <p><input class="checkbox" type="checkbox" <?php checked( $show_image ); ?> id="<?php echo $this->get_field_id( 'show_image' ); ?>" name="<?php echo $this->get_field_name( 'show_image' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_image' ); ?>"><?php _e( 'Display featured image?' ); ?></label></p>

		 <p>
	        <label for="<?php echo $this->get_field_id('image_size'); ?>"><?php _e('Image Width (in pixels):'); ?></label>
	        <input size="5" id="<?php echo $this->get_field_id('image_size'); ?>" name="<?php echo $this->get_field_name('image_size'); ?>" type="text" value="<?php if (empty($image_size)){echo '50';}else {echo $image_size;} ?>" />
	        <span>px</span>
	     </p>                         
	 	
	 	<p><input class="checkbox" type="checkbox" <?php checked( $numCols ); ?> id="<?php echo $this->get_field_id( 'numCols' ); ?>" name="<?php echo $this->get_field_name( 'numCols' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'numCols' ); ?>"><?php _e( 'Split into 2 columns?' ); ?></label></p>
        
         <p>
            <label for="<?php echo $this->get_field_id('the_pages'); ?>"><?php _e('Pages to list:');?> 
            
                <?php
                $pages_args = array(
					'sort_order' => 'ASC',
					'sort_column' => 'post_title',
					'hierarchical' => 1,
					'child_of' => 0,
					'parent' => -1,
					'post_type' => 'page',
					'post_status' => 'publish'
				); 
                   $pages=  get_pages($pages_args);
                     
                     echo '<div class="list_all_pages advwidgetlist" style="max-height:220px;overflow-y:auto;padding-bottom:10px;">';
                     foreach ($pages as $page) {

                         $option='<div class="list_page_input"><input type="checkbox" id="'. $this->get_field_id( 'the_pages' ) .'[]" name="'. $this->get_field_name( 'the_pages' ) .'[]"';
                            if (is_array($advpage_instance['the_pages'])) {
                                foreach ($advpage_instance['the_pages'] as $pages) {
                                    if($pages==$page->ID) {
                                         $option=$option.' checked="checked"';
                                    }
                                }
                            }
                            $option .= ' value="'.$page->ID.'" />';
			    $option .= '&nbsp;';
                            $option .= $page->post_title;
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
add_action('widgets_init', create_function('', 'return register_widget("Advanced_Pages_Widget_widgetized");'));
?>
