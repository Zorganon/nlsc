(function( $ ) {

    'use strict';

    $( window ).load( function() {

            // Iterate over each "g-recaptcha" div
            $( '.g-recaptcha' ).each( function( index, element ) {

                // Ensure field is empty before rendering CAPTCHA
                if( $( this ).is( ':empty' ) ) {

                    // Site key
                    var site_key = $( this ).attr( 'data-sitekey' );

                    // CAPTCHA theme
                    var theme = $( this ).attr( 'data-theme' );

                    // CAPTCHA type
                    var type = $( this ).attr( 'data-type' );

                    // Native DOM element
                    var element  = $( this ).get( 0 );

                    grecaptcha.render(element, {'sitekey': site_key, 'theme': theme, 'type': type});

                }
            });
    });
})( jQuery );