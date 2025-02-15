jQuery( function ( $ ) {

    /* ------------  Condition validation -----------------*/

    $( '.lwc-condition-groups' ).on( 'change validate-conditions', '.lwc-condition-group', function () {

        $( this ).find( '.condition-error, .condition-warning' ).unbind( 'hover' ).removeClass( 'condition-error condition-warning' );

        $( this ).each( function ( i ) {
            var $rows = $( this ).find( '.lwc-condition-wrap' );
            var $conditions = $.map( $rows, function ( elem ) {
                return {
                    key: $( elem ).find( '.lwc-condition option:selected' ).val(),
                    operator: $( elem ).find( '.lwc-operator option:selected' ).val()
                }
            } );

            // Duplicate conditions that are not possible
            $.each( [ 'city', 'state', 'country', 'role' ], function ( index, key ) {
                var checkConditions = $conditions.filter( function ( condition ) {
                    return condition.key == key && condition.operator == '=='
                } );
                if (checkConditions.length >= 2) {
                    var errorConditions = $rows.find( '.lwc-condition option[value=' + key + ']:selected' ).not( ':first' ).parents( '.lwc-condition' );
                    errorConditions.addClass( 'condition-error' );
                    errorConditions.attr( 'data-tip', 'Having two of these conditions with the \'Equal to\' operator in one condition group will give a conflict.' );
                }
            } );

            // 2+ category conditions
            var checkConditions = $conditions.filter( function ( condition ) {
                return condition.key == 'category' && condition.operator == '=='
            } );
            if (checkConditions.length >= 2) {
                var warningConditions = $rows.find( '.lwc-condition option[value=category]:selected' ).not( ':first' ).parents( '.lwc-condition' );
                warningConditions.addClass( 'condition-warning' );
                warningConditions.attr( 'data-tip', 'The \'category\' condition requires ALL products to have the given category. Having two or more required categories is a unusual scenario.' );
            }

            $( '.condition-warning, .condition-error' ).tipTip( {
                'attribute': 'data-tip',
                'fadeIn': 50,
                'fadeOut': 50,
                'delay': 200
            } );

        } );

    } );
    $( '.lwc-condition-group' ).trigger( 'validate-conditions' );


    /* --------- Table Rates -------------------- */

    // Delete repeater row in the rates table
    $( '.lwbs-table-rates-settings' ).on( 'click', '.delete-repeater-row', function () {
        $( this ).parents( '.repeater-row' ).slideUp( function () {
            $( this ).remove();
        } );
    } );

    /*-------  Price field input ---------- */
    $( document.body ).on( 'change keyup', '.lwbs_input_decimal[type=text]', function () {
        var regex = new RegExp( '[^\-0-9\%\\\.\,]+', 'gi' );
        var error = 'i18n_decimal_error';
        var value = $( this ).val();
        var newvalue = value.replace( regex, '' );

        if (value !== newvalue) {
            $( this ).val( newvalue );
            $( document.body ).triggerHandler( 'wc_add_error_tip', [ $( this ), error ] );
        } else {
            $( document.body ).triggerHandler( 'wc_remove_error_tip', [ $( this ), error ] );
        }
    } );

    /*------------  Price input validation / error handling ------------- */
    $( document.body ).on( 'blur', '.lwbs_input_price[type=text]', function () {
        $( '.wc_error_tip' ).fadeOut( '100', function () {
            $( this ).remove();
        } );
    } )
        .on( 'keyup change', '.lwbs_input_price[type=text]', function () {
            var value = $( this ).val();
            var regex = new RegExp( '[^0-9\/\\-\\%\*\\' + woocommerce_admin.mon_decimal_point + ']+', 'gi' );
            var newvalue = value.replace( regex, '' );

            if (value !== newvalue) {
                $( this ).val( newvalue );
                $( document.body ).triggerHandler( 'wc_add_error_tip', [ $( this ), 'i18n_mon_decimal_error' ] );
            } else {
                $( document.body ).triggerHandler( 'wc_remove_error_tip', [ $( this ), 'i18n_mon_decimal_error' ] );
            }
        } );

} );
