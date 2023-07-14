<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WC_Email' ) ) {
	return;
}

/**
 * Class WC_Custom_Prepayment_Email
 */
class WC_Custom_Prepayment_Email extends WC_Email {

	/**
	 * Create an instance of the class.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		$this->id                 = 'wc_custom_prepayment_email';
		$this->customer_email     = true;
		$this->title              = __( 'Prepayment Order to Customer', 'custom-wc-email' );
		$this->description        = __( 'An email sent to the customer when an order is set to prepayment.', 'custom-wc-email' );
		$this->heading            = __( 'Prepayment Order', 'custom-wc-email' );
		$this->subject            = __( 'Prepayment Order - {order_number}', 'custom-wc-email' );
		$this->template_html      = 'emails/wc-custom-prepayment-email.php';
		$this->template_plain     = 'emails/plain/wc-custom-prepayment-email.php';
		$this->template_base      = CUSTOM_WC_EMAIL_PATH . 'templates/';

		add_action( 'woocommerce_order_status_pending_to_prepayment_notification', array( $this, 'trigger' ) );

		parent::__construct();
	}

	/**
	 * Trigger Function that will send this email to the customer.
	 *
	 * @param int $order_id Order ID
	 * @return void
	 */
	public function trigger( $order_id ) {
		$this->object     = wc_get_order( $order_id );
		$this->recipient  = $this->object->get_billing_email();
		$this->placeholders['{order_number}'] = $this->object->get_order_number();

		if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
			return;
		}

		$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers() );
	}

	/**
	 * Get content html.
	 *
	 * @access public
	 * @return string
	 */
	public function get_content_html() {
		return wc_get_template_html( $this->template_html, array(
			'order'         => $this->object,
			'email_heading' => $this->get_heading(),
			'sent_to_admin' => false,
			'plain_text'    => false,
			'email'         => $this,
		), '', $this->template_base );
	}

	/**
	 * Get content plain.
	 *
	 * @return string
	 */
	public function get_content_plain() {
		return wc_get_template_html( $this->template_plain, array(
			'order'         => $this->object,
			'email_heading' => $this->get_heading(),
			'sent_to_admin' => false,
			'plain_text'    => true,
			'email'         => $this,
		), '', $this->template_base );
	}
}
