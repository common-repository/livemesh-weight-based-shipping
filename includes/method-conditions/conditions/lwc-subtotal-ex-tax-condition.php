<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'LWC_Subtotal_Ex_Tax_Condition' ) ) {

	class LWC_Subtotal_Ex_Tax_Condition extends LWC_Condition {

		public function __construct() {
			$this->name        = __( 'Subtotal ex. taxes', 'lwc-conditions' );
			$this->slug        = __( 'subtotal_ex_tax', 'lwc-conditions' );
			$this->group       = __( 'Cart', 'lwc-conditions' );
			$this->description = __( 'Compared against the order subtotal excluding taxes', 'lwc-conditions' );

			parent::__construct();
		}

		public function get_value( $value ) {
			return str_replace( ',', '.', $value );
		}

		public function get_value_for_comparison() {
			if ( method_exists( WC()->cart, 'get_subtotal' ) ) { // WC 3.2+
				return WC()->cart->get_subtotal();
			}

			return WC()->cart->subtotal_ex_tax;
		}

	}

}