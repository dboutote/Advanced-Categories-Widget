/**
 * Plugin functions file.
 *
 */
if( "undefined"==typeof jQuery )throw new Error( "Advanced Categories Widget's javaScript requires jQuery" );

(function ( $ ) {

    'use strict';
	
	/**
	 * Thumbnail Preview
	 *
	 * @since 
	 */
	function change_thumb_div( e ){
		var $field = $( e.currentTarget );
		var $thumb_wrap = $field.closest( '.widgin-thumb-size-wrap' );
		var $thumb_div = $thumb_wrap.find( '.widgin-thumb-preview' );

		if( $thumb_div.length ) {
			var thumb = $( '.widgin-thumb', $thumb_div );
			var width = parseInt ( ( $.trim( $( '.widgin-thumb-width', $thumb_wrap ).val() ) * 1 ) + 0 );
			var height = parseInt ( ( $.trim( $( '.widgin-thumb-height', $thumb_wrap ).val() ) * 1 ) + 0 );

			$thumb_div.css( {
				'height' : height + 'px',
				'width' : width + 'px'
			} );
			thumb.css( { 'font-size' : height + 'px' } );
		}

		return;
	};

	// Change thumb size when form field changes
	$( '#customize-controls, #wpcontent' ).on( 'change', '.widgin-thumb-size', function ( e ) {
		change_thumb_div( e );
		return;
	});

	// Change thumb size as user types
	$( '#customize-controls, #wpcontent' ).on( 'keyup', '.widgin-thumb-size', function ( e ) {
		setTimeout( function(){
			change_thumb_div( e );
		}, 300 );
		return;
	});
	
	
	

	/**
	 * Excerpt Preview
	 *
	 * @since 1.0
	 */
	function change_excerpt_size( e ) {
		var $field = $( e.currentTarget );
		var acatsw_excerpt_div = $field.closest( '.widgin-excerpt-size-wrap' ).find( '.widgin-excerpt' );
		var size = parseInt ( ( $.trim( $field.val() ) * 1 ) + 0 );

		if( acatsw_excerpt_div.length ) {
			var words = advcatswdgt_script_vars.sample_description.match(/\S+/g).length;
			var trimmed = '';
			if ( words > size ) {
				trimmed = advcatswdgt_script_vars.sample_description.split(/\s+/, size).join(" ");
			} else {
				trimmed = advcatswdgt_script_vars.sample_description;
			}
			
			acatsw_excerpt_div.html( trimmed + "&hellip;" );
			
			//acatsw_excerpt_div.html( advcatswdgt_script_vars.sample_description.substring( 0, size) + "&hellip;" );
		}
	}
	
	function update_excerpt_size( event, widget ){
		var $field = widget.find( '.widgin-excerpt-length' );
		var acatsw_excerpt_div = $field.closest( '.widgin-excerpt-size-wrap' ).find( '.widgin-excerpt' );
		var size = parseInt ( ( $.trim( $field.val() ) * 1 ) + 0 );

		if( acatsw_excerpt_div.length ) {
			var words = advcatswdgt_script_vars.sample_description.match(/\S+/g).length;
			var trimmed = '';
			if ( words > size ) {
				trimmed = advcatswdgt_script_vars.sample_description.split(/\s+/, size).join(" ");
			} else {
				trimmed = advcatswdgt_script_vars.sample_description;
			}
			
			acatsw_excerpt_div.html( trimmed + "&hellip;" );
			
			//acatsw_excerpt_div.html( advcatswdgt_script_vars.sample_description.substring( 0, size) + "&hellip;" );
		}
	}
	
	$( document ).on( 'widget-updated', update_excerpt_size );

	// Change excerpt size when form field changes
	$( '#customize-controls, #wpcontent' ).on( 'change', '.widgin-excerpt-length', function ( e ) {
		change_excerpt_size( e );
		return;
	});

	// Change excerpt size as user types
	$( '#customize-controls, #wpcontent' ).on( 'keyup', '.widgin-excerpt-length', function ( e ) {
		setTimeout( function(){
			change_excerpt_size( e );
		}, 300 );
		return;
	});
	
	
	
	/**
	 * Toggle settings accordions
	 *
	 * @since 1.0
	 */
	function widgin_close_accordions( widget ){
		var $sections = widget.find('.widgin-section');
		var $first_section = $sections.first();

		$first_section.addClass('expanded').find('.widgin-section-top').addClass('widgin-active');
		$first_section.siblings('.widgin-section').find('.widgin-settings').hide();
	}
	
	function widgin_on_form_update( event, widget ) {
		widgin_close_accordions( widget );
	}
	
	$( document ).on( 'widget-added widget-updated', widgin_on_form_update );
	
	$( '#widgets-right .widget:has(.widgin-widget-form)' ).each( function () {
		widgin_close_accordions( $( this ) );
	} );
	
	
	$( '#widgets-right, #accordion-panel-widgets' ).on( 'click', '.widgin-section-top', function( e ){
		var $header = $( this );
		var $section = $header.closest( '.widgin-section' );
		var $fieldset_id = $header.data( 'fieldset' );
		var $target_fieldset = $( 'fieldset[data-fieldset-id="' + $fieldset_id + '"]', $section );
		var $content = $section.find( '.widgin-settings' )
		
		$header.toggleClass( 'widgin-active' );
		$target_fieldset.addClass( 'targeted');
		$content.slideToggle( 300, function () {
			$section.toggleClass( 'expanded' );
		});
	});


}(jQuery) );