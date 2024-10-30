<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

if (class_exists('Livemesh_Weight_Based_Shipping_Method'))
    return; // Stop if the class already exists

/**
 * Class Livemesh_Weight_Based_Shipping_Method.
 *
 *  Livemesh Weight Based Shipping method for WooCommerce
 *
 */
class Livemesh_Weight_Based_Shipping_Method extends WC_Shipping_Method {

    public function __construct($instance_id = 0) {

        parent::__construct($instance_id);

        $this->id = 'livemesh_weight_based_shipping';
        $this->method_title = __('Livemesh Weight Based Shipping', 'livemesh-wb-shipping');
        $this->method_description = __('Flexible and conditional shipping rate', 'livemesh-wb-shipping');
        $this->supports = array('shipping-zones', 'instance-settings');

        $this->init();

        add_filter('woocommerce_shipping_' . 'livemesh_weight_based_shipping' . '_instance_settings_values', array($this, 'save_settings'), 10, 2);

        do_action('woocommerce_livemesh_weight_based_shipping_method_init', $this);
    }

    /**
     *
     * Initialize the fields and settings part of Livemesh Weight Based Shipping method.
     *
     */
    function init() {

        $this->init_form_fields();

        $this->instance_form_fields = $this->get_instance_settings();

        $this->title = $this->get_instance_option('title', $this->method_title);
    }


    /**
     * Get the instance settings array for this shipping method.
     *
     */
    public function get_instance_settings() {

        return array(
            'title' => array(
                'title' => __('Title', 'livemesh-wb-shipping'),
                'type' => 'title_text',
                'placeholder' => __('Shipping title', 'livemesh-wb-shipping'),
            ),
            'livemesh_weight_based_shipping_settings' => array(
                'type' => 'livemesh_weight_based_shipping_settings',
                'sanitize_callback' => array($this, 'sanitize_livemesh_shipping_settings')
            ),

            /*
            Do not output anything for these fields by using 'none' type as we output a custom settings HTML as part of
            livemesh_weight_based_shipping_settings above, but the below will help us utilize the get/set functionality of $instance_settings
            */
            'conditions' => array('type' => 'none'),
            'taxable' => array('type' => 'none'),
            'minimum_cost' => array('type' => 'none'),
            'maximum_cost' => array('type' => 'none'),
        );
    }

    /**
     * Override to actually check for key / prevent undefined notice with the custom fields.
     *
     * This method is documented in abstract-wc-shipping-method.php
     *
     * @see WC_Shipping_Method::get_instance_option( $key, $empty_value )
     */
    public function get_instance_option($key, $empty_value = null) {

        if (empty($this->instance_settings)) {
            $this->init_instance_settings();
        }

        if (isset($this->instance_settings[$key])) {
            return parent::get_instance_option($key, $empty_value);
        }
        else {
            return $empty_value;
        }
    }

    /**
     * Validate method conditions fields.
     *
     */
    public function validate_conditions_field($key, $value) {
        return lwc_sanitize_conditions($_POST[$key]);
    }

    /**
     * Validate the settings for the other fields.
     *
     */
    public function validate_none_field($key, $value) {

        $value = wc_clean($_POST['_lwbs_shipping_method'][$key]);

        switch ($key) {
            case 'minimum_cost' :
            case 'maximum_cost' :
                $value = wc_format_decimal($value);
                break;

            case 'taxable' :
                $value = 'taxable' == $value ? 'taxable' : 'not_taxable';
                break;

            case 'shipping_title' :
            default :
                $value = $this->validate_text_field($key, $value);
                break;
        }

        return $value;
    }

    /**
     * Don't output anything for 'none' field types here.
     */
    public function generate_none_html() {
    }

    /**
     * Load the instance settings and render the 'livemesh_weight_based_shipping_settings' field type.
     *
     */
    public function generate_livemesh_weight_based_shipping_settings_html($key, $data) {
        $condition_groups = $this->get_instance_option('conditions');
        $shipping_title = $this->get_instance_option('shipping_title');
        $taxable = $this->get_instance_option('taxable');
        $minimum_cost = $this->get_instance_option('minimum_cost');
        $maximum_cost = $this->get_instance_option('maximum_cost');

        ob_start();
        ?>
        </table><!-- Close the table -->

        <div class="lwbs-shipping-method-wrap" style="margin-right: 300px;" id="poststuff">
            <div id="lwbs_conditions" class="postbox ">
                <h2 class="" style="border-bottom: 1px solid #eee;">
                    <span><?php _e('Conditions', 'livemesh-wb-shipping'); ?></span></h2>
                <div class="inside"><?php
                    require_once LWBS_PLUGIN_DIR . '/includes/admin/views/shipping-conditions.php';
                    ?></div>
            </div>

            <div id="lwbs_settings" class="postbox ">
                <style>.lwbs-shipping-title { display: none; }</style>
                <h2 class="" style="border-bottom: 1px solid #eee;">
                    <span><?php _e('Settings', 'livemesh-wb-shipping'); ?></span></h2>
                <div class="inside"><?php
                    require_once LWBS_PLUGIN_DIR . '/includes/admin/views/shipping-settings.php';
                    ?></div>
            </div>
        </div>
        <table class="form-table"><!-- Re-open table tab --><?php

        return ob_get_clean();
    }


    /**
     * Generate the HTML for the title text field.
     *
     */
    public function generate_title_text_html($key, $data) {

        $field_key = $this->get_field_key($key);
        $defaults = array(
            'title' => 'Shipping Settings',
            'class' => '',
            'css' => '',
            'placeholder' => '',
            'type' => 'text',
            'custom_attributes' => array(),
        );

        $data = wp_parse_args($data, $defaults);

        ob_start();
        ?></table>
        <div class="lwbs-title-text-wrap">
            <legend class="screen-reader-text"><span><?php echo wp_kses_post($data['title']); ?></span></legend>
            <input class="input-text regular-input lwbs-title-text <?php echo esc_attr($data['class']); ?>"
                   type="text" name="<?php echo esc_attr($field_key); ?>"
                   id="<?php echo esc_attr($field_key); ?>" style="<?php echo esc_attr($data['css']); ?>"
                   value="<?php echo esc_attr($this->get_option($key)); ?>"
                   placeholder="<?php echo esc_attr($data['placeholder']); ?>"
                <?php echo wp_kses_post($this->get_custom_attribute_html($data)); ?> />
        </div>
        <table class="form-table"><!-- Re-open table tab --><?php
        return ob_get_clean();
    }

    /**
     * Save the custom instance settings related to rates table that are not being saved as part of $this->instance_form_fields;
     *
     */
    public function save_settings($settings, $class) {

        $post_data = $class->get_post_data();

        // Save pricing options
        foreach (LWBS()->table_rates_helper->get_table_rates_options() as $key => $option) {
            $name = 'table_rates_' . esc_attr($option->id);
            $save_value = isset($post_data[$name]) ? $post_data[$name] : array();

            array_walk_recursive($save_value, 'sanitize_text_field');
            $class->instance_settings[$name] = $save_value;
        }

        return $class->instance_settings;

    }

    /**
     * Calculate the shipping costs by using the table rates specified by the user.
     *
     */
    protected function calculate_shipping_cost($package, $rate_id, $args) {

        $minimum_cost = (float)$args['minimum_cost'];
        $maximum_cost = (float)$args['maximum_cost'];

        $cost = 0;

        /** @var LWBS_Table_Rate_Abstract $option */
        foreach (LWBS()->table_rates_helper->get_table_rates_options() as $option) {
            $cost += $option->calculate_table_rates_shipping_cost($rate_id, $package);
        }

        // if minimum or maximum value is specified
        if ($minimum_cost > 0) {
            $cost = max($minimum_cost, $cost);
        }
        if ($maximum_cost > 0) {
            $cost = min($maximum_cost, $cost);
        }

        return apply_filters('lwbs_calculate_shipping_costs', $cost, $package, $rate_id, $this);

    }


    /**
     * If conditions specified by the user match, calculate the shipping cost and then add the shipping rate with taxes
     *
     */
    public function calculate_shipping($package = array()) {

        $conditions = $this->get_instance_option('conditions');

        // Ensure conditions match
        if (lwc_match_conditions($conditions, array('context' => 'lwbs', 'package' => $package))) {

            $cost = $this->calculate_shipping_cost($package, $this->get_rate_id(), array(
                'minimum_cost' => $this->get_instance_option('minimum_cost'),
                'maximum_cost' => $this->get_instance_option('maximum_cost'),
            ));

            // Add rates added through the WC Zones
            $rate_args = apply_filters('lwbs_shipping_rate', array(
                'id' => $this->get_rate_id(),
                'label' => $this->get_instance_option('title'),
                'cost' => $cost,
                'taxes' => ('taxable' == $this->get_instance_option('taxable')) ? '' : false,
                'calc_tax' => 'per_order',
                'package' => $package,
            ), $package, $this);

            $this->add_rate($rate_args);
        }
    }
}
