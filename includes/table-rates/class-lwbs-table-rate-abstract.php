<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class LWBS_Table_Rate_Abstract.
 *
 * Abstract class to add new LWBS Rate Option.
 *
 */
abstract class LWBS_Table_Rate_Abstract {


	/**
	 * ID of the Rate option
     *
	 */
	public $id;


	/**
	 * Name of the Rate option.
	 *
	 */
	public $name;

	public function __construct() {
	}

	/**
	 * Get the table rates set by the user for the rate id provided
	 *
	 */
	public function get_table_rates( $rate_id = null ) {

		if ( isset( $_GET['instance_id'] ) || strpos( $rate_id, ':' ) ) {
			$instance_id = isset( $_GET['instance_id'] ) ? sanitize_key($_GET['instance_id']) : substr( $rate_id, strpos( $rate_id, ':' ) + 1 );

			if ( $shipping_method = WC_Shipping_Zones::get_shipping_method( absint( $instance_id ) ) ) {
				return $shipping_method->get_instance_option( 'table_rates_' . $this->id );
			}
		}

		return null;

	}


	/**
	 * Output the settings HTML for this rate option.
	 *
	 */
	abstract function output();


	/**
	 * Abstract method to override for calculating shipping costs for the rate option chosen.
     * Returns the cost only if min/max values are met
	 *
	 *
	 */
	abstract function calculate_table_rates_shipping_cost( $shipping_rate_id, $package );


	/**
	 * Get the weight to compare the min/max values against. 
	 *
	 * - The min/max field requirement is based on the weight of the relevant products.
	 *
	 */
	public function get_weight( $package, $value = null ) {

		$quantity = 0;

		foreach ( $this->get_relevant_products( $package, $value ) as $cart_key => $item ) :

			if (!empty($item['data']->get_weight())) {
                $quantity += $item['data']->get_weight() * $item['quantity'];
            }

		endforeach;

		return $quantity;

	}


	/**
	 * Get the relevant products from the cart which match the product, category
     * or shipping class specified for shipping costs calculation.
     * By default, it returns all products in the cart as required by rate by weight option.
	 *
	 *
	 */
	public function get_relevant_products( $package, $value = null ) {

		$relevant_products = array();

		foreach ( $package['contents'] as $cart_key => $item ) :
			$relevant_products[ $cart_key ] = $item;
		endforeach;

		return $relevant_products;

	}

}
