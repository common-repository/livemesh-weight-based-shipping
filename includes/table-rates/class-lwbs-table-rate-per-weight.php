<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class LWBS_Table_Rate_Per_Weight.
 *
 * Rate per weight pricing option.
 *
 * @class       LWBS_Table_Rate_Per_Weight
 */
class LWBS_Table_Rate_Per_Weight extends LWBS_Table_Rate_Abstract {


	/**
	 * Constructor.
	 *
	 */
	public function __construct() {

		$this->id   = 'rate_per_weight';
		$this->name = __( 'Rate per weight', 'livemesh-wb-shipping' );

		parent::__construct();

	}

    /**
     * Output the settings HTML related for this rate option.
     *
     */
	public function output() {

		$table_rates = $this->get_table_rates();

		?><div class='rate-per-weight-wrap'>

        <div class='repeater-header lwbs-input-group-list'>
            <div class='lwbs-input-group lwbs-input-group-min-value'>
                <label>
                    <span class='label-text'><?php _e('Min weight', 'livemesh-wb-shipping'); ?></span>
                    <img class="help_tip"
                         data-tip="<?php _e('Set a minimum for total weight of products per row before the rate specified is applied.<br/>Leave empty to not set a minimum.', 'livemesh-wb-shipping'); ?>"
                         src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16"/>
                </label>
            </div>
            <div class='lwbs-input-group lwbs-input-group-max-value'>
                <label>
                    <span class='label-text'><?php _e('Max weight', 'livemesh-wb-shipping'); ?></span>
                    <img class="help_tip"
                         data-tip="<?php _e('Set a maximum for total weight of products per row before the rate specified is applied.<br/>Leave empty to not set a maximum.', 'livemesh-wb-shipping'); ?>"
                         src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16"/>
                </label>
            </div>
            <div class='lwbs-input-group lwbs-input-group-basic-cost'>
                <label>
                    <span class='label-text'><?php _e('Basic Cost', 'livemesh-wb-shipping'); ?></span>
                    <img class="help_tip"
                         data-tip="<?php _e('Set a basic rate to be applied for shipping items in the cart', 'livemesh-wb-shipping'); ?>"
                         src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16"/>
                </label>
            </div>
            <div class='lwbs-input-group'>
                <label>
                    <span class='label-text'><?php _e('Additional Cost', 'livemesh-wb-shipping'); ?></span>
                    <img class="help_tip"
                         data-tip="<?php _e('Specify additional rate to be applied for shipping items in the cart (optional)', 'livemesh-wb-shipping'); ?>"
                         src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16"/>
                </label>
            </div>
            <div class='lwbs-input-group lwbs-input-group-per-value'>
                <label>
                    <span class='label-text'><?php _e('Per', 'livemesh-wb-shipping'); ?></span>
                    <img class="help_tip"
                         data-tip="<?php _e('Provide a value in weight that needs to be multiplied by Additional Cost set for the products in the cart (optional)', 'livemesh-wb-shipping'); ?>"
                         src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16"/>
                </label>
            </div>
            <div class='lwbs-input-group lwbs-input-group-threshold-value'>
                <label>
                    <span class='label-text'><?php _e('Over', 'livemesh-wb-shipping'); ?></span>
                    <img class="help_tip"
                         data-tip="<?php _e('Specify a threshold value in weight that needs to be met before the Additional Cost multiplied by Per Value takes effect. (optional)', 'livemesh-wb-shipping'); ?>"
                         src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16"/>
                </label>
            </div>

            <div class='lwbs-input-group-inline'></div>
        </div>
			<div class='repeater-wrap'><?php


                $weight_unit = get_option('woocommerce_weight_unit');
                $currency_symbol = get_woocommerce_currency_symbol();

				$i = 0;
				if ( is_array( $table_rates ) ) :
					foreach ( $table_rates as $values ) :

						$i++;
						$min  = isset( $values['condition']['min'] ) ? esc_attr( $values['condition']['min'] ) : '';
						$max  = isset( $values['condition']['max'] ) ? esc_attr( $values['condition']['max'] ) : '';
						$base_cost = isset( $values['action']['base_cost'] ) ? esc_attr( $values['action']['base_cost'] ) : '';
                        $additional_cost = isset( $values['action']['additional_cost'] ) ? esc_attr( $values['action']['additional_cost'] ) : '';
                        $per_value = isset( $values['action']['per_value'] ) ? esc_attr( $values['action']['per_value'] ) : '';
                        $threshold_value = isset( $values['action']['threshold_value'] ) ? esc_attr( $values['action']['threshold_value'] ) : '';

						?><div class='rate-per-weight-option repeater-row lwbs-input-group-list'>

							<div class='lwbs-input-group lwbs-input-group-min-value'>
                                <span class='lwbs-input-addon lwbs-input-addon-right'><?php echo esc_html($weight_unit); ?></span>
								<input type='text' class='rate-per-weight-min lwbs_input_decimal' name='table_rates_<?php echo esc_attr( $this->id ); ?>[<?php echo absint( $i ); ?>][condition][min]' value='<?php echo esc_attr($min); ?>'>
							</div>
							<div class='lwbs-input-group lwbs-input-group-max-value'>
                                <span class='lwbs-input-addon lwbs-input-addon-right'><?php echo esc_html($weight_unit); ?></span>
								<input type='text' class='rate-per-weight-max lwbs_input_decimal' name='table_rates_<?php echo esc_attr( $this->id ); ?>[<?php echo absint( $i ); ?>][condition][max]' value='<?php echo esc_attr($max); ?>'>
							</div>
							<div class='lwbs-input-group'>
                                <span class='lwbs-input-addon lwbs-input-addon-left'><?php echo esc_html($currency_symbol); ?></span>
								<input type='text' class='rate-per-weight-cost lwbs_input_price' name='table_rates_<?php echo esc_attr( $this->id ); ?>[<?php echo absint( $i ); ?>][action][base_cost]' value='<?php echo esc_attr( wc_format_localized_price( $base_cost ) ); ?>' placeholder='0'>
							</div>
                        <div class='lwbs-input-group'>
                            <span class='lwbs-input-addon lwbs-input-addon-left'><?php echo esc_html($currency_symbol); ?></span>
                            <input type='text' class='rate-per-weight-additional-cost lwbs_input_price' name='table_rates_<?php echo esc_attr( $this->id ); ?>[<?php echo absint( $i ); ?>][action][additional_cost]' value='<?php echo esc_attr( wc_format_localized_price( $additional_cost ) ); ?>' placeholder='0'>
                        </div>
                        <div class='lwbs-input-group  lwbs-input-group-per-value'>
                            <span class='lwbs-input-addon lwbs-input-addon-right'><?php echo esc_html($weight_unit); ?></span>
                            <input type='text' class='rate-per-weight-per-value lwbs_input_decimal' name='table_rates_<?php echo esc_attr( $this->id ); ?>[<?php echo absint( $i ); ?>][action][per_value]' value='<?php echo esc_attr($per_value); ?>' placeholder='1'>
                        </div>
                        <div class='lwbs-input-group lwbs-input-group-threshold-value'>
                            <span class='lwbs-input-addon lwbs-input-addon-right'><?php echo esc_html($weight_unit); ?></span>
                            <input type='text' class='rate-per-weight-threshold-value lwbs_input_decimal' name='table_rates_<?php echo esc_attr( $this->id ); ?>[<?php echo absint( $i ); ?>][action][threshold_value]' value='<?php echo esc_attr($threshold_value); ?>' placeholder='0'>
                        </div>
							<div class='lwbs-input-group-inline'>
								<span class='dashicons dashicons-no-alt delete-repeater-row' style='line-height: 29px;'></span>
							</div>

						</div><?php

					endforeach;
				else :

					?><div class='rate-per-weight-option repeater-row lwbs-input-group-list'>
						<div class='lwbs-input-group lwbs-input-group-min-value'>
                            <span class='lwbs-input-addon lwbs-input-addon-right'><?php echo esc_html($weight_unit); ?></span>
							<input type='text' class='rate-per-weight-min lwbs_input_decimal' name='table_rates_<?php echo esc_attr( $this->id ); ?>[0][condition][min]' value=''>
						</div>
						<div class='lwbs-input-group lwbs-input-group-max-value'>
                            <span class='lwbs-input-addon lwbs-input-addon-right'><?php echo esc_html($weight_unit); ?></span>
							<input type='text' class='rate-per-weight-max lwbs_input_decimal' name='table_rates_<?php echo esc_attr( $this->id ); ?>[0][condition][max]' value=''>
						</div>
						<div class='lwbs-input-group'>
                            <span class='lwbs-input-addon lwbs-input-addon-left'><?php echo esc_html($currency_symbol); ?></span>
							<input type='text' class='rate-per-weight-cost lwbs_input_price' name='table_rates_<?php echo esc_attr( $this->id ); ?>[0][action][base_cost]' value='' placeholder='0'>
						</div>
                    <div class='lwbs-input-group'>
                        <span class='lwbs-input-addon lwbs-input-addon-left'><?php echo esc_html($currency_symbol); ?></span>
                        <input type='text' class='rate-per-weight-additional-cost lwbs_input_price' name='table_rates_<?php echo esc_attr( $this->id ); ?>[0][action][additional_cost]' value='' placeholder='0'>
                    </div>
                    <div class='lwbs-input-group  lwbs-input-group-per-value'>
                        <span class='lwbs-input-addon lwbs-input-addon-right'><?php echo esc_html($weight_unit); ?></span>
                        <input type='text' class='rate-per-weight-per-value lwbs_input_decimal' name='table_rates_<?php echo esc_attr( $this->id ); ?>[0][action][per_value]' value='' placeholder='1'>
                    </div>
                    <div class='lwbs-input-group lwbs-input-group-threshold-value'>
                        <span class='lwbs-input-addon lwbs-input-addon-right'><?php echo esc_html($weight_unit); ?></span>
                        <input type='text' class='rate-per-weight-threshold-value lwbs_input_decimal' name='table_rates_<?php echo esc_attr( $this->id ); ?>[0][action][threshold_value]' value='' placeholder='0'>
                    </div>


                    <div class='lwbs-input-group-inline'>
                        <span class='dashicons dashicons-no-alt delete-repeater-row' style='line-height: 29px;'></span>
                    </div>

					</div><?php

				endif;

			?></div>

			<a href='javascript:void(0);' class='button secondary-button add add-repeat-row'><?php _e( 'Add new', 'livemesh-wb-shipping' ); ?></a>

		</div><?php

		// Add new repeater row
		wc_enqueue_js( "
			jQuery( '.lwbs-table-rates-settings #rate_per_weight ' ).on( 'click', '.add-repeat-row', function() {

				var repeater_wrap = $( this ).prev( '.repeater-wrap' );
				var clone = repeater_wrap.find( '.repeater-row' ).first().clone();
				var repeater_index = repeater_wrap.find( '.repeater-row' ).length;
				repeater_index++;
				clone.find( '[name*=\"[condition][min]\"]' ).attr( 'name', 'table_rates_rate_per_weight[' + parseInt( repeater_index ) + '][condition][min]' ).val( '' );
				clone.find( '[name*=\"[condition][max]\"]' ).attr( 'name', 'table_rates_rate_per_weight[' + parseInt( repeater_index ) + '][condition][max]' ).val( '' );
				clone.find( '[name*=\"[action][base_cost]\"]' ).attr( 'name', 'table_rates_rate_per_weight[' + parseInt( repeater_index ) + '][action][base_cost]' ).val( '' );
				clone.find( '[name*=\"[action][additional_cost]\"]' ).attr( 'name', 'table_rates_rate_per_weight[' + parseInt( repeater_index ) + '][action][additional_cost]' ).val( '' );
				clone.find( '[name*=\"[action][per_value]\"]' ).attr( 'name', 'table_rates_rate_per_weight[' + parseInt( repeater_index ) + '][action][per_value]' ).val( '' );
				clone.find( '[name*=\"[action][threshold_value]\"]' ).attr( 'name', 'table_rates_rate_per_weight[' + parseInt( repeater_index ) + '][action][threshold_value]' ).val( '' );
				repeater_wrap.append( clone ).find( '.repeater-row' ).last().hide().slideDown( 'fast' );

			});
		" );

	}

    /**
     * Calculate the shipping costs based on the table rates specified in this Rate by Weight section
     *
     */
	public function calculate_table_rates_shipping_cost( $shipping_rate_id, $package ) {

        $shipping_cost = 0;

		$table_rates = $this->get_table_rates( $shipping_rate_id );
		$weight        = WC()->cart->get_cart_contents_weight();

		if ( is_array( $table_rates ) ) :
			foreach ( $table_rates as $values ) :

				$min  = isset( $values['condition']['min'] ) ? esc_attr( $values['condition']['min'] ) : null;
				$max  = isset( $values['condition']['max'] ) ? esc_attr( $values['condition']['max'] ) : null;
				$base_cost = isset( $values['action']['base_cost'] ) ? esc_attr( $values['action']['base_cost'] ) : null;
                $additional_cost = isset( $values['action']['additional_cost'] ) ? esc_attr( $values['action']['additional_cost'] ) : null;
                $per_value = isset( $values['action']['per_value'] ) ? esc_attr( $values['action']['per_value'] ) : null;
                $threshold_value = isset( $values['action']['threshold_value'] ) ? esc_attr( $values['action']['threshold_value'] ) : null;

				// Bail if cost is not set
				if ( empty( $base_cost ) && empty($additional_cost)) :
					continue;
				endif;

				// Bail if minimum is not set, or item weight is not met
				if ( is_null( $min ) || ( ! empty( $min ) && $weight < $min ) ) :
					continue;
				endif;

				// Bail if maximum is not set, or item weight is not met
				if ( is_null( $max ) || ( ! empty( $max ) && $weight > $max ) ) :
					continue;
				endif;

				$shipping_cost += lwbs_calculate_table_rate_shipping_cost( $weight, $base_cost, $additional_cost, $per_value, $threshold_value );

			endforeach;
		endif;

		return $shipping_cost;

	}


}
