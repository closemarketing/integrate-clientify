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
	private $intclientify_settings;
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
			__( 'Clientify Integration', 'integrate-clientify' ),
			__( 'Clientify Integration', 'integrate-clientify' ),
			'manage_options',
			'intclientify_admin',
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Create admin page.
	 *
	 * @return void
	 */
	public function create_admin_page() {
		$this->intclientify_settings = get_option( 'integrate_clientify' );
		?>
		<div class="wrap">
			<h2><?php esc_html_e( 'Clientify Integration Settings', 'integrate-clientify' ); ?>
			</h2>
			<p></p>
			<?php
			settings_errors();
			?>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'intclientify_settings' );
				do_settings_sections( 'intclientify-admin' );
				submit_button( __( 'Save settings', 'integrate-clientify' ), 'primary', 'submit_settings' );
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
		register_setting( 'intclientify_settings', 'integrate_clientify', array( $this, 'sanitize_fields' ) );

		add_settings_section(
			'intcli_setting_section',
			__( 'Settings', 'integrate-clientify' ),
			array( $this, 'intcli_section_info' ),
			'intclientify-admin'
		);

		add_settings_field(
			'active',
			__( 'Active Clientify Web Analytics', 'integrate-clientify' ),
			array( $this, 'active_callback' ),
			'intclientify-admin',
			'intcli_setting_section'
		);

		add_settings_field(
			'webanalytics',
			__( 'Clientify Web Analytics Code', 'integrate-clientify' ),
			array( $this, 'webanalytics_callback' ),
			'intclientify-admin',
			'intcli_setting_section'
		);

		add_settings_field(
			'chatbot',
			__( 'Clientify ChatBot ID', 'integrate-clientify' ),
			array( $this, 'chatbot_callback' ),
			'intclientify-admin',
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

		if ( isset( $input['active'] ) ) {
			$sanitary_values['active'] = sanitize_text_field( $input['active'] );
		}
		if ( isset( $input['webanalytics'] ) ) {
			$sanitary_values['webanalytics'] = sanitize_text_field( $input['webanalytics'] );
		}
		if ( isset( $input['chatbot'] ) ) {
			$sanitary_values['chatbot'] = sanitize_text_field( $input['chatbot'] );
		}

		return $sanitary_values;
	}

	/**
	 * Info for holded automate section.
	 *
	 * @return void
	 */
	public function intcli_section_info() {
		esc_html_e( 'Put the settings for Clientify in order to integrate with WordPress', 'integrate_clientify' );
	}

	/**
	 * Metgs URL Callback
	 *
	 * @return void
	 */
	public function webanalytics_callback() {
		printf( '<input class="regular-text" type="text" name="integrate_clientify[webanalytics]" id="webanalytics" value="%s">', ( isset( $this->intclientify_settings['webanalytics'] ) ? esc_attr( $this->intclientify_settings['webanalytics'] ) : '' ) );
	}

	/**
	 * Chatbot URL Callback
	 *
	 * @return void
	 */
	public function chatbot_callback() {
		printf( '<input class="regular-text" type="text" name="integrate_clientify[chatbot]" id="chatbot" value="%s">', ( isset( $this->intclientify_settings['chatbot'] ) ? esc_attr( $this->intclientify_settings['chatbot'] ) : '' ) );
	}

	/**
	 * Chatbot URL Callback
	 *
	 * @return void
	 */
	public function active_callback() {
		?>
		<select name="integrate_clientify[active]" id="active">
			<?php
			$selected = ( isset( $this->intclientify_settings['active'] ) && $this->intclientify_settings['active'] === 'yes' ? 'selected' : '' );
			?>
			<option value="yes" <?php echo esc_html( $selected ); ?>><?php esc_html_e( 'Yes', 'integrate_clientify' ); ?></option>
			<?php
			$selected = ( isset( $this->intclientify_settings['active'] ) && $this->intclientify_settings['active'] === 'no' ? 'selected' : '' );
			?>
			<option value="no" <?php echo esc_html( $selected ); ?>><?php esc_html_e( 'No', 'integrate_clientify' ); ?></option>
		</select>
		<?php
	}
}
if ( is_admin() ) {
	$intclientify_admin = new INTCLI_Admin_Settings();
}
