<?php

/**
 * Advanced_Categories_Widget_Utils Class
 *
 * All methods are static, this is basically a namespacing class wrapper.
 *
 * @package Advanced_Categories_Widget
 * @subpackage Advanced_Categories_Widget_Utils
 *
 * @since 1.0
 */

// No direct access
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}


/**
 * Advanced_Categories_Widget_Utils Class
 *
 * Group of utility methods for use by Advanced_Categories_Widget
 *
 * @since 1.0
 */
class Advanced_Categories_Widget_Utils
{

	/**
	 * Plugin root file
	 *
	 * @since 0.1.1
	 *
	 * @var string
	 */
	public static $base_file = ADVANCED_CATS_WIDGET_FILE;


	/**
	 * Generates path to plugin root
	 *
	 * @uses WordPress plugin_dir_path()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @return string $path Path to plugin root.
	 */
	public static function get_plugin_path()
	{
		$path = plugin_dir_path( self::$base_file );
		return $path;
	}


	/**
	 * Generates path to subdirectory of plugin root
	 *
	 * @see Advanced_Categories_Widget_Utils::get_plugin_path()
	 *
	 * @uses WordPress trailingslashit()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param string $directory The name of the requested subdirectory.
	 *
	 * @return string $sub_path Path to requested sub directory.
	 */
	public static function get_plugin_sub_path( $directory )
	{
		if( ! $directory ){
			return false;
		}

		$path = self::get_plugin_path();

		$sub_path = $path . trailingslashit( $directory );

		return $sub_path;
	}


	/**
	 * Generates url to plugin root
	 *
	 * @uses WordPress plugin_dir_url()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @return string $url URL of plugin root.
	 */
	public static function get_plugin_url()
	{
		$url = plugin_dir_url( self::$base_file );
		return $url;
	}


	/**
	 * Generates url to subdirectory of plugin root
	 *
	 * @see Advanced_Categories_Widget_Utils::get_plugin_url()
	 *
	 * @uses WordPress trailingslashit()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param string $directory The name of the requested subdirectory.
	 *
	 * @return string $sub_url URL of requested sub directory.
	 */
	public static function get_plugin_sub_url( $directory )
	{
		if( ! $directory ){
			return false;
		}

		$url = self::get_plugin_url();

		$sub_url = $url . trailingslashit( $directory );

		return $sub_url;
	}


	/**
	 * Generates basename to plugin root
	 *
	 * @uses WordPress plugin_basename()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @return string $basename Filename of plugin root.
	 */
	public static function get_plugin_basename()
	{
		$basename = plugin_basename( self::$base_file );
		return $basename;
	}


	/**
	 * Sets default parameters
	 *
	 * Use 'acatw_instance_defaults' filter to modify accepted defaults.
	 *
	 * @uses WordPress current_theme_supports()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @return array $defaults The default values for the widget.
	 */
	public static function instance_defaults()
	{
		$_defaults = array(
			'title'          => __( 'Categories' ),
			'orderby'        => 'name',
			'order'          => 'asc',
			'tax_term'       => '',
			'show_thumb'     => 0,
			'thumb_size'     => 0,
			'thumb_size_w'   => 55,
			'thumb_size_h'   => 55,
			'show_desc'      => 1,
			'desc_length'    => 15,
			'list_style'     => 'ul',
			'show_count'     => 0,
			'css_default'    => 0,
		);

		$defaults = apply_filters( 'acatw_instance_defaults', $_defaults );

		return $defaults;
	}


	/**
	 * Builds a sample description
	 *
	 * Use 'acatw_sample_description' filter to modify Description text.
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @return string $description Description text.
	 */
	public static function sample_description()
	{
		$description = __( 'The point of the foundation is to ensure free access, in perpetuity, to the software projects we support. People and businesses may come and go, so it is important to ensure that the source code for these projects will survive beyond the current contributor base, that we may create a stable platform for web publishing for generations to come. As part of this mission, the Foundation will be responsible for protecting the WordPress, WordCamp, and related trademarks. A 501(c)3 non-profit organization, the WordPress Foundation will also pursue a charter to educate the public about WordPress and related open source software.');

		return apply_filters( 'acatw_sample_description', $description );
	}


	/**
	 * Retrieves taxonomies
	 *
	 * Use 'acatw_allowed_taxonomies' to filter taxonomies that can be selected in the widget.
	 *
	 * @see Advanced_Categories_Widget_Utils::sanitize_select_array()
	 *
	 * @uses WordPress get_object_taxonomies()
	 * @uses WordPress get_taxonomy()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @return array $taxes Allowed taxonomies.
	 */
	public static function get_allowed_taxonomies()
	{
		$_ptaxes = array();

		$_ptaxes['category'] = 'Category';

		$taxes = apply_filters( 'acatw_allowed_taxonomies', $_ptaxes );
		$taxes = self::sanitize_select_array( $taxes );

		return $taxes;

	}


	/**
	 * Retrieves registered image sizes
	 *
	 * Use 'acatw_allowed_image_sizes' to filter image sizes that can be selected in the widget.
	 *
	 * @see Advanced_Categories_Widget_Utils::sanitize_select_array()
	 *
	 * @global $_wp_additional_image_sizes
	 *
	 * @uses get_intermediate_image_sizes()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @return array $_sizes Filtered array of image sizes.
	 */
	public static function get_allowed_image_sizes( $fields = 'name' )
	{
		global $_wp_additional_image_sizes;
		$wp_defaults = array( 'thumbnail', 'medium', 'medium_large', 'large' );

		$_sizes = get_intermediate_image_sizes();

		if( count( $_sizes ) ) {
			sort( $_sizes );
			$_sizes = array_combine( $_sizes, $_sizes );
		}

		$_sizes = apply_filters( 'acatw_allowed_image_sizes', $_sizes );
		$sizes = self::sanitize_select_array( $_sizes );

		if( count( $sizes )&& 'all' === $fields ) {

			$image_sizes = array();
			natsort( $sizes );

			foreach ( $sizes as $_size ) {
				if ( in_array( $_size, $wp_defaults ) ) {
					$image_sizes[$_size]['name']   = $_size;
					$image_sizes[$_size]['width']  = absint( get_option( "{$_size}_size_w" ) );
					$image_sizes[$_size]['height'] = absint(  get_option( "{$_size}_size_h" ) );
					$image_sizes[$_size]['crop']   = (bool) get_option( "{$_size}_crop" );
				} else if( isset( $_wp_additional_image_sizes[ $_size ] )  ) {
					$image_sizes[$_size]['name']   = $_size;
					$image_sizes[$_size]['width']  = absint( $_wp_additional_image_sizes[ $_size ]['width'] );
					$image_sizes[$_size]['height'] = absint( $_wp_additional_image_sizes[ $_size ]['height'] );
					$image_sizes[$_size]['crop']   = (bool) $_wp_additional_image_sizes[ $_size ]['crop'];
				}
			}

			$sizes = $image_sizes;

		};

		return $sizes;
	}


	/**
	 * Retrieves specific image size
	 *
	 * @see Advanced_Categories_Widget_Utils::get_allowed_image_sizes()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @return string Name of image size.
	 *         array  Image size settings; name, width, height, crop.
	 *		   bool   False if size doesn't exist.
	 */
	public static function get_image_size( $size = 'thumbnail', $fields = 'all' )
	{
		$sizes = self::get_allowed_image_sizes( $_fields = 'all' );

		if( count( $sizes ) && isset( $sizes[$size] ) ) :
			if( 'all' === $fields ) {
				return $sizes[$size];
			} else {
				return $sizes[$size]['name'];
			}
		endif;

		return false;
	}


	/**
	 * Builds html for thumbnail
	 *
	 * Use 'acatw_term_thumb_class' to modify image classes.
	 * Use 'acatw_term_thumbnail_html' to modify thumbnail output.
	 *
	 * @see Advanced_Categories_Widget_Utils::get_image_size()
	 *
	 * @uses WordPres wp_get_attachment_image()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param object $term       Term object.
	 * @param array  $instance   Settings for the current Categories widget instance.
	 *
	 * @return string $html Thumbnail html.
	 */
	public static function get_term_thumbnail( $term = 0, $instance = array() )
	{

		if ( empty( $term ) ) {
			return '';
		}

		// future compatible?
		$meta_field = apply_filters( 'acatw_thumb_meta_field', '_thumbnail_id', $term, $instance );

		$_thumbnail_id = get_term_meta( $term->term_id, $meta_field, true );
		$_thumbnail_id = absint( $_thumbnail_id );

		// no thumbnail
		// @todo placeholder?
		if( ! $_thumbnail_id ) {
			return '';
		}

		$_classes = array();
		$_classes[] = 'acatw-term-image';
		$_classes[] = 'acatw-alignleft';

		// was registered size selected?
		$_size = $instance['thumb_size'];

		// custom size entered
		if( empty( $_size ) ){
			$_w = absint( $instance['thumb_size_w'] );
			$_h = absint( $instance['thumb_size_h'] );
			$_size = "acatw-thumbnail-{$_w}-{$_h}";
		}

		// check if the size is registered
		$_size_exists = self::get_image_size( $_size );

		if( $_size_exists ){
			$_get_size = $_size;
			$_w = absint( $_size_exists['width'] );
			$_h = absint( $_size_exists['height'] );
			$_classes[] = "size-{$_size}";
		} else {
			$_get_size = array( $_w, $_h);
		}

		$classes = apply_filters( 'acatw_term_thumb_class', $_classes, $term, $instance );
		$classes = ( ! is_array( $classes ) ) ? (array) $classes : $classes ;
		$classes = array_map( 'sanitize_html_class', $classes );

		$class_str = implode( ' ', $classes );

		$_thumb = wp_get_attachment_image(
			$_thumbnail_id,
			$_get_size,
			false,
			array(
				'class' => $class_str,
				'alt' => $term->name,
				)
			);

		$thumb = apply_filters( 'acatw_term_thumbnail', $_thumb, $term, $instance );

		return $thumb;

	}


	/**
	 * Retrieves term description
	 *
	 * Use 'acatw_term_excerpt' to modify the text before output.
	 * Use 'acatw_term_excerpt_length' to modify the text length before output.
	 * Use 'acatw_term_excerpt_more' to modify the readmore text before output.
	 *
	 * Uses WordPress strip_shortcodes()
	 * Uses WordPress wp_html_excerpt()
	 * Uses WordPress wp_trim_words()
	 *
	 * @access public
	 * @since 1.0
	 *
	 * @param object $term     Term object.
	 * @param array  $instance Widget instance.
	 * @param string $trim     Flag to trim by word or character.
	 *
	 * @return string $text Filtered description.
	 */
	public static function get_term_excerpt( $term = 0, $instance = array(), $trim = 'words' )
	{
		if ( empty( $term ) ) {
			return '';
		}

		$_text = $term->description;

		if( '' === $_text ) {
			return '';
		}

		$_text = strip_shortcodes( $_text );
		$_text = str_replace(']]>', ']]&gt;', $_text);

		$text = apply_filters( 'acatw_term_excerpt', $_text, $term, $instance );

		$_length = ( ! empty( $instance['desc_length'] ) ) ? absint( $instance['desc_length'] ) : 55 ;
		$length = apply_filters( 'acatw_term_excerpt_length', $_length );

		$_aposiopesis = ( ! empty( $instance['excerpt_more'] ) ) ? $instance['excerpt_more'] : '&hellip;' ;
		$aposiopesis = apply_filters( 'acatw_term_excerpt_more', $_aposiopesis );

		if( 'chars' === $trim ){
			$text = wp_html_excerpt( $text, $length, $aposiopesis );
		} else {
			$text = wp_trim_words( $text, $length, $aposiopesis );
		}

		return $text;
	}


	/**
	 * Cleans array of keys/values used in select drop downs
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array $options Values used for select options
	 * @param bool  $sort    Flag to sort the values alphabetically.
	 *
	 * @return array $options Sanitized values.
	 */
	public static function sanitize_select_array( $options, $sort = true )
	{
		$options = ( ! is_array( $options ) ) ? (array) $options : $options ;

		// Clean the values (since it can be filtered by other plugins)
		$options = array_map( 'esc_html', $options );

		// Flip to clean the keys (used as <option> values in <select> field on form)
		$options = array_flip( $options );
		$options = array_map( 'sanitize_key', $options );

		// Flip back
		$options = array_flip( $options );

		if( $sort ) {
			asort( $options );
		};

		return $options;
	}


	/**
	 * Adds a widget to the acatw_use_css option
	 *
	 * If css_default option is selected in the widget, this will add a reference to that
	 * widget instance in the acatw_use_css option.  The 'acatw_use_css' option determines if the
	 * default stylesheet is enqueued on the front end.
	 *
	 * @uses WordPress get_option()
	 * @uses WordPress update_option()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param string $widget_id Widget instance ID.
	 */
	public static function stick_css( $widget_id )
	{
		$widgets = get_option( 'acatw_use_css' );

		if ( ! is_array( $widgets ) ) {
			$widgets = array( $widget_id );
		}

		if ( ! in_array( $widget_id, $widgets ) ) {
			$widgets[] = $widget_id;
		}

		update_option('acatw_use_css', $widgets);
	}


	/**
	 * Removes a widget from the acatw_use_css option
	 *
	 * If css_default option is unselected in the widget, this will remove (if applicable) a
	 * reference to that widget instance in the acatw_use_css option. The 'acatw_use_css' option
	 * determines if the default stylesheet is enqueued on the front end.
	 *
	 * @uses WordPress get_option()
	 * @uses WordPress update_option()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param string $widget_id Widget instance ID.
	 */
	public static function unstick_css( $widget_id )
	{
		$widgets = get_option( 'acatw_use_css' );

		if ( ! is_array( $widgets ) ) {
			return;
		}

		if ( ! in_array( $widget_id, $widgets ) ) {
			return;
		}

		$offset = array_search($widget_id, $widgets);

		if ( false === $offset ) {
			return;
		}

		array_splice( $widgets, $offset, 1 );

		update_option( 'acatw_use_css', $widgets );
	}


	/**
	 * Prints link to default widget stylesheet
	 *
	 * Actual stylesheet is enqueued if the user selects to use default styles
	 *
	 * @see Widget_APW_Recent_Categories::widget()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance Current widget settings.
	 * @param object $widget   Widget Object.
	 * @param bool   $echo     Flag to echo|return output.
	 *
	 * @return string $css_url Stylesheet link.
	 */
	public static function css_preview( $instance, $widget, $echo = true )
	{
		$_css_url =  self::get_plugin_sub_url('css') . 'front.css' ;

		$css_url = sprintf('<link rel="stylesheet" href="%s" type="text/css" media="all" />',
			esc_url( $_css_url )
		);

		if( $echo ) {
			echo $css_url;
		} else {
			return $css_url;
		}
	}


	/**
	 * Checks if site is compatible with Category Thumbnail plugin
	 *
	 * Note: Checks for Advanced Term Images
	 * @see https://wordpress.org/plugins/advanced-term-fields-featured-images/
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @return bool True if plugin is active, False if not.
	 */
	public static function is_category_thumbnail_compatible()
	{
		$plugin = 'advanced-term-fields-featured-images/advanced-term-fields-images.php';

		if ( is_multisite() ) {
			$active_plugins = get_site_option( 'active_sitewide_plugins' );
			if ( isset( $active_plugins[$plugin] ) ) {
				 return true;
			}
		}

		return in_array( $plugin, (array) get_option( 'active_plugins', array() ) ) ;
	}


	/**
	 * Returns categories based on widget settings
	 *
	 * Note: prior to WP4.5 get_terms() accepted the taxonomy as a separate argument.  As of 4.5
	 * get_terms() accepts one array of arguments with the taxonomy arg passed as an array value.
	 * We're calling get_terms() using the older format for sites with older versions of WP.
	 *
	 * @uses WordPress get_terms()
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance Current widget settings.
	 * @param object $widget   Widget Object.
	 *
	 * @return string $css_url Stylesheet link.
	 */
	public static function get_widget_categories( $instance, $widget )
	{

		if( empty( $instance['tax_term'] ) ) {
			return array();
		}

		$_include_taxonomies = array();
		$_include_ids = array();

		foreach( $instance['tax_term'] as $taxonomy => $term_ids ) {
			$_include_taxonomies[] = $taxonomy;
			array_walk_recursive( $term_ids, function( $value, $key ) use ( &$_include_ids ) {
				$_include_ids[$key] = $value;
			} );
		}

		$r = array(
			'taxonomy'   => $_include_taxonomies,
			'orderby'    => $instance['orderby'],
			'order'      => $instance['order'],
			'hide_empty' => 0,
			'include'    => $_include_ids
		);

		$categories = get_terms( $_include_taxonomies, $r );

		if ( is_wp_error( $categories ) ) {
			$categories = array();
		} else {
			$categories = (array) $categories;
		}

		return $categories;

	}


	/**
	 * Generates unique list-item id based on widget instance and term (obj) ID
	 *
	 * Note: The output is not just the ID of the term. It includes the widget instance as well.
	 * This allows for multiple term lists to be created, each with unique IDs.
	 *
	 * Use 'acatw_term_id' filter to modify term ID before output.
	 *
	 * @access public
	 * @since 1.0
	 *
	 * @param object $term Term to display.
	 * @param array  $instance Widget instance.
	 *
	 * @return string $term_id Filtered term ID.
	 */
	public static function get_item_id( $term = 0, $instance = array() )
	{
		if( ! $term ){
			return '';
		}

		$_term_id = $instance['widget_id'] . '-term-' . $term->term_id;

		$term_id = sanitize_html_class( $_term_id );

		return apply_filters( 'acatw_item_id', $term_id, $term, $instance );
	}


	/**
	 * Generate term classes
	 *
	 * Use 'acatw_term_class' filter to modify term classes before output.
	 *
	 * @access public
	 * @since 1.0
	 *
	 * @param object $term     Term to display.
	 * @param array  $instance Widget instance.
	 *
	 * @return string $class_str CSS classes.
	 */
	public static function get_item_class( $term = 0, $instance = array() )
	{
		if( ! $term ){
			return '';
		}

		$_classes = array();
		$_classes[] = 'acatw-term-item';
		$_classes[] = 'acatw-' . $term->taxonomy . '-item';
		$_classes[] = 'acatw-' . $term->taxonomy . '-item-' . $term->term_id;

		if ( $term->parent > 0 ) {
			$_classes[] = 'child-term';
			$_classes[] = 'parent-' . $term->parent;
		}

		$classes = apply_filters( 'acatw_term_class', $_classes, $term, $instance );
		$classes = ( ! is_array( $classes ) ) ? (array) $classes : $classes ;
		$classes = array_map( 'sanitize_html_class', $classes );

		$class_str = implode( ' ', $classes );

		return $class_str;
	}

}