<?php
class Tribe__Tickets__Tickets_Handler {
	/**
	 * Singleton instance of this class
	 *
	 * @var Tribe__Tickets__Tickets_Handler
	 * @static
	 */
	protected static $instance;

	/**
	 * Path to this plugin
	 * @var string
	 */
	protected $path;

	/**
	 * Post Meta key for the ticket header
	 * @var string
	 */
	protected $image_header_field = '_tribe_ticket_header';

	/**
	 * Slug of the admin page for attendees
	 * @var string
	 */
	public static $attendees_slug = 'tickets-attendees';

	/**
	 * Hook of the admin page for attendees
	 * @var
	 */
	private $attendees_page;

	/**
	 * WP_Post_List children for Attendees
	 * @var Tribe__Tickets__Attendees_Table
	 */
	private $attendees_table;

	/**
	 * @var Tribe__Tickets__Google_Event_Data
	 */
	protected $google_event_data;


	/**
	 *    Class constructor.
	 */
	public function __construct() {
		$main = Tribe__Tickets__Main::instance();

		foreach ( $main->post_types() as $post_type ) {
			add_action( 'save_post_' . $post_type, array( $this, 'save_image_header' ), 10, 2 );
		}

		add_action( 'wp_ajax_tribe-ticket-email-attendee-list', array( $this, 'ajax_handler_attendee_mail_list' ) );
		add_action( 'admin_menu', array( $this, 'attendees_page_register' ) );
		add_filter( 'post_row_actions', array( $this, 'attendees_row_action' ) );

		$this->path = trailingslashit(  dirname( dirname( dirname( __FILE__ ) ) ) );
		$this->google_event_data = new Tribe__Tickets__Google_Event_Data;
	}

	/**
	 * Adds the "attendees" link in the admin list row actions for each event.
	 *
	 * @param $actions
	 *
	 * @return array
	 */
	public function attendees_row_action( $actions ) {
		global $post;

		if ( in_array( $post->post_type, Tribe__Tickets__Main::instance()->post_types() ) ) {
			$url = add_query_arg( array(
				'post_type' => $post->post_type,
				'page'      => self::$attendees_slug,
				'event_id'  => $post->ID,
			), admin_url( 'edit.php' ) );

			$actions['tickets_attendees'] = sprintf( '<a title="%s" href="%s">%s</a>', esc_html__( 'See who purchased tickets to this event', 'event-tickets' ), esc_url( $url ), esc_html__( 'Attendees', 'event-tickets' ) );
		}

		return $actions;
	}

	/**
	 * Registers the Attendees admin page
	 */
	public function attendees_page_register() {

		$this->attendees_page = add_submenu_page( null, 'Attendee list', 'Attendee list', 'edit_posts', self::$attendees_slug, array( $this, 'attendees_page_inside' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'attendees_page_load_css_js' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'attendees_page_load_pointers' ) );
		add_action( 'load-' . $this->attendees_page, array( $this, 'attendees_page_screen_setup' ) );

	}

	/**
	 * Enqueues the JS and CSS for the attendees page in the admin
	 *
	 * @param $hook
	 */
	public function attendees_page_load_css_js( $hook ) {
		if ( $hook != $this->attendees_page ) {
			return;
		}

		$resources_url = plugins_url( 'src/resources', dirname( dirname( __FILE__ ) ) );

		wp_enqueue_style( self::$attendees_slug, $resources_url . '/css/tickets-attendees.css', array(), Tribe__Tickets__Main::instance()->css_version() );
		wp_enqueue_style( self::$attendees_slug . '-print', $resources_url . '/css/tickets-attendees-print.css', array(), Tribe__Tickets__Main::instance()->css_version(), 'print' );
		wp_enqueue_script( self::$attendees_slug, $resources_url . '/js/tickets-attendees.js', array( 'jquery' ), Tribe__Tickets__Main::instance()->js_version() );

		$mail_data = array(
			'nonce'           => wp_create_nonce( 'email-attendee-list' ),
			'required'        => esc_html__( 'You need to select a user or type a valid email address', 'event-tickets' ),
			'sending'         => esc_html__( 'Sending...', 'event-tickets' ),
			'checkin_nonce'   => wp_create_nonce( 'checkin' ),
			'uncheckin_nonce' => wp_create_nonce( 'uncheckin' ),
		);

		wp_localize_script( self::$attendees_slug, 'Attendees', $mail_data );
	}

	/**
	 * Loads the WP-Pointer for the Attendees screen
	 *
	 * @param $hook
	 */
	public function attendees_page_load_pointers( $hook ) {
		if ( $hook != $this->attendees_page ) {
			return;
		}

		$dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
		$pointer   = null;

		if ( version_compare( get_bloginfo( 'version' ), '3.3', '>' ) && ! in_array( 'attendees_filters', $dismissed ) ) {
			$pointer = array(
				'pointer_id' => 'attendees_filters',
				'target'     => '#screen-options-link-wrap',
				'options'    => array(
					'content' => sprintf( '<h3> %s </h3> <p> %s </p>', esc_html__( 'Columns', 'event-tickets' ), esc_html__( 'You can use Screen Options to select which columns you want to see. The selection works in the table below, in the email, for print and for the CSV export.', 'event-tickets' ) ),
					'position' => array( 'edge' => 'top', 'align' => 'right' ),
				),
			);
			wp_enqueue_script( 'wp-pointer' );
			wp_enqueue_style( 'wp-pointer' );
		}

		wp_localize_script( self::$attendees_slug, 'AttendeesPointer', $pointer );
	}

	/**
	 *    Setups the Attendees screen data.
	 */
	public function attendees_page_screen_setup() {
		if ( ! empty( $_GET['action'] ) && in_array( $_GET['action'], array( 'email' ) ) ) {
			define( 'IFRAME_REQUEST', true );

			// Use iFrame Header -- WP Method
			iframe_header();

			// Check if we need to send an Email!
			if ( isset( $_POST['tribe-send-email'] ) && $_POST['tribe-send-email'] ) {
				$status = $this->send_attendee_mail_list();
			} else {
				$status = false;
			}

			$which_tmpl = sanitize_file_name( $_GET['action'] );
			include $this->path . 'src/admin-views/attendees-' . $which_tmpl . '.php';

			// Use iFrame Footer -- WP Method
			iframe_footer();

			// We need nothing else here
			exit;
		} else {
			$this->attendees_table = new Tribe__Tickets__Attendees_Table();

			$this->maybe_generate_attendees_csv();

			add_filter( 'admin_title', array( $this, 'attendees_admin_title' ), 10, 2 );
			add_filter( 'admin_body_class', array( $this, 'attendees_admin_body_class' ) );
		}
	}

	public function attendees_admin_body_class( $body_classes ) {
		return $body_classes . ' plugins-php';
	}

	/**
	 * Sets the browser title for the Attendees admin page.
	 * Uses the event title.
	 *
	 * @param $admin_title
	 * @param $title
	 *
	 * @return string
	 */
	public function attendees_admin_title( $admin_title, $title ) {
		if ( ! empty( $_GET['event_id'] ) ) {
			$event       = get_post( $_GET['event_id'] );
			$admin_title = sprintf( '%s - Attendee list', $event->post_title );
		}

		return $admin_title;
	}

	/**
	 * Renders the Attendees page
	 */
	public function attendees_page_inside() {
		include $this->path . 'src/admin-views/attendees.php';
	}

	/**
	 * Generates a list of attendees taking into account the Screen Options.
	 * It's used both for the Email functionality, as for the CSV export.
	 *
	 * @param $event_id
	 *
	 * @return array
	 */
	private function _generate_filtered_attendees_list( $event_id ) {

		if ( empty( $this->attendees_page ) ) {
			$this->attendees_page = 'tribe_events_page_tickets-attendees';
		}

		$columns = $this->attendees_table->get_columns();
		$hidden  = get_hidden_columns( $this->attendees_page );

		// We dont want to export html inputs or private data
		$hidden[] = 'cb';
		$hidden[] = 'provider';

		// Get the data
		$items = Tribe__Tickets__Tickets::get_event_attendees( $event_id );

		// if there are attendees, hide any column that the attendee array doesn't contain
		if ( count( $items ) ) {
			$hidden = array_merge(
				$hidden,
				array_diff(
					array_keys( $columns ),
					array_keys( $items[0] )
				)
			);
		}

		// remove the hidden fields from the final list of columns
		$hidden         = array_filter( $hidden );
		$hidden         = array_flip( $hidden );
		$export_columns = array_diff_key( $columns, $hidden );
		$columns_names  = array_filter( array_values( $export_columns ) );
		$export_columns = array_filter( array_keys( $export_columns ) );

		$rows = array( $columns_names );
		//And echo the data
		foreach ( $items as $item ) {
			$row = array();
			foreach ( $item as $key => $data ) {
				if ( in_array( $key, $export_columns ) ) {
					if ( $key == 'check_in' && $data == 1 ) {
						$data = esc_html__( 'Yes', 'event-tickets' );
					}
					$row[ $key ] = $data;
				}
			}
			$rows[] = array_values( $row );
		}

		return array_filter( $rows );
	}

	/**
	 *    Checks if the user requested a CSV export from the attendees list.
	 *  If so, generates the download and finishes the execution.
	 */
	public function maybe_generate_attendees_csv() {

		if ( empty( $_GET['attendees_csv'] ) || empty( $_GET['attendees_csv_nonce'] ) || empty( $_GET['event_id'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_GET['attendees_csv_nonce'], 'attendees_csv_nonce' ) || ! $this->user_can( 'edit_posts', $_GET['event_id'] ) ) {
			return;
		}


		$items = apply_filters( 'tribe_events_tickets_attendees_csv_items', $this->_generate_filtered_attendees_list( $_GET['event_id'] ) );;
		$event = get_post( $_GET['event_id'] );

		if ( ! empty( $items ) ) {

			$charset  = get_option( 'blog_charset' );
			$filename = sanitize_file_name( $event->post_title . '-' . __( 'attendees', 'event-tickets' ) );

			// output headers so that the file is downloaded rather than displayed
			header( "Content-Type: text/csv; charset=$charset" );
			header( "Content-Disposition: attachment; filename=$filename.csv" );

			// create a file pointer connected to the output stream
			$output = fopen( 'php://output', 'w' );

			//And echo the data
			foreach ( $items as $item ) {
				fputcsv( $output, $item );
			}

			fclose( $output );
			exit;
		}
	}

	/**
	 * Handles the "send to email" action for the attendees list.
	 */
	public function send_attendee_mail_list() {
		$error = new WP_Error();

		if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'email-attendees-list' ) || ! $this->user_can( 'edit_posts', $_GET['event_id'] ) ) {
			$error->add( 'nonce-fail', esc_html__( 'Cheatin Huh?', 'event-tickets' ), array( 'type' => 'general' ) );

			return $error;
		}

		if ( empty( $_GET['event_id'] ) ) {
			$error->add( 'no-event-id', esc_html__( 'Invalid Event ID', 'event-tickets' ), array( 'type' => 'general' ) );

			return $error;
		}

		if ( empty( $_POST['email_to_address'] ) && ( empty( $_POST['email_to_user'] ) || 0 >= (int) $_POST['email_to_user'] ) ) {
			$error->add( 'empty-fields', esc_html__( 'Empty user and email', 'event-tickets' ), array( 'type' => 'general' ) );

			return $error;
		}

		if ( ! empty( $_POST['email_to_address'] ) ) {
			$type = 'email';
		} else {
			$type = 'user';
		}

		if ( 'email' === $type && ! is_email( $_POST['email_to_address'] ) ) {
			$error->add( 'invalid-email', esc_html__( 'Invalid Email', 'event-tickets' ), array( 'type' => $type ) );

			return $error;
		}

		if ( 'user' === $type && ! is_numeric( $_POST['email_to_user'] ) ) {
			$error->add( 'invalid-user', esc_html__( 'Invalid User ID', 'event-tickets' ), array( 'type' => $type ) );

			return $error;
		}

		/**
		 * Now we know we have valid data
		 */

		if ( 'email' === $type ) {
			// We already check this variable so, no harm here
			$email = $_POST['email_to_address'];
		} else {
			$user = get_user_by( 'id', $_POST['email_to_user'] );

			if ( ! is_object( $user ) ) {
				$error->add( 'invalid-user', esc_html__( 'Invalid User ID', 'event-tickets' ), array( 'type' => $type ) );

				return $error;
			}

			$email = $user->data->user_email;
		}

		$this->attendees_table = new Tribe__Tickets__Attendees_Table();

		$items = $this->_generate_filtered_attendees_list( $_GET['event_id'] );

		$event = get_post( $_GET['event_id'] );

		ob_start();
		$attendee_tpl = Tribe__Tickets__Templates::get_template_hierarchy( 'tickets/attendees-email.php', array( 'disable_view_check' => true ) );
		include $attendee_tpl;
		$content = ob_get_clean();

		add_filter( 'wp_mail_content_type', array( $this, 'set_contenttype' ) );
		if ( ! wp_mail( $email, sprintf( esc_html__( 'Attendee List for: %s', 'event-tickets' ), $event->post_title ), $content ) ) {
			$error->add( 'email-error', esc_html__( 'Error when sending the email', 'event-tickets' ), array( 'type' => 'general' ) );

			return $error;
		}

		return esc_html__( 'Email sent successfully!', 'event-tickets' );
	}

	/**
	 * Sets the content type for the attendees to email functionality.
	 * Allows for sending an HTML email.
	 *
	 * @param $content_type
	 *
	 * @return string
	 */
	public function set_contenttype( $content_type ) {
		return 'text/html';
	}

	/**
	 * Tests if the user has the specified capability in relation to whatever post type
	 * the ticket relates to.
	 *
	 * For example, if tickets are created for the banana post type, the generic capability
	 * "edit_posts" will be mapped to "edit_bananas" or whatever is appropriate.
	 *
	 * @internal for internal plugin use only (in spite of having public visibility)
	 *
	 * @param  string $generic_cap
	 * @param  int    $event_id
	 * @return boolean
	 */
	public function user_can( $generic_cap, $event_id ) {
		$type = get_post_type( $event_id );

		// It's possible we'll get null back
		if ( null === $type ) {
			return false;
		}

		$type_config = get_post_type_object( $type );

		if ( ! empty( $type_config->cap->{$generic_cap} ) ) {
			return current_user_can( $type_config->cap->{$generic_cap} );
		}

		return false;
	}

	/* Tickets Metabox */

	/**
	 * Includes the tickets metabox inside the Event edit screen
	 *
	 * @param $post_id
	 */
	public function do_meta_box( $post_id ) {

		$startMinuteOptions   = Tribe__View_Helpers::getMinuteOptions( null );
		$endMinuteOptions     = Tribe__View_Helpers::getMinuteOptions( null );
		$startHourOptions     = Tribe__View_Helpers::getHourOptions( null, true );
		$endHourOptions       = Tribe__View_Helpers::getHourOptions( null, false );
		$startMeridianOptions = Tribe__View_Helpers::getMeridianOptions( null, true );
		$endMeridianOptions   = Tribe__View_Helpers::getMeridianOptions( null );

		$tickets = Tribe__Tickets__Tickets::get_event_tickets( $post_id );
		include $this->path . 'src/admin-views/meta-box.php';
	}

	/**
	 * Echoes the markup for the tickets list in the tickets metabox
	 *
	 * @param array $tickets
	 */
	public function ticket_list_markup( $tickets = array() ) {
		if ( ! empty( $tickets ) ) {
			include $this->path . 'src/admin-views/list.php';
		}
	}

	/**
	 * Returns the markup for the tickets list in the tickets metabox
	 *
	 * @param array $tickets
	 *
	 * @return string
	 */
	public function get_ticket_list_markup( $tickets = array() ) {

		ob_start();
		$this->ticket_list_markup( $tickets );
		$return = ob_get_contents();
		ob_end_clean();

		return $return;
	}

	/**
	 * Returns the attachment ID for the header image for a event.
	 *
	 * @param $event_id
	 *
	 * @return mixed
	 */
	public function get_header_image_id( $event_id ) {
		return get_post_meta( $event_id, $this->image_header_field, true );
	}

	/**
	 * Save or delete the image header for tickets on an event
	 *
	 * @param $post_id
	 * @param $post
	 */
	public function save_image_header( $post_id, $post ) {
		// don't do anything on autosave or auto-draft either or massupdates
		if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
			return;
		}

		if ( empty( $_POST['tribe_ticket_header_image_id'] ) ) {
			delete_post_meta( $post_id, $this->image_header_field );
		} else {
			update_post_meta( $post_id, $this->image_header_field, $_POST['tribe_ticket_header_image_id'] );
		}

		return;
	}

	/**
	 * Static Singleton Factory Method
	 *
	 * @return Tribe__Tickets__Tickets_Handler
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			$className      = __CLASS__;
			self::$instance = new $className;
		}

		return self::$instance;
	}

}
