<?php
/**
 * Plugin Name: Custom WooCommerce Email
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class Custom_WC_Email
 */
class Custom_WC_Email {

	/**
	 * Custom_WC_Email constructor.
	 */
	public function __construct() {
		add_action( 'woocommerce_email_classes', array( $this, 'register_email' ), 90, 1 );

		define( 'CUSTOM_WC_EMAIL_PATH', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Register the custom email classes.
	 *
	 * @param array $emails List of registered email classes.
	 * @return array
	 */
	public function register_email( $emails ) {
		require_once 'emails/class-wc-custom-invoice-email.php';
		require_once 'emails/class-wc-custom-prepayment-email.php';

		$emails['WC_Custom_Invoice_Email'] = new WC_Custom_Invoice_Email();
		$emails['WC_Custom_Prepayment_Email'] = new WC_Custom_Prepayment_Email();

		return $emails;
	}
}

new Custom_WC_Email();