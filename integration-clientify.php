<?php
/**
 * Plugin Name: Integration with Clientify
 * Plugin URI:  https://close.marketing/plugins/integration-clientify/
 * Description: Integration for Clientify.
 * Version:     0.1
 * Author:      Closemarketing
 * Author URI:  https://close.marketing
 * Text Domain: integration-clientify
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

add_action( 'plugins_loaded', 'intcli_plugin_init' );
/**
 * Load localization files
 *
 * @return void
 */
function intcli_plugin_init() {
	load_plugin_textdomain( 'integration-clientify', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

// Include files.
require_once plugin_dir_path( __FILE__ ) . '/includes/class-admin-settings.php';

add_action( 'wp_footer', 'intcli_footer_scripts' );
/**
 * Web Analytics script
 *
 * @return void
 */
function intcli_footer_scripts() {
	// Web Analytics.
	echo '<!--Clientify Tracking Begins-->
	<script type="text/javascript">
	if (typeof trackerCode ==="undefined"){
	    (function (d, w, u, o) {
		  w[o] = w[o] || function () {
			(w[o].q = w[o].q || []).push(arguments)
		  };
		  a = d.createElement("script"),
			m = d.getElementsByTagName("script")[0];
		  a.async = 1; a.src = u;
		  m.parentNode.insertBefore(a, m)
	    })(document, window, "https://analytics.clientify.net/tracker.js", "ana");
	    ana("setTrackerUrl", "https://analytics.clientify.net");
	    ana("setTrackingCode", "CF-9313-9313-LMXY3");
	    ana("trackPageview");
	}</script>
	<!--Clientify Tracking Ends-->';

	// Chatbots.
	echo '<script type="text/javascript" src="https://clientify.net/web-marketing/chatbots/script/42538.js"></script>';
}