<?php
class Advanced_Recent_Posts_Widget_widgetized extends WP_Widget {
	public function __construct() {
		parent::__construct(
	 		'advrecent', // Base ID
			'Advanced Recent Posts', // Name
			array( 'description' => __( 'Shows recent posts by category' ), ) // Args
		);
	}
    ///START WIDGET
	function widget($args, $advposts_instance) {
           
			extract( $args );
		
			$title = apply_filters( 'widget_title', empty($advposts_instance['title']) ? 'Recent Posts' : $advposts_instance['title'], $advposts_instance, $this->id_base);	
			$show_date = isset( $advposts_instance['show_date'] ) ? $advposts_instance['show_date'] : false;
			$show_image = isset( $advposts_instance['show_image'] ) ? $advposts_instance['show_image'] : false;
			$numCols = isset( $advposts_instance['numCols'] ) ? $advposts_instance['numCols'] : false;
			if ( ! $number = absint( $advposts_instance['number'] ) ) $number = 5;
			if( ! $cats = $advposts_instance["cats"] )  $cats='';
					
			// array to call recent posts.
			
			$advposts_args=array(
						   
				'showposts' => $number,
				'category__in'=> $cats,
				);
			
			$advposts_widget = null;
			$advposts_widget = new WP_Query($advposts_args);
			echo '<style>',
			'#adv-recent-posts .advposts-image.advposts-img-width-'.absint($advposts_instance["image_size"]).' img{width:'.$advposts_instance["image_size"].'px;}',
			'</style>';
			echo $before_widget;
			echo '<div id="adv-recent-posts" class="widget-inner">';

			
			// Widget title
			if ($advposts_instance["title"]!=''){
				echo $before_title;
				echo $advposts_instance["title"];
				echo $after_title;
			}
			
			// Post list in widget
			echo "<ul class='row'>\n";
			
		while ( $advposts_widget->have_posts() )
		{
			$advposts_widget->the_post();
		?>
		<?php if ( $numCols ){ ?>
		<li class="advwidget-item col-xs-6">
		<?php } else {?>
		<li class="advwidget-item col-xs-12">
		<?php }?>
		<?php if ( $show_image ) : ?>
		<a  href="<?php the_permalink(); ?>">
			<div class="advposts-image advposts-img-width-<?php echo absint($advposts_instance['image_size']);?>">
				<?php 
				if (absint($advposts_instance["image_size"]) >= 300){
				 echo get_the_post_thumbnail($post->ID,'medium'); 
				}
				else{
				 echo get_the_post_thumbnail($post->ID,'thumbnail'); 
				}
				?>
			</div>
		</a>
		<?php endif; ?>
			<div class="advposts-content
			<?php if ( $show_image ) :
			echo 'advposts-hasimage';
			endif; ?>
			">
				<a  href="<?php the_permalink(); ?>" class="advposts-title"><?php the_title(); ?></a>
				<?php if ( $show_date ) : ?>
				<span class="advposts-date"><?php echo "(".get_the_date().")"; ?></span>
			<?php endif; ?>
		</div>
		
			</li>
		<?php

		}

		 wp_reset_query();

		echo "</ul>\n";
		echo '</div>';
		echo $after_widget;
		

	}
	
	function update( $new_instance, $old_instance ) {
		$advposts_instance = $old_instance;
		$advposts_instance['title'] = strip_tags($new_instance['title']);
        	$advposts_instance['cats'] = $new_instance['cats'];
		$advposts_instance['number'] = absint($new_instance['number']);
		$advposts_instance['show_date'] = (bool) $new_instance['show_date'];
		$advposts_instance['show_image'] = (bool) $new_instance['show_image'];
		$advposts_instance['image_size']  = absint( $new_instance['image_size'] );
		$advposts_instance['numCols'] = (bool) $new_instance['numCols'];
	     
        		return $advposts_instance;
	}
	
	function form( $advposts_instance ) {
		$title = isset($advposts_instance['title']) ? esc_attr($advposts_instance['title']) : 'Recent Posts';
		$number = isset($advposts_instance['number']) ? absint($advposts_instance['number']) : 5;
		$show_date = isset( $advposts_instance['show_date'] ) ? (bool) $advposts_instance['show_date'] : false;
		$show_image = isset( $advposts_instance['show_image'] ) ? (bool) $advposts_instance['show_image'] : false;
		$image_size = isset($advposts_instance['image_size']) ? absint($advposts_instance['image_size']) : 50;
		$numCols = isset( $advposts_instance['numCols'] ) ? (bool) $advposts_instance['numCols'] : false;
		
?>
        <style>
        .list_all_recentposts.advwidgetlist{
        	max-height:220px;
        	overflow-y:auto;
        	padding-bottom:10px;
        }
        .list_all_recentposts.advwidgetlist .list_recentposts_input{
        	margin:7px 0;
        }
        </style>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
                        
        <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:'); ?></label>
        <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
        
        <p><input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
	<label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display post date?' ); ?></label></p>

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
            <label for="<?php echo $this->get_field_id('cats'); ?>"><?php _e('Select categories to include in the recent posts list:');?> 
            
                <?php
                   $categories=  get_categories('hide_empty=0');
					 echo '<div class="list_all_recentposts advwidgetlist" style="max-height:220px;overflow-y:auto;padding-bottom:10px;">';
                     foreach ($categories as $cat) {
                         $option='<div class="list_recentposts_input"><input type="checkbox" id="'. $this->get_field_id( 'cats' ) .'[]" name="'. $this->get_field_name( 'cats' ) .'[]"';
                            if (is_array($advposts_instance['cats'])) {
                                foreach ($advposts_instance['cats'] as $cats) {
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
add_action('widgets_init', create_function('', 'return register_widget("Advanced_Recent_Posts_Widget_widgetized");'));
?>
