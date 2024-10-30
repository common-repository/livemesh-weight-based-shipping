<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'LWC_Stock_Condition' ) ) {

	class LWC_Stock_Condition extends LWC_Condition {

		public function __construct() {
			$this->name        = __( 'Stock', 'lwc-conditions' );
			$this->slug        = __( 'stock', 'lwc-conditions' );
			$this->group       = __( 'Product', 'lwc-conditions' );
			$this->description = __( 'Compared against the product with the lowest stock amount.', 'lwc-conditions' );

			parent::__construct();
		}

		public function get_value_for_comparison() {

			$stock = array();

			// $package['contents']
			foreach ( WC()->cart->get_cart() as $item ) :

				/** @var $product WC_Product */
				$product = $item['data'];
				$stock[] = $product->get_stock_quantity();

			endforeach;

			// Get lowest value
			return empty( $stock ) ? null : min( $stock );

		}

	}

}