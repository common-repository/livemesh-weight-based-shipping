<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Display the shipping settings in shipping method edit page
 *
 */


?>
<div class='lwbs lwbs_settings lwbs_shipping_settings'>

    <p class='lwbs-option lwbs-shipping-title'>
        <label for='shipping_title'><?php _e('Shipping title', 'livemesh-wb-shipping'); ?></label>
        <input
                type='text'
                class=''
                id='shipping_title'
                name='_lwbs_shipping_method[shipping_title]'
                style='width: 190px;'
                value='<?php echo esc_attr($shipping_title); ?>'
                placeholder='<?php _e('E.g. Expedited shipping', 'livemesh-wb-shipping'); ?>'
        >
    </p>

    <p class='lwbs-option'>
        <label for='taxable'><?php _e('Tax status', 'livemesh-wb-shipping'); ?></label>
        <select name='_lwbs_shipping_method[taxable]' style='width: 189px;'>
            <option value='taxable' <?php selected($taxable, 'taxable'); ?>><?php _e('Taxable', 'livemesh-wb-shipping'); ?></option>
            <option value='not_taxable' <?php selected($taxable, 'not_taxable'); ?>><?php _e('Not taxable', 'livemesh-wb-shipping'); ?></option>
        </select>
    </p>

    <p class='lwbs-option'>
        <label for='minimum_cost'><?php _e('Minimum cost', 'livemesh-wb-shipping'); ?></label>
        <span class='lwc-currency'><?php echo get_woocommerce_currency_symbol(); ?></span>
        <input
                type='text'
                step='any'
                class='wc_input_price'
                id='minimum_cost'
                name='_lwbs_shipping_method[minimum_cost]'
                value='<?php echo esc_attr(wc_format_localized_price($minimum_cost)); ?>'
                placeholder='0'>
        <img class="help_tip"
             data-tip="<?php _e('Set a minimum rate to be set if the calculated rate is lower than the minimum set here.<br/>Leave zero to not set a minimum.', 'livemesh-wb-shipping'); ?>"
             src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16"/>
    </p>

    <p class='lwbs-option'>
        <label for='maximum_cost'><?php _e('Maximum cost', 'livemesh-wb-shipping'); ?></label>
        <span class='lwc-currency'><?php echo get_woocommerce_currency_symbol(); ?></span>
        <input
                type='text'
                step='any'
                class='wc_input_price'
                id='maximum_cost'
                name='_lwbs_shipping_method[maximum_cost]'
                value='<?php echo esc_attr(wc_format_localized_price($maximum_cost)); ?>'
                placeholder='0'>
        <img class="help_tip"
             data-tip="<?php _e('Set a maximum rate to be set if the calculated rate is higher than the maximum set here.<br/>Leave zero to not set a maximum.', 'livemesh-wb-shipping'); ?>"
             src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16"/>
    </p>

    <h3 style='padding-left:0;'><?php _e('Table Rates', 'livemesh-wb-shipping'); ?></h3>

    <div class='lwbs-table-rates-settings-wrap'><?php

        $table_rates_options = LWBS()->table_rates_helper->get_table_rates_options();

        $first_option = reset($table_rates_options);
        ?>
        <div class='lwbs-table-rates-settings'>

            <div class='inside'>

                <div class='lwbs-table-rates-sections-wrap'>

                    <div class='lwbs-table-rates-sections'><?php

                        /** @var LWBS_Table_Rate_Abstract $table_rates_option */
                        foreach ($table_rates_options as $table_rates_option) :
                            $name = $table_rates_option->name;
                            ?>
                        <div id='<?php echo esc_attr($table_rates_option->id); ?>' class='lwbs-table-rates-section'>
                            <h3 class='lwbs-table-rate-option'><?php echo esc_html($name); ?></h3><?php
                            $table_rates_option->output();
                            ?></div><?php
                        endforeach;

                        ?></div>

                    <div class='clear'></div>

                </div>

            </div>

        </div>

    </div>

    <?php if (lwbs_fs()->is_not_paying()) : ?>
        <section class="lwbs-upgrade-notice">
            <h3><?php echo esc_html__('Awesome Premium Features with top-notch support!', 'livemesh-wb-shipping'); ?></h3>
            <p><?php echo esc_html__('Set weight based rates specific to Shipping Class, Category or a Product!', 'livemesh-wb-shipping'); ?></p>
            <a href="<?php echo lwbs_fs()->get_upgrade_url(); ?>"><?php echo __('Upgrade to the Premium Version!', 'livemesh-wb-shipping'); ?></a>
        </section>
    <?php endif; ?>

</div>
