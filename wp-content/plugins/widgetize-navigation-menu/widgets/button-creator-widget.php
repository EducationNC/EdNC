<?php
class Button_Creator_Widget_widgetized extends WP_Widget {
	public function __construct() {
		parent::__construct(
	 		'advbuttonwdgt', // Base ID
			'Advanced Button Widget', // Name
			array( 'description' => __( 'Create a button' ), ) // Args
		);
	}
 /* =============================================================
    DISPLAY THE WIDGET
* =============================================================*/
    function widget($args, $mybut_instance) {	
       extract( $args );
      ?>
<!--start container-->
<?php 
   $title = apply_filters( 'widget_title', empty($mybut_instance['title']) ? 'Categories' : $mybut_instance['title'], $mybut_instance, $this->id_base); 
   $mybut_widget_showaslink = isset( $mybut_instance['mybut_widget_showaslink'] ) ? $mybut_instance['mybut_widget_showaslink'] : false;

  //START WIDGET      
      echo $before_widget;
      echo '<div id="button_creator_widget" class="widget-inner">';
      // Widget title
      if ($mybut_instance["title"]!=''){
        echo $before_title;
        echo $mybut_instance["title"];
        echo $after_title;
      }
      //IMAGE
        if(!empty($mybut_instance['mybut_widget_buttonlink'])){
          echo '<a class="button-creator-imagelink" href="'.$mybut_instance['mybut_widget_buttonlink'].'">';
        }
        if(!empty($mybut_instance['mybut_widget_buttonimage'])){?>
          <img width="<?php if($mybut_instance["mybut_widget_imagesize"]==""){echo '300';}else{ echo $mybut_instance['mybut_widget_imagesize'];}?>" 
        src="<?php echo $mybut_instance['mybut_widget_buttonimage'];?>"/>
        <?php }
         if(!empty($mybut_instance['mybut_widget_buttonlink'])){
            echo '</a>';
          }
      //LINK
      if($mybut_widget_showaslink=='on' && $mybut_instance['mybut_widget_buttontext']!='' && !empty($mybut_instance['mybut_widget_buttonlink'])){
          echo '<a class="button-creator-textlink" href="'.$mybut_instance['mybut_widget_buttonlink'].'" style="color:'.$mybut_instance['mybut_widget_buttoncolor'].';">'.$mybut_instance['mybut_widget_buttontext'].'</a>';
      }
      //BUTTON
      else if($mybut_widget_showaslink=='' && $mybut_instance['mybut_widget_buttontext']!='' && !empty($mybut_instance['mybut_widget_buttonlink'])){
       		echo '<a class="button-creator-buttoncoloredlink" href="'.$mybut_instance['mybut_widget_buttonlink'].'" type="button" style="background-color:'.$mybut_instance['mybut_widget_buttoncolor'].';">'.$mybut_instance['mybut_widget_buttontext'].'</a>';
      }
      echo '</div>';
      echo $after_widget;
    }//end function widget
/*=============================================================
	END THE WIDGET
* ============================================================= */

    //UPDATE VARS FROM FORM
    function update( $new_mybut_instance, $old_mybut_instance ) {

	  $mybut_instance = $old_mybut_instance;
    $mybut_instance['title']      = strip_tags( $new_mybut_instance['title'] );
    $mybut_instance['mybut_widget_buttonimage']      = strip_tags( $new_mybut_instance['mybut_widget_buttonimage'] );
    $mybut_instance['mybut_widget_imagesize']      = strip_tags( $new_mybut_instance['mybut_widget_imagesize'] );
		$mybut_instance['mybut_widget_buttontext']      = strip_tags( $new_mybut_instance['mybut_widget_buttontext'] );
		$mybut_instance['mybut_widget_buttoncolor']      = strip_tags( $new_mybut_instance['mybut_widget_buttoncolor'] );
		$mybut_instance['mybut_widget_buttonlink']      = strip_tags( $new_mybut_instance['mybut_widget_buttonlink'] );	
    $mybut_instance['mybut_widget_showaslink']      =  $new_mybut_instance['mybut_widget_showaslink']; 

		return $mybut_instance;
    }
    function form( $mybut_instance ) {
      $title = isset($mybut_instance['title']) ? esc_attr($mybut_instance['title']) : 'My Title';
      // Default vars in form
		  $mybut_instance = wp_parse_args( (array) $mybut_instance, array(
      'mybut_widget_buttonimage' => '',
      'mybut_widget_imagesize' => '300',
			'mybut_widget_buttontext' => '',
			'mybut_widget_buttoncolor' => '',
			'mybut_widget_buttonlink' => '',
		));
      $mybut_widget_buttonimage = esc_attr($mybut_instance['mybut_widget_buttonimage']);
      $mybut_widget_imagesize = esc_attr($mybut_instance['mybut_widget_imagesize']);
     	$mybut_widget_buttontext = esc_attr($mybut_instance['mybut_widget_buttontext']);
		  $mybut_widget_buttoncolor = esc_attr($mybut_instance['mybut_widget_buttoncolor']);
      $mybut_widget_buttonlink = esc_attr($mybut_instance['mybut_widget_buttonlink']);
      $mybut_widget_showaslink = (bool) $mybut_instance['mybut_widget_showaslink'];
		?>  
        <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('mybut_widget_buttonimage'); ?>"><?php _e('Image:'); ?></label><br/>
          <?php echo '<img class="button_creator_img" src="'.$mybut_widget_buttonimage.'" width="200"/><br/>';?>  
          <input type="text" class="button_creator_img_input" id="<?php echo $this->get_field_id('mybut_widget_buttonimage'); ?>" name="<?php echo $this->get_field_name('mybut_widget_buttonimage'); ?>" value="<?php echo $mybut_widget_buttonimage; ?>" /><br/>
          <input type="button" class="button btnctr_meta-button" value="<?php _e( 'Select Image', 'prfx-textdomain' )?>" />
          <br/>
          <a class="delete_button_creator_img" style="cursor:pointer;">Remove Image</a>
        </p>  
        <p>
          <label for="<?php echo $this->get_field_id('mybut_widget_imagesize'); ?>"><?php _e('Image Width (in pixels):'); ?></label>
          <input size="5" id="<?php echo $this->get_field_id('mybut_widget_imagesize'); ?>" name="<?php echo $this->get_field_name('mybut_widget_imagesize'); ?>" type="text" value="<?php if (empty($mybut_widget_imagesize)){echo '300';}else {echo $mybut_widget_imagesize;} ?>" />
          <span>px</span>
        </p>          
        <p>
          <label for="<?php echo $this->get_field_id('mybut_widget_buttonlink'); ?>"><?php _e('Link (required):'); ?></label>
          <input id="<?php echo $this->get_field_id('mybut_widget_buttonlink'); ?>" name="<?php echo $this->get_field_name('mybut_widget_buttonlink'); ?>" type="text" value="<?php echo $mybut_widget_buttonlink; ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('mybut_widget_buttontext'); ?>"><?php _e('Button Text:'); ?></label>
          <input id="<?php echo $this->get_field_id('mybut_widget_buttontext'); ?>" name="<?php echo $this->get_field_name('mybut_widget_buttontext'); ?>" type="text" value="<?php if (empty($mybut_widget_buttontext)){echo 'My Button';}else {echo $mybut_widget_buttontext;} ?>" />
        </p>
         <p>
          <label for="<?php echo $this->get_field_id('mybut_widget_buttoncolor'); ?>"><?php _e('Link Color:'); ?></label><br/>
          <input class="my-color-field" id="<?php echo $this->get_field_id('mybut_widget_buttoncolor'); ?>" name="<?php echo $this->get_field_name('mybut_widget_buttoncolor'); ?>" type="text" value="<?php if (empty($mybut_widget_buttoncolor)){echo '#abc261';} else {echo $mybut_widget_buttoncolor; }?>" />
        </p>
        <p>
          <input class="checkbox" type="checkbox" 
          <?php
          if ($mybut_instance['mybut_widget_showaslink']=='on') {
            echo "checked";
          } ?> 
           name="<?php echo $this->get_field_name('mybut_widget_showaslink'); ?>" />
          <label for="<?php echo $this->get_field_id( 'mybut_widget_showaslink' ); ?>"><?php _e( 'Show as text link' ); ?></label></p>
        </p>  
        <?php
    }
}
add_action('widgets_init', create_function('', 'return register_widget("Button_Creator_Widget_widgetized");'));