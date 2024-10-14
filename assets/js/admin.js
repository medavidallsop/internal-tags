jQuery( document ).ready( function( $ ) {

	// Color picker

	if ( $( '.internal-tags-color-picker' ).length > 0 ) {

		$( '.internal-tags-color-picker' ).wpColorPicker();

	}

	// Select2

	if ( $( '.internal-tags-select2' ).length > 0 ) {

		$( '.internal-tags-select2' ).select2();

	}

});