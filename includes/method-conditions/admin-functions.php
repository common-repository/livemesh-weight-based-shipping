<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly


if (!function_exists('lwc_admin_enqueue_scripts')) {

    /**
     * Register the general LWC scripts that can be used as a dependency.
     *
     */
    function lwc_admin_enqueue_scripts() {

        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        wp_register_script('lwc-repeater', plugins_url('assets/js/repeater/jquery.repeater' . $suffix . '.js', __FILE__), array('jquery'), '1.0.0', true);
        wp_register_script('lwc-conditions', plugins_url('assets/js/lwc-conditions' . $suffix . '.js', __FILE__), array('jquery', 'lwc-repeater', 'select2'), '1.0.0', true);

        wp_localize_script('lwc-conditions', 'lwc', array(
            'nonce' => wp_create_nonce('lwc-ajax-nonce'),
            'condition_operators' => lwc_condition_operators(),
            'condition_descriptions' => lwc_condition_descriptions(),
        ));

    }

    add_action('admin_enqueue_scripts', 'lwc_admin_enqueue_scripts', 5);

}

if (!function_exists('lwc_html_field')) {

    /**
     * Output a new HTML field.
     *
     * @param array $args
     */
    function lwc_html_field($args = array()) {

        $args = wp_parse_args($args, array(
            'id' => '',
            'type' => 'text',
            'name' => '',
            'class' => '',
            'value' => null,
            'default' => '',
            'placeholder' => '',
            'custom_attributes' => isset($args['custom_attr']) ? $args['custom_attr'] : array(), // BC
        ));

        $type = !empty($args['field']) ? $args['field'] : $args['type'];
        $class = is_array($args['class']) ? implode(' ', array_map('sanitize_html_class', $args['class'])) : sanitize_html_class($args['class']);
        $value = isset($args['value']) ? $args['value'] : '';
        $name = isset($args['name']) ? $args['name'] : $args['id'];
        $custom_attributes = array();
        if (!empty($args['custom_attributes']) && is_array($args['custom_attributes'])) {
            foreach ($args['custom_attributes'] as $k => $v) {
                $custom_attributes[$k] = esc_attr($k) . '="' . esc_attr($v) . '"';
            }
        }

        switch ($type) :

            case 'text' :
            case 'number' :
                ?><input
                name="<?php echo esc_attr($name); ?>"
                type="<?php echo esc_attr($args['type']); ?>"
                id="<?php echo esc_attr($args['id']); ?>"
                value="<?php echo esc_attr($value); ?>"
                class="input-text <?php echo esc_attr($class); ?>"
                placeholder="<?php echo esc_attr($args['placeholder']); ?>"
                <?php echo wp_kses_post(implode(' ', $custom_attributes)); ?>
                ><?php
                break;

            case 'dropdown' :
            case 'select' :

                $options = is_array($args['options']) ? $args['options'] : array();

                ?><select
                name="<?php echo esc_attr($args['name']); ?>"
                id="<?php echo esc_attr($args['id']); ?>"
                class="input-select <?php echo esc_attr($class); ?>"
                <?php echo implode(' ', $custom_attributes); ?>
                ><?php

                foreach ($options as $index => $values) :

                    if (!is_array($values)) :
                        ?>
                        <option
                        value='<?php echo esc_attr($index); ?>' <?php selected(in_array($index, (array)$value)); ?>><?php echo esc_attr($values); ?></option><?php
                    else :
                        ?>
                        <optgroup label='<?php echo esc_attr($index); ?>'><?php
                        foreach ($values as $k => $v) :
                            ?>
                            <option
                            value='<?php echo esc_attr($k); ?>' <?php selected(in_array($k, (array)$value)); ?>><?php echo esc_attr($v); ?></option><?php
                        endforeach;
                        ?></optgroup><?php
                    endif;

                endforeach;

                if (empty($options)) :
                    ?>
                    <option readonly disabled><?php
                    _e('There are no options available', 'lwc-conditions');
                    ?></option><?php
                endif;

                ?></select><?php
                break;

            case 'product' :
                if ($product = wc_get_product($value)) {
                    $args['custom_attributes']['data-selected'] = $product->get_formatted_name();
                }
                $args['type'] = 'text';
                lwc_html_field($args);
                break;

            default :
            case 'hook' :

                do_action('lwc-conditions/html_field_hook', $args['type'], $args);
                do_action('lwc-conditions/html_field_type_' . $args['type'], $args);
                break;

        endswitch;

    }


}
