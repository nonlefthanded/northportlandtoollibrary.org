/**
 * Theme Customizer custom scripts
 * Control of show/hide events on feature slider type selection
 */
(function($) {
    //Change value of hidden field below custom checkboxes
	jQuery( document ).ready( function() {
	    jQuery( '.customize-control-simplecatch_custom_checkbox input[type="checkbox"]' ).on(
	        'change',
	        function() {
	        	checkbox_value = "0";
	            
	            if ( jQuery( this ).is(":checked") ) {
	            	checkbox_value = "1";
	            }
	            
	            jQuery( this ).parents( '.customize-control' ).find( 'input[type="hidden"]' ).val( checkbox_value ).trigger( 'change' );
	        }
	    );

	} ); // jQuery( document ).ready
})(jQuery);