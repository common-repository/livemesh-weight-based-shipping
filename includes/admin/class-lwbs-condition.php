<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Condition class.
 *
 * Represents a single condition in a condition group.
 *
 */
class LWBS_Condition {


    /**
     * Condition ID.
     */
    public $id;

    public $condition;

    public $operator;

    public $value;

    /**
     * Group ID.
     *
     */
    public $group;

    public function __construct($id = null, $group = 0, $condition = null, $operator = null, $value = null) {

        $this->id = $id;
        $this->group = $group;
        $this->condition = $condition;
        $this->operator = $operator;
        $this->value = $value;

        if (!$id) {
            $this->id = rand();
        }

    }


    /**
     *
     * Output the full condition row which includes: condition, operator, value, add/delete buttons and
     * the description.
     *
     */
    public function output_condition_row() {

        $lwc_condition = $this;
        require 'views/html-condition-row.php';

    }


    /**
     *
     * Get a list with the available conditions.
     */
    public function get_conditions() {
        return lwbs_get_method_conditions();

    }

    /**
     *
     * Get a list with the available operators for the conditions.
     *
     */
    public function get_operators() {
        $lwc_condition = lwc_get_condition($this->condition);
        return apply_filters('lwbs_operators', $lwc_condition->get_operators(), $lwc_condition);
    }

    /**
     *
     * Get the value field args that are condition dependent. This usually includes
     * type, class and placeholder.
     *
     */
    public function get_value_field_args() {

        // Defaults
        $default_field_args = array(
            'name' => 'conditions[' . absint($this->group) . '][' . absint($this->id) . '][value]',
            'placeholder' => '',
            'type' => 'text',
            'class' => array('lwc-value'),
        );

        $field_args = $default_field_args;
        if ($condition = lwc_get_condition($this->condition)) {
            $field_args = wp_parse_args($condition->get_value_field_args(), $default_field_args);
        }

        if ($this->condition == 'contains_product' && $product = wc_get_product($this->value)) {
            $field_args['options'][$this->value] = $product->get_formatted_name(); // WC >= 2.7
        }

        $field_args = apply_filters('lwbs_values', $field_args, $this->condition);

        return $field_args;

    }


    /**
     *
     * Return the description for this condition.
     *
     */
    public function get_description() {
        $descriptions = apply_filters('lwbs_descriptions', lwc_condition_descriptions());
        return isset($descriptions[$this->condition]) ? $descriptions[$this->condition] : '';
    }

}
