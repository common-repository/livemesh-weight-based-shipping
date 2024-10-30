<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'LWC_Product_Stock_Condition' ) ) {

	class LWC_Product_Stock_Condition extends LWC_Condition {

		public function __construct() {
			$this->name        = __( 'Product Stock', 'lwc-conditions' );
			$this->slug        = __( 'product_stock', 'lwc-conditions' );
			$this->group       = __( 'Product', 'lwc-conditions' );
			$this->description = __( 'Compare against the product stock', 'lwc-conditions' );
			// Alias: stock_quantity

			parent::__construct();
		}

		public function validate() {
			return isset( $GLOBALS['product'] );
		}

		// @todo - Improve
		public function get_value_for_comparison() {
			/** @var $product WC_Product */
			global $product;
//			return $product->get_stock_quantity();

			$stock_levels = array();

			if ( $product->is_type( 'variable' ) ) {
				foreach ( $product->get_children() as $variation ) {
					$var = wc_get_product( $variation );
					$stock_levels[] = $var->get_stock_quantity();
				}
			} else {
				$stock_levels[] = $product->get_stock_quantity();
			}

			return max( $stock_levels );

		}

	}

}