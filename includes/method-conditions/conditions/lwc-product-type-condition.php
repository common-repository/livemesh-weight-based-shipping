<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'LWC_Product_Type_Condition' ) ) {

	class LWC_Product_Type_Condition extends LWC_Condition {

		public function __construct() {
			$this->name        = __( 'Product type', 'lwc-conditions' );
			$this->slug        = __( 'product', 'lwc-conditions' );
			$this->group       = __( 'Product', 'lwc-conditions' );
			$this->description = __( 'Compare against the current product type', 'lwc-conditions' );

			parent::__construct();
		}

		public function validate() {
			return isset( $GLOBALS['product'] );
		}

		public function get_value_for_comparison() {
			/** @var $product WC_Product */
			global $product;
			return $product->get_type();
		}

		public function get_operators() {

			$operators = parent::get_operators();

			unset( $operators['>='] );
			unset( $operators['<='] );

			return $operators;

		}

		public function get_value_field_args() {

			$field_args['type'] = 'select';

			$product_types = get_terms( 'product_type', array( 'hide_empty' => false ) );
			foreach ( $product_types as $type ) {
				$field_args['options'][ $type->slug ] = $type->name;
			}

			return $field_args;

		}

	}

}