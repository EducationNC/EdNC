<?php

/*
Plugin Name: Tiled Galleries Carousel Without Jetpack
Plugin URL: http://themepacific.com
Description: Transform your standard image galleries into an immersive full-screen experience without Jetpack.This plugin is made from Jetpack Modules. You can get the tiled galleries with Full screen carousel with out connecting to wordpress.com account.
Version: 2.1
Author: Raja CRN
Author URI: http://themepacific.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/
/* */

// Include the class file containing methods for rounding constrained array elements.
// Here the constrained array element is the dimension of a row, group or an image in the tiled gallery.
include_once dirname( __FILE__ ) . '/math/class-constrained-array-rounding.php';
include( plugin_dir_path( __FILE__ ) . 'class.jetpack-user-agent.php');
include( plugin_dir_path( __FILE__ ) . 'functions.gallery.php');
include( plugin_dir_path( __FILE__ ) . 'jetpack-carousel.php');
load_plugin_textdomain('themepacific_gallery', false, basename( dirname( __FILE__ ) ) . '/languages' );
class themePacific_Jetpack_Tiled_Gallery {

	public function __construct() {
		add_action( 'admin_init', array( $this, 'settings_api_init' ) );
		add_filter( 'jetpack_gallery_types', array( $this, 'jetpack_gallery_types' ), 9 );
		add_filter( 'jetpack_default_gallery_type', array( $this, 'jetpack_default_gallery_type' ) );
	}

	public function tiles_enabled() {
		// Check the setting status
		return '' != get_option( 'tiled_galleries' );
	}

	public function set_atts( $atts ) {
		global $post;

		$this->atts = shortcode_atts( array(
			'order'      => 'ASC',
			'orderby'    => 'menu_order ID',
			'id'         => $post->ID,
			'include'    => '',
			'exclude'    => '',
			'type'       => '',
			'grayscale'  => false,
			'link'       => '',
		), $atts );

		$this->atts['id'] = (int) $this->atts['id'];
		$this->float = is_rtl() ? 'right' : 'left';

		// Default to rectangular is tiled galleries are checked
		if ( $this->tiles_enabled() && ( ! $this->atts['type'] || 'default' == $this->atts['type'] ) )
			$this->atts['type'] = 'rectangular';

		if ( !$this->atts['orderby'] ) {
			$this->atts['orderby'] = sanitize_sql_orderby( $this->atts['orderby'] );
			if ( !$this->atts['orderby'] )
				$this->atts['orderby'] = 'menu_order ID';
		}

		if ( 'RAND' == $this->atts['order'] )
			$this->atts['orderby'] = 'none';
	}

	public function get_attachments() {
		extract( $this->atts );

		if ( !empty( $include ) ) {
			$include = preg_replace( '/[^0-9,]+/', '', $include );
			$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

			$attachments = array();
			foreach ( $_attachments as $key => $val ) {
				$attachments[$val->ID] = $_attachments[$key];
			}
		} elseif ( !empty( $exclude ) ) {
			$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
			$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
		} else {
			$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby ) );
		}
		return $attachments;
	}

	public function get_attachment_link( $attachment_id, $orig_file ) {
		if ( isset( $this->atts['link'] ) && $this->atts['link'] == 'file' )
			return $orig_file;
		else
			return get_attachment_link( $attachment_id );
	}

	public function default_scripts_and_styles() {
		wp_enqueue_script( 'tiled-gallery', plugins_url( 'tiled-gallery/tiled-gallery.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_style( 'tiled-gallery', plugins_url( 'tiled-gallery/tiled-gallery.css', __FILE__ ), array(), '2012-09-21' );
	}

	public function gallery_shortcode( $val, $atts ) {
		if ( ! empty( $val ) ) // something else is overriding post_gallery, like a custom VIP shortcode
			return $val;

		global $post;

		$this->set_atts( $atts );

		$attachments = $this->get_attachments();
		if ( empty( $attachments ) )
			return '';

		if ( is_feed() || defined( 'IS_HTML_EMAIL' ) )
			return '';

		if ( method_exists( $this, $this->atts['type'] . '_talavera' ) ) {
			// Enqueue styles and scripts
			$this->default_scripts_and_styles();
			$gallery_html = call_user_func_array( array( $this, $this->atts['type'] . '_talavera' ), array( $attachments ) );

			if ( $gallery_html && class_exists( 'Jetpack' ) && class_exists( 'Jetpack_Photon' ) ) {
				// Tiled Galleries in Jetpack require that Photon be active.
				// If it's not active, run it just on the gallery output.
				if ( ! in_array( 'photon', Jetpack::get_active_modules() ) )
					$gallery_html = Jetpack_Photon::filter_the_content( $gallery_html );
			}

			return $gallery_html;
		}

		return '';
	}
	
	public function vt_resize( $attach_id = null, $img_url = null, $width, $height, $crop = false ) {

	// this is an attachment, so we have the ID
	if ( $attach_id ) {
	
		$image_src = wp_get_attachment_image_src( $attach_id, 'full' );
		$file_path = get_attached_file( $attach_id );
	
	// this is not an attachment, let's use the image url
	} else if ( $img_url ) {
		
		$file_path = parse_url( $img_url );
		$file_path = ltrim( $file_path['path'], '/' );
		//$file_path = rtrim( ABSPATH, '/' ).$file_path['path'];
		
		$orig_size = getimagesize( $file_path );
		
		$image_src[0] = $img_url;
		$image_src[1] = $orig_size[0];
		$image_src[2] = $orig_size[1];
	}
	
	$file_info = pathinfo( $file_path );
	$extension = '.'. $file_info['extension'];

	// the image path without the extension
	$no_ext_path = $file_info['dirname'].'/'.$file_info['filename'];

	$cropped_img_path = $no_ext_path.'-'.$width.'x'.$height.$extension;

	// checking if the file size is larger than the target size
	// if it is smaller or the same size, stop right here and return
	if ( $image_src[1] > $width || $image_src[2] > $height ) {

		// the file is larger, check if the resized version already exists (for crop = true but will also work for crop = false if the sizes match)
		if ( file_exists( $cropped_img_path ) ) {

			$cropped_img_url = str_replace( basename( $image_src[0] ), basename( $cropped_img_path ), $image_src[0] );
			
			$vt_image = array (
				'url' => $cropped_img_url,
				'width' => $width,
				'height' => $height
			);
			
			return $vt_image;
		}

		// crop = false
		if ( $crop == false ) {
		
			// calculate the size proportionaly
			$proportional_size = wp_constrain_dimensions( $image_src[1], $image_src[2], $width, $height );
			$resized_img_path = $no_ext_path.'-'.$proportional_size[0].'x'.$proportional_size[1].$extension;			

			// checking if the file already exists
			if ( file_exists( $resized_img_path ) ) {
			
				$resized_img_url = str_replace( basename( $image_src[0] ), basename( $resized_img_path ), $image_src[0] );

				$vt_image = array (
					'url' => $resized_img_url,
					'width' => $new_img_size[0],
					'height' => $new_img_size[1]
				);
				
				return $vt_image;
			}
		}

		// no cached files - let's finally resize it
		$tp_image = wp_get_image_editor( $file_path );  
 		if ( ! is_wp_error( $tp_image ) ) {
			$tp_image->resize( $width, $height, $crop );
			$new_img_array = $tp_image->save();
		}
		$new_img_size = getimagesize( $new_img_array['path'] );
		$new_img = str_replace( basename( $image_src[0] ), basename(  $new_img_array['path'] ), $image_src[0] );
		
 

		
		

		// resized output
		$vt_image = array (
			'url' => $new_img,
			'width' => $new_img_size[0],
			'height' => $new_img_size[1]
		);
		
		return $vt_image;
	}

	// default output - without resizing
	$vt_image = array (
		'url' => $image_src[0],
		'width' => $image_src[1],
		'height' => $image_src[2]
	);
	
	return $vt_image;
}

	public function rectangular_talavera( $attachments ) {
		$grouper = new themePacific_Jetpack_Tiled_Gallery_Grouper( $attachments );

		themePacific_Jetpack_Tiled_Gallery_Shape::reset_last_shape();

		$output = $this->generate_carousel_container();
		foreach ( $grouper->grouped_images as $row ) {
			$output .= '<div class="gallery-row" style="' . esc_attr( 'width: ' . $row->width . 'px; height: ' . ( $row->height - 4 ) . 'px;' ) . '">';
			foreach( $row->groups as $group ) {
				$count = count( $group->images );
				$output .= '<div class="gallery-group images-' . esc_attr( $count ) . '" style="' . esc_attr( 'width: ' . $group->width . 'px; height: ' . $group->height . 'px;' ) . '">';
				foreach ( $group->images as $image ) {

					$size = 'large';
					if ( $image->width < 250 )
						$size = 'small';

					$image_title = $image->post_title;
					$orig_file = wp_get_attachment_url( $image->ID );
					$link = $this->get_attachment_link( $image->ID, $orig_file );

 					$img_src = $this->vt_resize(  $image->ID,'' , $image->width, $image->height, true ); 
					$output .= '<div class="tiled-gallery-item tiled-gallery-item-' . esc_attr( $size ) . '"><a href="' . esc_url( $link ) . '"><img ' . $this->generate_carousel_image_args( $image ) . ' src="' . esc_url( $img_src['url'] ) . '" width="' . esc_attr( $image->width ) . '" height="' . esc_attr( $image->height ) . '" align="left" title="' . esc_attr( $image_title ) . '" /></a>';

					if ( $this->atts['grayscale'] == true ) {
						$img_src_grayscale = jetpack_photon_url( $img_src['url'], array( 'filter' => 'grayscale' ) );
						$output .= '<a href="'. esc_url( $link ) . '"><img ' . $this->generate_carousel_image_args( $image ) . ' class="grayscale" src="' . esc_url( $img_src_grayscale ) . '" width="' . esc_attr( $image->width ) . '" height="' . esc_attr( $image->height ) . '" align="left" title="' . esc_attr( $image_title ) . '" /></a>';
					}

					if ( trim( $image->post_excerpt ) )
						$output .= '<div class="tiled-gallery-caption">' . wptexturize( $image->post_excerpt ) . '</div>';

					$output .= '</div>';
				}
				$output .= '</div>';
			}
			$output .= '</div>';
		}
		$output .= '</div>';
		return $output;
	}

	public function square_talavera( $attachments ) {
		$content_width = self::get_content_width();
		$images_per_row = 3;
		$margin = 2;

		$margin_space = ( $images_per_row * $margin ) * 2;
		$size = floor( ( $content_width - $margin_space ) / $images_per_row );
		$remainder = count( $attachments ) % $images_per_row;
		if ( $remainder > 0 ) {
			$remainder_space = ( $remainder * $margin ) * 2;
			$remainder_size = ceil( ( $content_width - $remainder_space - $margin ) / $remainder );
		}
		$output = $this->generate_carousel_container();
		$c = 1;
		foreach( $attachments as $image ) {
			if ( $remainder > 0 && $c <= $remainder )
				$img_size = $remainder_size;
			else
				$img_size = $size;
 
			$img_src = $this->vt_resize(  $image->ID,'' , $img_size, $img_size, true );
 			$orig_file = wp_get_attachment_url( $image->ID );
			$link = $this->get_attachment_link( $image->ID, $orig_file );
			$image_title = $image->post_title;


			$output .= '<div class="tiled-gallery-item">';
			$output .= '<a border="0" href="' . esc_url( $link ) . '"><img ' . $this->generate_carousel_image_args( $image ) . ' style="' . esc_attr( 'margin: ' . $margin . 'px' ) . '" src="' .  $img_src['url']  . '" width=' . esc_attr( $img_size ) . ' height=' . esc_attr( $img_size ) . ' title="' . esc_attr( $image_title ) . '" /></a>';

			// Grayscale effect
			if ( $this->atts['grayscale'] == true ) {
				$src = urlencode( $image->guid );
				$output .= '<a border="0" href="' . esc_url( $link ) . '"><img ' . $this->generate_carousel_image_args( $image ) . ' style="margin: 2px" class="grayscale" src="' . esc_url( 'http://en.wordpress.com/imgpress?url=' . urlencode( $image->guid ) . '&resize=' . $img_size . ',' . $img_size . '&filter=grayscale' ) . '" width=' . esc_attr( $img_size ) . ' height=' . esc_attr( $img_size ) . ' title="' . esc_attr( $image_title ) . '" /></a>';
			}

			// Captions
			if ( trim( $image->post_excerpt ) )
				$output .= '<div class="tiled-gallery-caption">' . wptexturize( $image->post_excerpt ) . '</div>';
			$output .= '</div>';
			$c ++;
		}
		$output .= '</div>';
		return $output;
	}

	public function circle_talavera( $attachments ) {
		return $this->square_talavera( $attachments );
	}

	public function rectangle_talavera( $attachments ) {
		return $this->rectangular_talavera( $attachments );
	}

	function generate_carousel_container() {
		global $post;

		$html = '<div '. $this->gallery_classes() . ' data-original-width="' . esc_attr( self::get_content_width() ) . '">';
		$blog_id = (int) get_current_blog_id();
		$extra_data = array( 'data-carousel-extra' => array( 'blog_id' => $blog_id, 'permalink' => get_permalink( $post->ID ) ) );

		foreach ( (array) $extra_data as $data_key => $data_values ) {
			$html = str_replace( '<div ', '<div ' . esc_attr( $data_key ) . "='" . json_encode( $data_values ) . "' ", $html );
		}

		return $html;
	}

	function generate_carousel_image_args( $image ) {
		$attachment_id = $image->ID;
		$orig_file       = wp_get_attachment_url( $attachment_id );
		$meta            = wp_get_attachment_metadata( $attachment_id );
		$size            = isset( $meta['width'] ) ? intval( $meta['width'] ) . ',' . intval( $meta['height'] ) : '';
		$img_meta        = ( ! empty( $meta['image_meta'] ) ) ? (array) $meta['image_meta'] : array();
		$comments_opened = intval( comments_open( $attachment_id ) );

		$medium_file_info = wp_get_attachment_image_src( $attachment_id, 'medium' );
		$medium_file      = isset( $medium_file_info[0] ) ? $medium_file_info[0] : '';

		$large_file_info  = wp_get_attachment_image_src( $attachment_id, 'large' );
		$large_file       = isset( $large_file_info[0] ) ? $large_file_info[0] : '';
		$attachment_title = wptexturize( $image->post_title );
		$attachment_desc  = wpautop( wptexturize( $image->post_content ) );

        // Not yet providing geo-data, need to "fuzzify" for privacy
		if ( ! empty( $img_meta ) ) {
            foreach ( $img_meta as $k => $v ) {
                if ( 'latitude' == $k || 'longitude' == $k )
                    unset( $img_meta[$k] );
            }
        }

		$img_meta = json_encode( array_map( 'strval', $img_meta ) );

		$output = sprintf(
				'data-attachment-id="%1$d" data-orig-file="%2$s" data-orig-size="%3$s" data-comments-opened="%4$s" data-image-meta="%5$s" data-image-title="%6$s" data-image-description="%7$s" data-medium-file="%8$s" data-large-file="%9$s"',
				esc_attr( $attachment_id ),
				esc_url( wp_get_attachment_url( $attachment_id ) ),
				esc_attr( $size ),
				esc_attr( $comments_opened ),
				esc_attr( $img_meta ),
				esc_attr( $attachment_title ),
				esc_attr( $attachment_desc ),
				esc_url( $medium_file ),
				esc_url( $large_file )
			);
		return $output;
	}

	public function gallery_classes() {
		$classes = 'class="tiled-gallery type-' . esc_attr( $this->atts['type'] ) . '"';
		return $classes;
	}

	public static function gallery_already_redefined() {
		global $shortcode_tags;
		if ( ! isset( $shortcode_tags[ 'gallery' ] ) || $shortcode_tags[ 'gallery' ] !== 'gallery_shortcode' )
			return true;
	}

	public static function init() {
		if ( self::gallery_already_redefined() )
			return;

		$gallery = new themePacific_Jetpack_Tiled_Gallery;
		add_filter( 'post_gallery', array( $gallery, 'gallery_shortcode' ), 1001, 2 );
	}

	public static function get_content_width() {
		global $content_width;

		$tiled_gallery_content_width = $content_width;

		if ( ! $tiled_gallery_content_width )
			$tiled_gallery_content_width = 500;

		return apply_filters( 'tiled_gallery_content_width', $tiled_gallery_content_width );
	}

	/**
	 * Media UI integration
	 */
	function jetpack_gallery_types( $types ) {
		if ( get_option( 'tiled_galleries' ) && isset( $types['default'] ) ) {
			// Tiled is set as the default, meaning that type='default'
			// will still display the mosaic.
			$types['thumbnails'] = $types['default'];
			unset( $types['default'] );
		}

		$types['rectangular'] = __( 'Tiled Mosaic', 'themepacific_gallery' );
		$types['square'] = __( 'Square Tiles', 'themepacific_gallery' );
		$types['circle'] = __( 'Circles', 'themepacific_gallery' );

		return $types;
	}

	function jetpack_default_gallery_type( $default ) {
		return ( get_option( 'tiled_galleries' ) ? 'rectangular' : 'default' );
	}

	/**
	 * Add a checkbox field to the Carousel section in Settings > Media
	 * for setting tiled galleries as the default.
	 */
	function settings_api_init() {
		global $wp_settings_sections;

		// Add the setting field [tiled_galleries] and place it in Settings > Media
		if ( isset( $wp_settings_sections['media']['carousel_section'] ) )
			$section = 'carousel_section';
		else
			$section = 'default';

		add_settings_field( 'tiled_galleries', __( 'Tiled Galleries', 'themepacific_gallery' ), array( $this, 'setting_html' ), 'media', $section );
		register_setting( 'media', 'tiled_galleries', 'esc_attr' );
	}

	function setting_html() {
		echo '<label><input name="tiled_galleries" type="checkbox" value="1" ' .
			checked( 1, '' != get_option( 'tiled_galleries' ), false ) . ' /> ' .
			__( 'Display all your gallery pictures in a cool mosaic.', 'themepacific_gallery' ) . '</br></label>';
	}
}

class themePacific_Jetpack_Tiled_Gallery_Shape {
	static $shapes_used = array();

	public function __construct( $images ) {
		$this->images = $images;
		$this->images_left = count( $images );
	}

	public function sum_ratios( $number_of_images = 3 ) {
		return array_sum( array_slice( wp_list_pluck( $this->images, 'ratio' ), 0, $number_of_images ) );
	}

	public function next_images_are_symmetric() {
		return $this->images_left > 2 && $this->images[0]->ratio == $this->images[2]->ratio;
	}

	public function is_not_as_previous( $n = 1 ) {
		return ! in_array( get_class( $this ), array_slice( self::$shapes_used, -$n ) );
	}

	public function is_wide_theme() {
		global $content_width;
		return $content_width > 1000;
	}

	public static function set_last_shape( $last_shape ) {
		self::$shapes_used[] = $last_shape;
	}

	public static function reset_last_shape() {
		self::$shapes_used = array();
	}
}

class themePacific_Jetpack_Tiled_Gallery_Three extends themePacific_Jetpack_Tiled_Gallery_Shape {
	public $shape = array( 1, 1, 1 );

	public function is_possible() {
		$ratio = $this->sum_ratios( 3 );
		return $this->images_left > 2 && $this->is_not_as_previous() &&
			( ( $ratio < 2.5 ) || ( $ratio < 5 && $this->next_images_are_symmetric() ) || $this->is_wide_theme() );
	}
}

class themePacific_Jetpack_Tiled_Gallery_Four extends themePacific_Jetpack_Tiled_Gallery_Shape {
	public $shape = array( 1, 1, 1, 1 );

	public function is_possible() {
		return $this->is_not_as_previous() && $this->sum_ratios( 4 ) < 3.5 &&
			( $this->images_left == 4 || ( $this->images_left != 8 && $this->images_left > 5 ) );
	}
}

class themePacific_Jetpack_Tiled_Gallery_Five extends themePacific_Jetpack_Tiled_Gallery_Shape {
	public $shape = array( 1, 1, 1, 1, 1 );

	public function is_possible() {
		return $this->is_wide_theme() && $this->is_not_as_previous() && $this->sum_ratios( 5 ) < 5 &&
			( $this->images_left == 5 || ( $this->images_left != 10 && $this->images_left > 6 ) );
	}
}

class themePacific_Jetpack_Tiled_Gallery_Two_One extends themePacific_Jetpack_Tiled_Gallery_Shape {
	public $shape = array( 2, 1 );

	public function is_possible() {
		return $this->is_not_as_previous( 3 ) && $this->images_left >= 2 &&
			$this->images[2]->ratio < 1.6 && $this->images[0]->ratio >=0.9 && $this->images[1]->ratio >= 0.9;
	}
}

class themePacific_Jetpack_Tiled_Gallery_One_Two extends themePacific_Jetpack_Tiled_Gallery_Shape {
	public $shape = array( 1, 2 );

	public function is_possible() {
		return $this->is_not_as_previous( 3 ) && $this->images_left >= 2 &&
			$this->images[0]->ratio < 1.6 && $this->images[1]->ratio >=0.9 && $this->images[2]->ratio >= 0.9;
	}
}

class themePacific_Jetpack_Tiled_Gallery_One_Three extends themePacific_Jetpack_Tiled_Gallery_Shape {
	public $shape = array( 1, 3 );

	public function is_possible() {
		return $this->is_not_as_previous() && $this->images_left >= 3 &&
			$this->images[0]->ratio < 0.8 && $this->images[1]->ratio >=0.9 && $this->images[2]->ratio >= 0.9 && $this->images[3]->ratio >= 0.9;
	}
}

class themePacific_Jetpack_Tiled_Gallery_Symmetric_Row extends themePacific_Jetpack_Tiled_Gallery_Shape {
	public $shape = array( 1, 2, 1 );

	public function is_possible() {
		return $this->is_not_as_previous() && $this->images_left >= 3 && $this->images_left != 5 &&
			$this->images[0]->ratio < 0.8 && $this->images[0]->ratio == $this->images[3]->ratio;
	}
}

class themePacific_Jetpack_Tiled_Gallery_Grouper {
	public $margin = 4;
	public function __construct( $attachments ) {
		$content_width = themePacific_Jetpack_Tiled_Gallery::get_content_width();
 		//$ua_info = new Jetpack_User_Agent_Info();

		$this->last_shape = '';
		$this->images = $this->get_images_with_sizes( $attachments );
		$this->grouped_images = $this->get_grouped_images();
		$this->apply_content_width( $content_width - 5 ); //reduce the margin hack to 5px. It will be further reduced when we fix more themes and the rounding error.
	}

	public function get_current_row_size() {
		$images_left = count( $this->images );
		if ( $images_left < 3 )
			return array_fill( 0, $images_left, 1 );

		foreach ( array( 'One_Three', 'One_Two', 'Five', 'Four', 'Three', 'Two_One', 'Symmetric_Row' ) as $shape_name ) {
			$class_name = "themePacific_Jetpack_Tiled_Gallery_$shape_name";
			$shape = new $class_name( $this->images );
			if ( $shape->is_possible() ) {
				themePacific_Jetpack_Tiled_Gallery_Shape::set_last_shape( $class_name );
				return $shape->shape;
			}
		}

		themePacific_Jetpack_Tiled_Gallery_Shape::set_last_shape( 'Two' );
		return array( 1, 1 );
	}

	public function get_images_with_sizes( $attachments ) {
		$images_with_sizes = array();

		foreach ( $attachments as $image ) {
			$meta  = wp_get_attachment_metadata( $image->ID );
			$image->width_orig = ( $meta['width'] > 0 )? $meta['width'] : 1;
			$image->height_orig = ( $meta['height'] > 0 )? $meta['height'] : 1;
			$image->ratio = $image->width_orig / $image->height_orig;
			$image->ratio = $image->ratio? $image->ratio : 1;
			$images_with_sizes[] = $image;
		}

		return $images_with_sizes;
	}

	public function read_row() {
		$vector = $this->get_current_row_size();

		$row = array();
		foreach ( $vector as $group_size ) {
			$row[] = new themePacific_Jetpack_Tiled_Gallery_Group( array_splice( $this->images, 0, $group_size ) );
		}

		return $row;
	}

	public function get_grouped_images() {
		$grouped_images = array();

		while( !empty( $this->images ) ) {
			$grouped_images[] = new themePacific_Jetpack_Tiled_Gallery_Row( $this->read_row() );
		}

		return $grouped_images;
	}

	// todo: split in functions
	// todo: do not stretch images
	public function apply_content_width( $width ) {
		foreach ( $this->grouped_images as $row ) {
			$row->width = $width;
			$row->raw_height = 1 / $row->ratio * ( $width - $this->margin * ( count( $row->groups ) - $row->weighted_ratio ) );
			$row->height = round( $row->raw_height );

			$this->calculate_group_sizes( $row );
		}
	}

	public function calculate_group_sizes( $row ) {
		// Storing the calculated group heights in an array for rounding them later while preserving their sum
		// This fixes the rounding error that can lead to a few ugly pixels sticking out in the gallery
		$group_widths_array = array();
		foreach ( $row->groups as $group ) {
			$group->height = $row->height;
			// Storing the raw calculations in a separate property to prevent rounding errors from cascading down and for diagnostics
			$group->raw_width = ( $row->raw_height - $this->margin * count( $group->images ) ) * $group->ratio + $this->margin;
			$group_widths_array[] = $group->raw_width;
		}
		$rounded_group_widths_array = themePacific_Jetpack_Constrained_Array_Rounding::get_rounded_constrained_array( $group_widths_array, $row->width );

		foreach ( $row->groups as $group ) {
			$group->width = array_shift( $rounded_group_widths_array );
			$this->calculate_image_sizes( $group );
		}
	}

	public function calculate_image_sizes( $group ) {
		// Storing the calculated image heights in an array for rounding them later while preserving their sum
		// This fixes the rounding error that can lead to a few ugly pixels sticking out in the gallery
		$image_heights_array = array();
		foreach ( $group->images as $image ) {
			$image->width = $group->width - $this->margin;
			// Storing the raw calculations in a separate property for diagnostics
			$image->raw_height = ( $group->raw_width - $this->margin ) / $image->ratio;
			$image_heights_array[] = $image->raw_height;
		}

		$image_height_sum = $group->height - count( $image_heights_array ) * $this->margin;
		$rounded_image_heights_array = themePacific_Jetpack_Constrained_Array_Rounding::get_rounded_constrained_array( $image_heights_array, $image_height_sum );

		foreach ( $group->images as $image ) {
			$image->height = array_shift( $rounded_image_heights_array );
		}
	}
}

class themePacific_Jetpack_Tiled_Gallery_Row {
	public function __construct( $groups ) {
		$this->groups = $groups;
		$this->ratio = $this->get_ratio();
		$this->weighted_ratio = $this->get_weighted_ratio();
	}

	public function get_ratio() {
		$ratio = 0;
		foreach ( $this->groups as $group ) {
			$ratio += $group->ratio;
		}
		return $ratio > 0? $ratio : 1;
	}

	public function get_weighted_ratio() {
		$weighted_ratio = 0;
		foreach ( $this->groups as $group ) {
			$weighted_ratio += $group->ratio * count( $group->images );
		}
		return $weighted_ratio > 0 ? $weighted_ratio : 1;
	}
}

class themePacific_Jetpack_Tiled_Gallery_Group {
	public function __construct( $images ) {
		$this->images = $images;
		$this->ratio = $this->get_ratio();
	}

	public function get_ratio() {
		$ratio = 0;
		foreach ( $this->images as $image ) {
			if ( $image->ratio )
				$ratio += 1/$image->ratio;
		}
		if ( !$ratio )
			return 1;

		return 1/$ratio;
	}
}

add_action( 'init', array( 'themePacific_Jetpack_Tiled_Gallery', 'init' ) );

