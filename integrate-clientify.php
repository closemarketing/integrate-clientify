<?php
/**
 * Plugin Name: Integrate with Clientify
 * Plugin URI:  https://close.marketing/plugins/integrate-clientify/
 * Description: Integration for Clientify.
 * Version:     0.5.0
 * Author:      Closemarketing
 * Author URI:  https://close.marketing
 * Text Domain: integrate-clientify
 * Domain Path: /languages
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package     WordPress
 * @author      Closemarketing
 * @copyright   2021 Closemarketing
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 *
 * Prefix:      intcli
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

define( 'INTCLI_VERSION', '0.5.0' );

add_action( 'plugins_loaded', 'intcli_plugin_init' );
/**
 * Load localization files
 *
 * @return void
 */
function intcli_plugin_init() {
	load_plugin_textdomain( 'integrate-clientify', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

// Include files.
require_once plugin_dir_path( __FILE__ ) . '/includes/class-admin-settings.php';
require_once plugin_dir_path( __FILE__ ) . '/includes/class-public-scripts.php';
