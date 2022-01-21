/*
 * Danger Code Nav Scripts
 * Author: Will Christenson [Danger Code]
 *
*/

jQuery( ".menu_button" ).on( "click", function() {
    var target = jQuery( this ).attr( 'data-target' );
    
    jQuery( "#" + target ).toggleClass( 'danger_nav_open' );
});