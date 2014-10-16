<?php
/**
 * Register wigetable areas and custom widgets
 *
 * @package EducationNC
 */


/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function nomacorc_widgets_init() {
    register_sidebar( array(
        'name'          => 'Blog Sidebar',
        'id'            => 'blog-sidebar',
        'description'   => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
	register_sidebar( array(
		'name'          => 'Header - Left',
		'id'            => 'header-left',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
    register_sidebar( array(
        'name'          => 'Header - Right',
        'id'            => 'header-right',
        'description'   => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'nomacorc_widgets_init' );

/*
 * Tabbed Date Archives for the Blog
 *
 */

class nomacorc_blog_archive extends WP_Widget {

	function nomacorc_blog_archive() {
		// Instantiate the parent object
		parent::__construct( false, 'Archive Tabs' );
	}

	function widget( $args, $instance ) {
		// Widget output
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);

		echo $before_widget;
		?>

		<h3 class="widget-title"><?php echo $title; ?></h3>
		<div class="archive-years">
		<?php // nested list of archives by year and month because wp_get_archives is useless
			global $wpdb;
		    $years = $wpdb->get_col("SELECT DISTINCT YEAR(post_date)
		        FROM $wpdb->posts WHERE post_status = 'publish'
		        AND post_type = 'post' ORDER BY post_date DESC");
		    ?>

		    <ul class="tabs">
		    <?php foreach($years as $year) : ?>
		        <li><a href="#year-<?php echo $year; ?>"><?php echo $year; ?></a></li>
		    <?php endforeach; ?>
			</ul>

			<?php foreach($years as $year) : ?>
	            <div id="year-<?php echo $year; ?>" class="archive-months">
	            	<ul>
	                <?php
	                $months = $wpdb->get_col("SELECT DISTINCT MONTH(post_date)
	                    FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post'
	                    AND YEAR(post_date) = '".$year."' ORDER BY post_date DESC");
	                foreach($months as $month) : ?>
	                    <li><a href="<?php echo get_month_link($year, $month); ?>">
	                        <?php echo date( __('F'), mktime(0, 0, 0, $month) );?></a>
	                    </li>
	                <?php endforeach; ?>
	            	</ul>
	            </div>
		    <?php endforeach; ?>
		</div>
		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		// Save widget options
		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);

		return $instance;
	}

	function form( $instance ) {
		// Output admin widget options form
		$defaults = array('title' => 'Archives');
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title</label>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<?php
	}
}

/*
 * Twitter Widget (specific to this theme)
 *
 */

class nomacorc_twitter_widget extends WP_Widget {

	function nomacorc_twitter_widget() {
		// Instantiate the parent object
		parent::__construct( false, 'Twitter Widget' );
	}

	function widget( $args, $instance ) {
		// Widget output
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		$twitter_id = $instance['twitter_id'];

		echo $before_widget;
		?>

		<h3 class="widget-title"><?php echo $title; ?></h3>
		<?php nomacorc_tweets($twitter_id); ?>

		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		// Save widget options
		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);
		$instance['twitter_id'] = strip_tags($new_instance['twitter_id']);

		return $instance;
	}

	function form( $instance ) {
		// Output admin widget options form
		$defaults = array('title' => 'Twitter Today', 'twitter_id' => 'nomacorc');
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title</label>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('twitter_id'); ?>">Twitter Handle</label>
			<input id="<?php echo $this->get_field_id('twitter_id'); ?>" name="<?php echo $this->get_field_name('twitter_id'); ?>" type="text" value="<?php echo $instance['twitter_id']; ?>" />
		</p>
		<?php
	}
}

function nomacorc_register_widgets() {
	register_widget( 'nomacorc_blog_archive' );
	register_widget( 'nomacorc_twitter_widget' );
}

add_action( 'widgets_init', 'nomacorc_register_widgets' );
