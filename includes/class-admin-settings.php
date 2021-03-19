<?php
/**
 * Library for admin settings
 *
 * @package    WordPress
 * @author     David Perez <david@closemarketing.es>
 * @copyright  2021 Closemarketing
 * @version    1.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Library for Admin Settings
 */
class INTCLI_Admin_Settings {
	/**
	 * Settings
	 *
	 * @var array
	 */
	private $meetup_settings;
	/**
	 * Construct of class
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	/**
	 * Adds plugin page.
	 *
	 * @return void
	 */
	public function add_plugin_page() {
		add_submenu_page(
			'options-general.php',
			__( 'Clientify', 'integration-clientify' ),
			__( 'Clientify', 'integration-clientify' ),
			'manage_options',
			'intclientify_admin',
			array( $this, 'create_admin_page' ),
		);
	}

	/**
	 * Create admin page.
	 *
	 * @return void
	 */
	public function create_admin_page() {
		$this->meetup_settings = get_option( 'int_clientify' );
		$results = $this->get_meetup_options( $this->meetup_settings['meetup_url'] );
		?>
		<div class="wrap">
			<h2><?php esc_html_e( 'Meetings Settings', 'integration-clientify' ); ?>
			</h2>
			<p></p>
			<?php
			settings_errors();
			?>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'intclientify_settings' );
				do_settings_sections( 'meetings-admin' );
				submit_button( __( 'Save settings', 'integration-clientify' ), 'primary', 'submit_settings' );
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Init for page
	 *
	 * @return void
	 */
	public function page_init() {
		register_setting( 'intclientify_settings', 'meetings', array( $this, 'sanitize_fields' ) );

		add_settings_section(
			'intcli_setting_section',
			__( 'Settings', 'integration-clientify' ),
			array( $this, 'intcli_section_info' ),
			'meetings-admin'
		);

		add_settings_field(
			'meetup_url',
			__( 'Meetup URL', 'integration-clientify' ),
			array( $this, 'meetup_url_callback' ),
			'meetings-admin',
			'intcli_setting_section'
		);
	}

	/**
	 * Sanitize fiels before saves in DB
	 *
	 * @param array $input Input fields.
	 * @return array
	 */
	public function sanitize_fields( $input ) {
		$sanitary_values = array();

		if ( isset( $input['meetup_url'] ) ) {
			$sanitary_values['meetup_url'] = sanitize_text_field( $input['meetup_url'] );
		}

		return $sanitary_values;
	}

	/**
	 * Info for holded automate section.
	 *
	 * @return void
	 */
	public function intcli_section_info() {
		echo sprintf( __( 'Put the connection API key settings in order to connect and sync products. You can go here <a href="%s" target="_blank">Meetings API</a>. ', 'integration-clientify' ), 'https://www.meetup.com/' );
	}

	/**
	 * Metgs URL Callback
	 *
	 * @return void
	 */
	public function meetup_url_callback() {
		printf( '<input class="regular-text" type="text" name="meetings[meetup_url]" id="meetup_url" value="%s">', ( isset( $this->meetup_settings['meetup_url'] ) ? esc_attr( $this->meetup_settings['meetup_url'] ) : '' ) );
	}
}
if ( is_admin() ) {
	$intclientify_admin = new INTCLI_Admin_Settings();
}
