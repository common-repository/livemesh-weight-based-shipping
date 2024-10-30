<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'LWC_Stock_Status_Condition' ) ) {

	class LWC_Stock_Status_Condition extends LWC_Condition {

		public function __construct() {
			$this->name        = __( 'Stock status', 'lwc-conditions' );
			$this->slug        = __( 'stock_status', 'lwc-conditions' );
			$this->group       = __( 'Product', 'lwc-conditions' );
			$this->description = __( 'All products in cart must match stock status', 'lwc-conditions' );

			parent::__construct();
		}

		public function match( $match, $operator, $value ) {

			$value = $this->get_value( $value );

			if ( '==' == $operator ) :

				$match = true;
				// $package['contents']
				foreach ( WC()->cart->get_cart() as $item ) :

					if ( $item['data']->get_stock_status() != $value ) :
						return false;
					endif;

				endforeach;

			elseif ( '!=' == $operator ) :

				$match = true;
				// $package['contents']
				foreach ( WC()->cart->get_cart() as $item ) :

					if ( $item['data']->get_stock_status() == $value ) :
						return false;
					endif;

				endforeach;

			endif;

			return $match;

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
