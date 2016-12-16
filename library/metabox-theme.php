<?php
/**
 * CMB2 Theme Options
 * @version 0.1.0
 */
class pure_admin {

	/**
 	 * Option key, and option page slug
 	 * @var string
 	 */
	private $key = 'pure_options';

	/**
 	 * Options page metabox id
 	 * @var string
 	 */
	private $metabox_id = 'pure_option_metabox';

	/**
	 * Options Page title
	 * @var string
	 */
	protected $title = '';

	/**
	 * Options Page hook
	 * @var string
	 */
	protected $options_page = '';

	/**
	 * Constructor
	 * @since 0.1.0
	 */
	public function __construct() {
		// Set our title
		$this->title = __( 'Site Options', 'pure' );
	}

	/**
	 * Initiate our hooks
	 * @since 0.1.0
	 */
	public function hooks() {
		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'cmb2_init', array( $this, 'add_options_page_metabox' ) );
	}


	/**
	 * Register our setting to WP
	 * @since  0.1.0
	 */
	public function init() {
		register_setting( $this->key, $this->key );
	}

	/**
	 * Add menu options page
	 * @since 0.1.0
	 */
	public function add_options_page() {
		$this->options_page = add_menu_page( $this->title, $this->title, 'manage_options', $this->key, array( $this, 'admin_page_display' ) );

		// Include CMB CSS in the head to avoid FOUT
		add_action( "admin_print_styles-{$this->options_page}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
	}

	/**
	 * Admin page markup. Mostly handled by CMB2
	 * @since  0.1.0
	 */
	public function admin_page_display() {
		?>
		<div class="wrap cmb2-options-page <?php echo $this->key; ?>">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<?php cmb2_metabox_form( $this->metabox_id, $this->key, array( 'cmb_styles' => false ) ); ?>
		</div>
		<?php
	}

	/**
	 * Add the options metabox to the array of metaboxes
	 * @since  0.1.0
	 */
	function add_options_page_metabox() {

		$cmb = new_cmb2_box( array(
			'id'      => $this->metabox_id,
			'hookup'  => false,
			'show_on' => array(
				// These are important, don't remove
				'key'   => 'options-page',
				'value' => array( $this->key, )
			),
		) );

		$pages = get_pages( array(
			'hierarchical' => 0,
			'sort_column' => 'post_title',
			'sort_order' => 'ASC'
		) );
		$all_pages = array();

		foreach ( $pages as $page ) {
			$all_pages[$page->ID] = $page->post_title;
		}


		// Set our CMB2 fields
		$cmb->add_field( array(
			'name' => 'Login Page',
			'id' => 'login-page',
			'type' => 'select',
			'options' => $all_pages
		) );

		// Set our CMB2 fields
		$cmb->add_field( array(
			'name' => 'Reset Password Page',
			'id' => 'reset-page',
			'type' => 'select',
			'options' => $all_pages
		) );

		// Set our CMB2 fields
		$cmb->add_field( array(
			'name' => 'My Account Page',
			'id' => 'account-page',
			'type' => 'select',
			'options' => $all_pages
		) );

	    $cmb->add_field( array(
	        'name' => 'Welcome Email',
	        'id'   => 'welcome-email',
	        'desc' => 'Set the contents of the welcome email. Use the following shortcodes to include user/site information:<br>[first-name], [last-name], [user-login], [email], <br>[login-url], [homepage], [admin-email], <br>[date], [time], [user-id].',
	        'type' => 'wysiwyg',
	        'options' => array( 'textarea_rows' => 15 ),
	        'default' => "Welcome, [first-name]!

Your account has been created - your user name is [user-login]. Thanks for registering!

Best regards,
<strong>The Team</strong>"
	    ) );

	    $cmb->add_field( array(
	        'name' => 'Password Reset Email',
	        'id'   => 'reset-email',
	        'desc' => 'Set the contents of the welcome email. Use the following shortcodes to include user/site information:<br>[first-name], [last-name], [user-login], [email], <br>[password-reset-url], [homepage], [admin-email], <br>[date], [time], [user-id].',
	        'type' => 'wysiwyg',
	        'options' => array( 'textarea_rows' => 15 ),
	        'default' => "Hello [first-name],

We received a password reset request from LSCU for your user account ([user-login]). To complete the password reset, click the link below to have a new password sent to you.

[password-reset-url]

If you didn't request this password reset, please disregard this email - no further action is required to maintain your account security.

Best regards,
<strong>The Team</strong>"
	    ) );

	    $cmb->add_field( array(
	        'name' => 'Member Email Subject',
	        'id'   => 'member-email-subject',
	        'desc' => 'Set a subject line for the new member email.',
	        'type' => 'text_medium',
	        'default' => "[Role Updated] Your account has been upgraded to 'Member'"
	    ) );

	    $cmb->add_field( array(
	        'name' => 'Member Email',
	        'id'   => 'member-email',
	        'desc' => 'Set the contents of the new member email. This is sent when a user is changed to a member role. Use the following shortcodes to include user/site information:<br>[first-name], [last-name], [user-login], [email], <br>[login-url], [homepage], [admin-email], <br>[homepage], [date], [time], [user-id].',
	        'type' => 'wysiwyg',
	        'options' => array( 'textarea_rows' => 15 ),
	        'default' => "Hello, [first-name]!

Your account ([email]) has been set up as a Member on our site. Visit the site below and log in to see your new privileges.

[homepage]

Best regards,
<strong>The Team</strong>"
	    ) );

	}

	/**
	 * Public getter method for retrieving protected/private variables
	 * @since  0.1.0
	 * @param  string  $field Field to retrieve
	 * @return mixed          Field value or exception is thrown
	 */
	public function __get( $field ) {
		// Allowed fields to retrieve
		if ( in_array( $field, array( 'key', 'metabox_id', 'title', 'options_page' ), true ) ) {
			return $this->{$field};
		}

		throw new Exception( 'Invalid property: ' . $field );
	}

}

/**
 * Helper function to get/return the pure_admin object
 * @since  0.1.0
 * @return pure_admin object
 */
function pure_admin() {
	static $object = null;
	if ( is_null( $object ) ) {
		$object = new pure_admin();
		$object->hooks();
	}

	return $object;
}



/**
 * Wrapper function around cmb2_get_option
 * @since  0.1.0
 * @param  string  $key Options array key
 * @return mixed        Option value
 */
function pure_get_option( $key = '' ) {
	return cmb2_get_option( pure_admin()->key, $key );
}

// Get it started
pure_admin();


?>