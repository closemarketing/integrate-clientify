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
			__( 'Clientify Integration', 'integration-clientify' ),
			__( 'Clientify Integration', 'integration-clientify' ),
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
		$this->intclientify_settings = get_option( 'integration_clientify' );
		?>
		<div class="wrap">
			<h2><?php esc_html_e( 'Clientify Integration Settings', 'integration-clientify' ); ?>
			</h2>
			<p></p>
			<?php
			settings_errors();
			?>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'intclientify_settings' );
				do_settings_sections( 'intclientify-admin' );
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
		register_setting( 'intclientify_settings', 'integration_clientify', array( $this, 'sanitize_fields' ) );

		add_settings_section(
			'intcli_setting_section',
			__( 'Settings', 'integration-clientify' ),
			array( $this, 'intcli_section_info' ),
			'intclientify-admin'
		);

		add_settings_field(
			'active',
			__( 'Active Clientify Web Analytics', 'integration-clientify' ),
			array( $this, 'active_callback' ),
			'intclientify-admin',
			'intcli_setting_section'
		);

		add_settings_field(
			'webanalytics',
			__( 'Clientify Web Analytics Code', 'integration-clientify' ),
			array( $this, 'webanalytics_callback' ),
			'intclientify-admin',
			'intcli_setting_section'
		);

		add_settings_field(
			'chatbot',
			__( 'Clientify ChatBot ID', 'integration-clientify' ),
			array( $this, 'chatbot_callback' ),
			'intclientify-admin',
			'intcli_setting_section'
		);

		add_settings_section(
			'intcli_spider_section',
			__( 'Spider Settings', 'integration-clientify' ),
			array( $this, 'spider_section' ),
			'intclientify-admin'
		);

		add_settings_field(
			'spider',
			__( 'Spider ID', 'integration-clientify' ),
			array( $this, 'spider_callback' ),
			'intclientify-admin',
			'intcli_spider_section'
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

		// Save Spider options.
		if ( isset( $input['spider'] ) ) {
			$index = 0;
			foreach ( $input['spider'] as $spider ) {
				$sanitary_values['spider'][ $index ]['page'] = sanitize_text_field( $spider['page'] );
				$sanitary_values['spider'][ $index ]['id']   = sanitize_text_field( $spider['id'] );
				$index++;
			}
		}

		return $sanitary_values;
	}

	/**
	 * Info for holded automate section.
	 *
	 * @return void
	 */
	public function intcli_section_info() {
		esc_html_e( 'Put the settings for Clientify in order to integrate with WordPress', 'integration_clientify' );
	}

	/**
	 * Metgs URL Callback
	 *
	 * @return void
	 */
	public function webanalytics_callback() {
		printf( '<input class="regular-text" type="text" name="integration_clientify[webanalytics]" id="webanalytics" value="%s">', ( isset( $this->intclientify_settings['webanalytics'] ) ? esc_attr( $this->intclientify_settings['webanalytics'] ) : '' ) );
	}

	/**
	 * Chatbot URL Callback
	 *
	 * @return void
	 */
	public function chatbot_callback() {
		printf( '<input class="regular-text" type="text" name="integration_clientify[chatbot]" id="chatbot" value="%s">', ( isset( $this->intclientify_settings['chatbot'] ) ? esc_attr( $this->intclientify_settings['chatbot'] ) : '' ) );
	}

	/**
	 * Chatbot URL Callback
	 *
	 * @return void
	 */
	public function active_callback() {
		?>
		<select name="integration_clientify[active]" id="active">
			<?php
			$selected = ( isset( $this->intclientify_settings['active'] ) && $this->intclientify_settings['active'] === 'yes' ? 'selected' : '' );
			?>
			<option value="yes" <?php echo esc_html( $selected ); ?>><?php esc_html_e( 'Yes', 'integration_clientify' ); ?></option>
			<?php
			$selected = ( isset( $this->intclientify_settings['active'] ) && $this->intclientify_settings['active'] === 'no' ? 'selected' : '' );
			?>
			<option value="no" <?php echo esc_html( $selected ); ?>><?php esc_html_e( 'No', 'integration_clientify' ); ?></option>
		</select>
		<?php
	}

	/**
	 * Info for holded automate section.
	 *
	 * @return void
	 */
	public function spider_section() {
		esc_html_e( 'Put the settings for Clientify in order to integrate with WordPress', 'integration_clientify' );
	}

	private function get_pages_option() {
		// Get Post Types Public.
		$posts_options = array();

		$post_types = get_post_types(
			array(
				'public'   => true,
			),
			'object',
			'and'
		);
		foreach ( $post_types as $post_type ) {
			if ( 'attachment' === $post_type->name ) {
				continue;
			}
			// * Get posts in array
			$posts_options[] = '--- ' . $post_type->label . ' ---';

			$args_query          = array(
				'post_type'      => $post_type->name,
				'posts_per_page' => -1,
				'orderby'        => 'title', // menu_order, rand, date.
				'order'          => 'ASC',
			);
			$posts_array = get_posts( $args_query );
			foreach ( $posts_array as $post_single ) {
				$posts_options[ $post_type->name . '|' . $post_single->ID ] = $post_single->post_title;
			}
		}

		// Get Taxonomies Public.
		$taxonomies = get_taxonomies(
			array(
				'public'   => true,
			),
			'object',
			'and'
		);
		foreach ( $taxonomies as $taxonomy ) {
			// * Get posts in array
			$posts_options[] = '--- ' . $taxonomy->label . ' ---';

			$args_query          = array(
				'taxonomy'   => $taxonomy->name,
				'hide_empty' => false,
				'orderby'    => 'title', // menu_order, rand, date.
				'order'      => 'ASC',
			);
			$terms_array = get_terms( $args_query );
			foreach ( $terms_array as $term ) {
				$posts_options[ $taxonomy->name . '|' . $term->term_id ] = $term->name;
			}
		}
		return $posts_options;
	}

	/**
	 * Spider URL Callback
	 *
	 * @return void
	 */
	public function spider_callback() {
		$options = get_option( 'integration_clientify' );
		$posts_options = $this->get_pages_option();

		for ( $idx = 0, $size = count( $options['spider'] ); $idx < $size; ++$idx ) {
			?>
			<div class="repeating" style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
				<p><strong><?php esc_html_e( 'Page to load' );?></strong></p>
				<select name='integration_clientify[spider][<?php echo $idx; ?>][page]'>
					<option value=''></option>
					<?php 
					// Load Page Options.
					foreach ( $posts_options as $key => $value ) {
						echo '<option value="' . esc_html( $key ) . '" ';
						selected( $key, $options['spider'][ $idx ]['page']);
						echo '>' . esc_html( $value ) . '</option>';
					}
					?>
				</select>
				<p><strong><?php esc_html_e( 'ID Spider' ); ?></strong></p>
				<input type="text" size="30" name="integration_clientify[spider][<?php echo $idx; ?>][id]" value="<?php echo $options['spider'][ $idx ]['id']; ?>" />
				<p><a href="#" class="repeat">Add Another</a></p>
			</div>
			<?php
		}
		?>
		<script type="text/javascript">
		// Prepare new attributes for the repeating section
		var attrs = ['for', 'id', 'name'];
		function resetAttributeNames(section) { 
		var tags = section.find('select, input, label'), idx = section.index();
		tags.each(function() {
			var $this = jQuery(this);
			jQuery.each(attrs, function(i, attr) {
			var attr_val = $this.attr(attr);
			if (attr_val) {
				$this.attr(attr, attr_val.replace(/\[spider\]\[\d+\]\[/, '\[spider\]\['+(idx + 1)+'\]\['))
			}
			})
		})
		}

		// Clone the previous section, and remove all of the values                  
		jQuery('.repeat').click(function(e){
			e.preventDefault();
			var lastRepeatingGroup = jQuery('.repeating').last();
			var cloned = lastRepeatingGroup.clone(true)  
			cloned.insertAfter(lastRepeatingGroup);
			cloned.find("input").val("");
			cloned.find("select").val("");
			resetAttributeNames(cloned)
		});
		
		</script>
		<?php
	}
}
if ( is_admin() ) {
	$intclientify_admin = new INTCLI_Admin_Settings();
}
