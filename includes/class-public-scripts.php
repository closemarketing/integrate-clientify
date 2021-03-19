<?php
/**
 * Library for public scripts
 *
 * @package    WordPress
 * @author     David Perez <david@closemarketing.es>
 * @copyright  2021 Closemarketing
 * @version    1.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Library for Public Scripts
 */
class INTCLI_Public_Scripts {
	/**
	 * Construct of class
	 */
	public function __construct() {
		add_action( 'wp_footer', array( $this, 'footer_scripts_webanalytics' ) );
		add_action( 'wp_footer', array( $this, 'footer_scripts_chatbots' ) );
	}

	/**
	 * Web Analytics script
	 *
	 * @return void
	 */
	public function footer_scripts_webanalytics() {
		$int_settings        = get_option( 'integration_clientify' );
		$active_webanalytics = isset( $int_settings['active'] ) ? $int_settings['active'] : 'no';
		$webanalytics        = isset( $int_settings['webanalytics'] ) ? $int_settings['webanalytics'] : '';

		if ( $webanalytics && 'yes' === $active_webanalytics ) {
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
			ana("setTrackingCode", "' . esc_html( $webanalytics ) . '");
			ana("trackPageview");
			}</script>
			<!--Clientify Tracking Ends-->';
		}
	}

	/**
	 * Chatbot script
	 *
	 * @return void
	 */
	public function footer_scripts_chatbots() {
		$int_settings = get_option( 'integration_clientify' );
		$chatbot      = isset( $int_settings['chatbot'] ) ? $int_settings['chatbot'] : '';

		if ( $chatbot ) {
			// Chatbots.
			echo '<script type="text/javascript" src="https://clientify.net/web-marketing/chatbots/script/' . esc_html( $chatbot ) . '.js"></script>';
		}
	}

}

new INTCLI_Public_Scripts();

