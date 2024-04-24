/**
 * landkit.js
 *
 * Handles behaviour of the theme
 */
 ( function( $, window ) {
    'use strict';
        
    $( '.login-register-tab-switcher' ).on( 'click', function (e) {
        e.preventDefault();
        $( '#customer_login > .woocommerce-notices-wrapper' ).hide();
        $( this ).removeClass( 'active' );
        $( this ).tab( 'show' )
    });

    var hash_value = window.location.hash;

    switch( hash_value ) {
        case '#customer-login-form': 
        case '#forget-password-form':
            $( 'a.login-register-tab-switcher[href="' + hash_value + '"]' ).trigger( 'click' );
        break;
    }

    //grabs the hash tag from the url
    var hash = window.location.hash;
    //checks whether or not the hash tag is set
    if (hash != "") {
        //removes all active classes from tabs
        $('.lk-tab-content div').each(function() {
           $(this).removeClass('active');
        });
        //this will add the active class on the hashtagged value
        var link = "";
        $('.lk-tab-content div').each(function() {
           link = $(this).attr('id');
            if ('#'+link == hash) {
                $(this).addClass('active');
            }
        });
    }

 } )( jQuery, window );