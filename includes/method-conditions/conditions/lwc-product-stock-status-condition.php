<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'LWC_Product_Stock_Status_Condition' ) ) {

	class LWC_Product_Stock_Status_Condition extends LWC_Condition {

		public function __construct() {
			$this->name        = __( 'Product Stock Status', 'lwc-conditions' );
			$this->slug        = __( 'product_stock_status', 'lwc-conditions' );
			$this->group       = __( 'Product', 'lwc-conditions' );
			$this->description = __( 'Compare against the product stock status', 'lwc-conditions' );

			parent::__construct();
		}

		public function validate() {
			return isset( $GLOBALS['product'] );
		}

		public function get_value( $value ) {
			$value = ( $value == '1' ) ? 'instock' : $value;
			$value = ( $value == '0' ) ? 'outofstock' : $value;
			return $value;
		}

		public function get_value_for_comparison() {
			/** @var $product WC_Product */
			global $product;

			return $product->get_stock_status();
		}

		public function get_operators() {

			$operators = parent::get_operators();

			unset( $operators['>='] );
			unset( $operators['<='] );

			return $operators;

		}

		public function get_value_field_args() {

			$field_args = array(
				'type'    => 'select',
				'options' => array(
					'1' => __( 'In stock', 'woocommerce' ),
					'0' => __( 'Out of stock', 'woocommerce' ),
					'onbackorder' => __( 'On backorder', 'woocommerce' ),
				),
			);

			return $field_args;

		}

	}

}