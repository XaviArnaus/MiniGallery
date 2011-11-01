/* Simple class to manage Sections */
var Section = {};
Section.init = function()
{
	// Hide all sections.
	$( '.section' ).hide();

	// Autostart with VFR.
	Section.show( '#start' );
};
Section.show = function( element )
{
	$( '#main-container' ).find( '.section' ).fadeOut( 'slow' );
	$( '#main-container' ).text( '' );
	$( element ).clone().appendTo( '#main-container' );
	$( element ).fadeIn( 'slow' );
};

/* Bind and execute when all is loaded */
$(document).ready(function(){
	// Presentacion.
	$( '#link-start' ).unbind();
	$( '#link-start' ).click( function(){
		Section.show( '#start' );
	});
	// VFR 800.
	$( '#link-vfr' ).unbind();
	$( '#link-vfr' ).click( function(){
		Section.show( '#vfr' );
	});
	// GSR 600.
	$( '#link-gsr' ).unbind();
	$( '#link-gsr' ).click( function(){
		Section.show( '#gsr' );
	});
	// VTR 250.
	$( '#link-vtr' ).unbind();
	$( '#link-vtr' ).click( function(){
		Section.show( '#vtr' );
	});
	// Cota 247.
	$( '#link-cota' ).unbind();
	$( '#link-cota' ).click( function(){
		Section.show( '#cota' );
	});
	// eVTR.
	$( '#link-evtr' ).unbind();
	$( '#link-evtr' ).click( function(){
		Section.show( '#evtr' );
	});

	// Start the page.
	Section.init();
});