<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'LWC_Country_Condition' ) ) {

	class LWC_Country_Condition extends LWC_Condition {

		public function __construct() {
			$this->name        = __( 'Country', 'lwc-conditions' );
			$this->slug        = __( 'country', 'lwc-conditions' );
			$this->group       = __( 'User', 'lwc-conditions' );
			$this->description = __( 'Compare against the customer country', 'lwc-conditions' );

			parent::__construct();
		}

		public function match( $match, $operator, $value ) {

			$value            = $this->get_value( $value );
			$customer_country = $this->get_value_for_comparison();

			if ( method_exists( WC()->countries, 'get_continent_code_for_country' ) ) :
				$customer_continent = WC()->countries->get_continent_code_for_country( $customer_country );
			endif;

			if ( '==' == $operator ) :
				$match = stripos( $customer_country, $value ) === 0;

				// Check for continents if available
				if ( ! $match && isset( $customer_continent ) && strpos( $value, 'CO_' ) === 0 ) :
					$match = stripos( $customer_continent, str_replace( 'CO_','', $value ) ) === 0;
				endif;
			elseif ( '!=' == $operator ) :
				$match = stripos( $customer_country, $value ) === false;

				// Check for continents if available
				if ( isset( $customer_continent ) && strpos( $value, 'CO_' ) === 0 ) :
					$match = stripos( $customer_continent, str_replace( 'CO_','', $value ) ) === false;
				endif;
			endif;

			return $match;

		}

		public function get_value_for_comparison() {
			return WC()->customer->get_shipping_country();
		}

		public function get_operators() {

			$operators = parent::get_operators();

			unset( $operators['>='] );
			unset( $operators['<='] );

			return $operators;

		}


		public function get_value_field_args() {

			$countries  =  WC()->countries->get_allowed_countries() + WC()->countries->get_shipping_countries();
			$continents = array();
			if ( method_exists( WC()->countries, 'get_continents' ) ) :
				foreach ( WC()->countries->get_continents() as $k => $v ) :
					$continents[ 'CO_' . $k ] = $v['name']; // Add prefix for country key compatibility
				endforeach;
			endif;

			$field_args = array(
				'type' => 'select',
				'class' => array( 'lwc-value', 'wc-enhanced-select' ),
				'options' => array(
					__( 'Continents', 'woocommerce' ) => $continents,
					__( 'Countries', 'woocommerce' ) => $countries,
				),
			);

			return $field_args;

		}

	}

}