<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Get a list of the available conditions for the plugin.
 *
 */
function lwbs_get_method_conditions() {

    $conditions = array(
        __('Cart', 'livemesh-wb-shipping') => array(
            'subtotal' => __('Subtotal', 'livemesh-wb-shipping'),
            'subtotal_ex_tax' => __('Subtotal ex. taxes', 'livemesh-wb-shipping'),
            'tax' => __('Tax', 'livemesh-wb-shipping'),
            'quantity' => __('Quantity', 'livemesh-wb-shipping'),
            'contains_product' => __('Contains product', 'livemesh-wb-shipping'),
            'coupon' => __('Coupon', 'livemesh-wb-shipping'),
            'weight' => __('Weight', 'livemesh-wb-shipping'),
            'contains_shipping_class' => __('Contains shipping class', 'livemesh-wb-shipping'),
        ),
        __('User Details', 'livemesh-wb-shipping') => array(
            'zipcode' => __('Zipcode', 'livemesh-wb-shipping'),
            'city' => __('City', 'livemesh-wb-shipping'),
            'state' => __('State', 'livemesh-wb-shipping'),
            'country' => __('Country', 'livemesh-wb-shipping'),
            'role' => __('User role', 'livemesh-wb-shipping'),
        ),
        __('Product', 'livemesh-wb-shipping') => array(
            'width' => __('Width', 'livemesh-wb-shipping'),
            'height' => __('Height', 'livemesh-wb-shipping'),
            'length' => __('Length', 'livemesh-wb-shipping'),
            'stock' => __('Stock', 'livemesh-wb-shipping'),
            'stock_status' => __('Stock status', 'livemesh-wb-shipping'),
            'category' => __('Category', 'livemesh-wb-shipping'),
        ),
    );
    $conditions = apply_filters('lwbs_conditions', $conditions);

    return $conditions;
}


/**
 * Check if the current page related to Livemesh Weight Based Shipping plugin?
 *
 */
function is_lwbs_page() {
    $return = false;

    // Shipping instance
    if (isset($_GET['tab'], $_GET['instance_id']) && $_GET['tab'] === 'shipping') {
        $instance_id = absint($_GET['instance_id']);
        $shipping_method = WC_Shipping_Zones::get_shipping_method($instance_id);
        if ($shipping_method->id === 'livemesh_weight_based_shipping') {
            $return = true;
        }
    }

    return $return;
}


if (!defined('ABSPATH'))
    exit; // Exit if accessed directly


/**
 * Calculate Table Rates shipping costs based on weight of the items in the cart and the
 * rates provided by the user in the rates table
 *
 */
function lwbs_calculate_table_rate_shipping_cost($weight, $base_cost, $additional_cost, $per_value, $threshold_value) {

    $base_cost = str_replace(array('-'), '', $base_cost);
    $base_cost = (float) str_replace(',', '.', $base_cost);

    $additional_cost = str_replace(array('-'), '', $additional_cost);
    $additional_cost = (float) str_replace(',', '.', $additional_cost);

    // Ensure per value is 1 or greater.
    $per_value = max(absint($per_value), 1);

    $threshold_value = absint($threshold_value);

    // If no additional cost is specified, related values do not matter
    if ($additional_cost > 0):
        // Threshold value can be 0 or more
        $additional_weight = $weight - $threshold_value;
        // Per value can be 1 or more. Round up the value as done in other plugins
        $per_weight = ceil($additional_weight / $per_value);
        $cost = $additional_cost * $per_weight;
    endif;

    // Add the base cost at the end
    $cost += $base_cost;

    return apply_filters('lwbs_calculate_table_rate_shipping_cost', $cost, $weight, $base_cost, $additional_cost, $per_value, $threshold_value);
}
