/**
 * Core Widgins js file.
 *
 * @package Widgins
 * @since 1.1.0
 */

/* global jQuery */

if( "undefined" == typeof jQuery )throw new Error( "Widgins JS requires jQuery" );


( function( widgin, $, undefined ) {

	'use strict';


	/**
	 * Closes accordion section in widget form
	 *
	 * @since 1.0.0
	 */
	widgin.close_accordions = function ( widget ){
		var sections = widget.find( '.widgin-section' );
		var first_section = sections.first();

		first_section.addClass( 'expanded' ).find( '.widgin-section-top' ).addClass( 'widgin-active' );
		first_section.siblings( '.widgin-section' ).find( '.widgin-settings' ).hide();
	};


	/**
	 * Invokes accordion closing when widget form is saved
	 *
	 * @since 1.0.0
	 */
	widgin.accordion_form_update = function( event, widget ){
		console.log( event );
		console.log( widget );
		widgin.close_accordions( widget );
	};


	/**
	 * Updates thumbnail preview
	 *
	 * @since 1.0.0
	 */
	widgin.update_thumbnail_preview = function ( widget ){

		var preview_div = widget.find( '.widgin-thumbnail-preview' );

		if( ! preview_div.length ) {
			return;
		}

		var thumbsize_wrap = preview_div.closest( '.widgin-thumbsize-wrap' );
		var preview_image = $( '.widgin-preview-image', preview_div );
		var width         = parseInt ( ( $.trim( $( '.widgin-thumb-width', thumbsize_wrap ).val() ) * 1 ) + 0 );
		var height        = parseInt ( ( $.trim( $( '.widgin-thumb-height', thumbsize_wrap ).val() ) * 1 ) + 0 );

		preview_div.css( {
			'height' : height + 'px',
			'width'  : width  + 'px'
		} );
		preview_image.css( { 'font-size' : height + 'px' } );

		return;
	};


	/**
	 * Invokes thumbnail update when widget form is saved
	 *
	 * @since 1.0.0
	 */
	widgin.thumbnail_form_update = function ( event, widget ){
		widgin.update_thumbnail_preview( widget );
	};


	/**
	 * Updates excerpt preview
	 *
	 * @since 1.0.0
	 */
	widgin.update_excerpt_preview = function ( widget ) {

		var preview_div = widget.find( '.widgin-excerpt-preview' );
		var sample_excerpt = widget.find( '.widgin-excerpt-sample' ).text();

		if( ! preview_div.length ) {
			return;
		}

		var excerpt = $( '.widgin-excerpt', preview_div );
		var field   = widget.find( '.widgin-excerpt-length' );
		var size    = parseInt ( ( $.trim( field.val() ) * 1 ) + 0 );
		var words   = sample_excerpt.match(/\S+/g).length;
		var trimmed = '';

		if ( words > size ) {
			trimmed = sample_excerpt.split(/\s+/, size).join(" ");
		} else {
			trimmed = sample_excerpt;
		}

		excerpt.html( trimmed + "&hellip;" );

	};


	/**
	 * Invokes excerpt update when widget form is saved
	 *
	 * @since 1.0.0
	 */
	widgin.excerpt_form_update = function ( event, widget ){
		widgin.update_excerpt_preview( widget );
	};


}( window.widgin = window.widgin || {}, jQuery ) );

( function ( $ ) {

    'use strict';

	/**
	 * Accordion functions
	 *
	 * @since 1.0.0
	 */
	$( document ).on( 'widget-added widget-updated', widgin.accordion_form_update );

	$( '#widgets-right .widget:has( .widgin-widget-form )' ).each( function () {
		widgin.close_accordions( $( this ) );
	} );

	$( '#widgets-right, #accordion-panel-widgets' ).on( 'click', '.widgin-section-top', function( e ) {
		var header = $( this );
		var section = header.closest( '.widgin-section' );
		var fieldset_id = header.data( 'fieldset' );
		var target_fieldset = $( 'fieldset[data-fieldset-id="' + fieldset_id + '"]', section );
		var content = section.find( '.widgin-settings' );

		header.toggleClass( 'widgin-active' );
		target_fieldset.addClass( 'targeted');
		content.slideToggle( 300, function () {
			section.toggleClass( 'expanded' );
		});
	});


	/**
	 * Preview thumbnail size
	 *
	 * @since 1.0.0
	 */

	$( document ).on( 'widget-added widget-updated', widgin.thumbnail_form_update );

	// Change thumb size when form field changes
	$( '#customize-controls, #wpcontent' ).on( 'change', '.widgin-thumb-size', function ( e ) {
		var widget = $(this).closest('.widget');
		widgin.update_thumbnail_preview( widget );
		return;
	});

	// Change thumb size as user types
	$( '#customize-controls, #wpcontent' ).on( 'keyup', '.widgin-thumb-size', function ( e ) {
		var widget = $(this).closest('.widget');
		setTimeout( function(){
			widgin.update_thumbnail_preview( widget );
		}, 300 );
		return;
	});

	$( '#widgets-right .widget:has( .widgin-thumbnail-preview )' ).each( function () {
		widgin.update_thumbnail_preview( $(this) );
	});


	/**
	 * Preview excerpt size
	 *
	 * @since 1.0.0
	 */

	$( document ).on( 'widget-added widget-updated', widgin.excerpt_form_update );

	// Change excerpt size when form field changes
	$( '#customize-controls, #wpcontent' ).on( 'change', '.widgin-excerpt-length', function ( e ) {
		var widget = $(this).closest('.widget');
		widgin.update_excerpt_preview( widget );
		return;
	});

	// Change excerpt size as user types
	$( '#customize-controls, #wpcontent' ).on( 'keyup', '.widgin-excerpt-length', function ( e ) {
		var widget = $(this).closest('.widget');
		setTimeout( function(){
			widgin.update_excerpt_preview( widget );
		}, 300 );
		return;
	});

	$( '#widgets-right .widget:has( .widgin-excerpt-preview )' ).each( function () {
		widgin.update_excerpt_preview( $(this) );
	});


}( jQuery ) );