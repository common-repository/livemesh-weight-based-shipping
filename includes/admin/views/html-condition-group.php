<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

?>
<div class='lwc-condition-group-wrap'>
    <p class='or-text'><strong><?php _e('Or', 'livemesh-wb-shipping'); ?></strong></p>

    <div class='lwc-condition-group clearfix' data-group='<?php echo absint($condition_group); ?>'>

		<span class='lwc-condition-group-actions alignright'>
			<a href='javascript:void(0);' class='duplicate'><?php _e('Duplicate', 'livemesh-wb-shipping'); ?></a>&nbsp;|&nbsp;<a
                    href='javascript:void(0);' class='delete'><?php _e('Delete', 'livemesh-wb-shipping'); ?></a>
		</span>
        <p class='match-text'><?php _e('Match all of the following rules to apply the shipping rate:', 'livemesh-wb-shipping'); ?></p>

        <div class='lwc-conditions-list'><?php

            if (!empty($conditions)) :

                foreach ($conditions as $condition_id => $condition) :
                    $lwc_condition = new LWBS_Condition($condition_id, $condition_group, $condition['condition'], $condition['operator'], $condition['value']);
                    $lwc_condition->output_condition_row();
                endforeach;

            else :

                $lwc_condition = new LWBS_Condition(null, $condition_group);
                $lwc_condition->output_condition_row();

            endif;

            ?></div>

        <div class="lwc-condition-template hidden" style="display: none;"><?php
            $lwc_condition = new LWBS_Condition('9999', $condition_group);
            $lwc_condition->output_condition_row();
            ?></div>
        <a style="margin-top: 0.5em; margin-right: 63px;" class='lwc-condition-add lwc-add button alignright'
           data-group='<?php echo absint($condition_group); ?>'
           href='javascript:void(0);'><?php _e('Add condition', 'livemesh-wb-shipping'); ?></a>

    </div>
</div>