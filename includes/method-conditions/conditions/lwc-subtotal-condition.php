<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'LWC_Subtotal_Condition' ) ) {

	class LWC_Subtotal_Condition extends LWC_Condition {

		public function __construct() {
			$this->name        = __( 'Subtotal', 'lwc-conditions' );
			$this->slug        = __( 'subtotal', 'lwc-conditions' );
			$this->group       = __( 'Cart', 'lwc-conditions' );
			$this->description = __( 'Compared against the order subtotal', 'lwc-conditions' );

			parent::__construct();
		}

		public function get_value( $value ) {
			return str_replace( ',', '.', $value );
		}

		public function get_value_for_comparison() {
			return WC()->cart->subtotal;
		}

	}

}