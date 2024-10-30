<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'LWC_City_Condition' ) ) {

	class LWC_City_Condition extends LWC_Condition {

		public function __construct() {
			$this->name        = __( 'City', 'lwc-conditions' );
			$this->slug        = __( 'city', 'lwc-conditions' );
			$this->group       = __( 'User', 'lwc-conditions' );
			$this->description = __( 'Compare against customer city. Comma separated list allowed', 'lwc-conditions' );

			parent::__construct();
		}

		public function match( $match, $operator, $value ) {

			$value         = $this->get_value( $value );
			$customer_city = $this->get_value_for_comparison();

			if ( '==' == $operator ) :
				$match = ( in_array( $customer_city, preg_split( '/\, ?/', $value ) ) );
			elseif ( '!=' == $operator ) :
				$match = ( ! in_array( $customer_city, preg_split( '/\, ?/', $value ) ) );
			endif;

			return $match;

		}

		public function get_value( $value ) {
			return strtolower( $value );
		}

		public function get_value_for_comparison() {
			return strtolower( WC()->customer->get_shipping_city() );
		}

		public function get_operators() {

			$operators = parent::get_operators();

			unset( $operators['>='] );
			unset( $operators['<='] );

			return $operators;

		}

	}

}