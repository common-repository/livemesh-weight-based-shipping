<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'LWC_Date_Condition' ) ) {

	class LWC_Date_Condition extends LWC_Condition {

		public function __construct() {
			$this->name        = __( 'Date', 'lwc-conditions' );
			$this->slug        = __( 'date', 'lwc-conditions' );
			$this->group       = __( 'General', 'lwc-conditions' );
			$this->description = sprintf( __( 'Compares given date to the current date. Current date: %s', 'lwc-conditions' ), date_i18n( get_option( 'date_format' ) ) );

			parent::__construct();
		}

		public function get_value( $value ) {
			return date( 'Y-m-d', strtotime( $value ) ); // Set date in Year-Month-Day format
		}

		public function get_value_for_comparison() {
			return current_time( 'Y-m-d' ); // Today's date in Year-Month-Day format
		}

		public function get_value_field_args() {

			$field_args = array(
				'type' => 'text',
				'class' => array( 'lwc-value' ),
				'placeholder' => 'dd-mm-yyyy or yyyy-mm-dd',
			);

			return $field_args;

		}

	}

}