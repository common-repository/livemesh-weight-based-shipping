<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

?>
<div class='lwc-conditions'>
    <div class='lwc-condition-groups'>

        <p>
            <strong><?php _e('Match one of the condition groups to allow this shipping rate:', 'livemesh-wb-shipping'); ?></strong>
        </p><?php

        if (!empty($condition_groups)) :
            foreach ($condition_groups as $condition_group => $conditions) :
                include 'html-condition-group.php';
            endforeach;
        else :
            $condition_group = '0';
            include 'html-condition-group.php';
        endif;

        ?></div>

    <div class='lwc-condition-group-template hidden' style='display: none'><?php
        $condition_group = '9999';
        $conditions = array();
        include 'html-condition-group.php';
        ?></div>
    <a class='button lwc-condition-group-add'
       href='javascript:void(0);'><?php _e('Add \'Or\' group', 'livemesh-wb-shipping'); ?></a>
</div>
