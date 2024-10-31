/**
 * Feature Name:	Admin JS
 * Version:			0.1
 * Author:			Inpsyde GmbH
 * Author URI:		http://inpsyde.com
 * Licence:			GPLv3
 * 
 * Changelog
 *
 * 0.1
 * - Initial Commit
 */

( function( $ ) {
	var spd_admin = {
		init : function () {
			
			$( '#spd_scheduled_delete' ).live( 'change', function() {
				if ( undefined == $( this ).attr( 'checked' ) )
					$( '#spd_timestamp' ).slideUp( 'fast' );
				else
					$( '#spd_timestamp' ).slideDown( 'fast' );
			} );
		},
	};
	$( document ).ready( function( $ ) {
		spd_admin.init();
	} );
} )( jQuery );