<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Handles all AJAX calls.
 */
class LWBS_Ajax_Helper {


	/**
	 * Add ajax actions in order for AJAX calls to work.
	 *
	 */
	public function __construct() {
		// Update elements
		add_action( 'wp_ajax_lwbs_update_condition_value', array( $this, 'update_condition_value' ) );

	}


	/**
	 * Generate the HTML of the value field based on the condition key chosen by the user.
	 *
	 */
	public function update_condition_value() {
		check_ajax_referer( 'lwc-ajax-nonce', 'nonce' );

        $id = sanitize_key($_POST['id']);
        $group = wc_clean($_POST['group']);
        $condition = wc_clean($_POST['condition']);

		$lwc_condition     = new LWBS_Condition( $id, $group, $condition );
		$value_field_args = $lwc_condition->get_value_field_args();

		?><span class='lwc-value-field-wrap'><?php
			lwc_html_field( $value_field_args );
		?></span><?php

		die();
	}

}
