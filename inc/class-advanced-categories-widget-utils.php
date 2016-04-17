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
	 * Use 'advcatswdgt_instance_defaults' filter to modify accepted defaults.
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
			'order'          => 'desc',
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

		$defaults = apply_filters( "advcatswdgt_instance_defaults", $_defaults );

		return $defaults;
	}


	/**
	 * Builds a sample description
	 *
	 * Use 'advcatswdgt_sample_description' filter to modify Description text.
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

		return apply_filters( "advcatswdgt_sample_description", $description );
	}


	/**
	 * Retrieves taxonomies associated with allowed post types
	 *
	 * Use 'advcatswdgt_allowed_taxonomies' to filter taxonomies that can be selected in the widget.
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

		$taxes = apply_filters( 'advcatswdgt_allowed_taxonomies', $_ptaxes );
		$taxes = self::sanitize_select_array( $taxes );

		return $taxes;

	}


	/**
	 * Retrieves registered image sizes
	 *
	 * Use 'advcatswdgt_allowed_image_sizes' to filter image sizes that can be selected in the widget.
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

		$_sizes = apply_filters( 'advcatswdgt_allowed_image_sizes', $_sizes );
		$sizes = self::sanitize_select_array( $_sizes );

		if( count( $sizes )&& 'all' === $fields ) {

			$image_sizes = array();
			asort( $sizes, SORT_NATURAL );

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
	public static function get_allowed_image_size( $size = 'thumbnail', $fields = 'all' )
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
	 * Builds html for post thumbnail
	 *
	 * Use 'advcatswdgt_post_thumb_class' to modify image classes.
	 * Use 'advcatswdgt_post_thumbnail_html' to modify thumbnail output.
	 *
	 * @see Advanced_Categories_Widget_Utils::get_allowed_image_size()
	 *
	 * @uses WordPres get_post()
	 * @uses WordPres has_post_thumbnail()
	 * @uses WordPres get_the_post_thumbnail()
	 * @uses WordPres the_title_attribute()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @return
	 */
	public static function get_post_thumbnail( $post = 0, $instance = array() )
	{
		$_post = get_post( $post );

		if ( empty( $_post ) ) {
			return '';
		}

		$_classes = array();
		$_classes[] = 'advcatswgt-post-image';
		$_classes[] = 'advcatswgt-alignleft';

		// was registered size selected?
		$_size = $instance['thumb_size'];

		// custom size entered
		if( empty( $_size ) ){
			$_w = absint( $instance['thumb_size_w'] );
			$_h = absint( $instance['thumb_size_h'] );
			$_size = "advcatswgt-thumbnail-{$_w}-{$_h}";
		}

		// check if the size is registered
		$_size_exists = self::get_allowed_image_size( $_size );

		// no thumbnail
		// @todo placeholder?
		if( ! has_post_thumbnail( $_post ) ) {
			return '';
		}

		if( $_size_exists ){
			$_get_size = $_size;
			$_w = absint( $_size_exists['width'] );
			$_h = absint( $_size_exists['height'] );
			$_classes[] = "size-{$_size}";
		} else {
			$_get_size = array( $_w, $_h);
		}

		$classes = apply_filters( 'advcatswdgt_post_thumb_class', $_classes, $_post, $instance );
		$classes = ( ! is_array( $classes ) ) ? (array) $classes : $classes ;
		$classes = array_map( 'sanitize_html_class', $classes );

		$class_str = implode( ' ', $classes );

		$_thumb = get_the_post_thumbnail(
			$_post,
			$_get_size,
			array(
				'class' => $class_str,
				'alt' => the_title_attribute( 'echo=0' )
				)
			);

		$thumb = apply_filters( 'advcatswdgt_post_thumbnail_html', $_thumb, $_post, $instance );

		return $thumb;

	}



	/**
	 * Retrieves a template file
	 *
	 * @see Advanced_Categories_Widget_Utils::get_plugin_sub_path()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param string $file         Template file to search for.
	 * @param bool   $load         If true the template file will be loaded if it is found.
	 * @param bool   $require_once Whether to require_once or require. Default true. Has no effect if $load is false.
	 * @param array  $instance     Widget instance.
	 *
	 * @return string $located The template filename if one is located.
	 */
	public static function get_template( $file, $load = false, $require_once = true, $instance = array() )
	{
		$located = '';

		$template_name = "{$file}.php";
		$template_path = self::get_plugin_sub_path('tpl');

		if ( file_exists( $template_path . $template_name ) ) {
			$located = $template_path . $template_name;
		}

		if ( $load && '' != $located ){
			if ( $require_once ) {
				require_once( $located );
			} else {
				require( $located );
			}
		}

		return $located;
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
	 * Adds a widget to the advcatswdgt_use_css option
	 *
	 * If css_default option is selected in the widget, this will add a reference to that
	 * widget instance in the advcatswdgt_use_css option.  The 'advcatswdgt_use_css' option determines if the
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
		$widgets = get_option( 'advcatswdgt_use_css' );

		if ( ! is_array( $widgets ) ) {
			$widgets = array( $widget_id );
		}

		if ( ! in_array( $widget_id, $widgets ) ) {
			$widgets[] = $widget_id;
		}

		update_option('advcatswdgt_use_css', $widgets);
	}


	/**
	 * Removes a widget from the advcatswdgt_use_css option
	 *
	 * If css_default option is unselected in the widget, this will remove (if applicable) a
	 * reference to that widget instance in the advcatswdgt_use_css option. The 'advcatswdgt_use_css' option
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
		$widgets = get_option( 'advcatswdgt_use_css' );

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

		update_option( 'advcatswdgt_use_css', $widgets );
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
		$plugin = 'advanced-term-fields-images/advanced-term-fields-images.php';

		if ( is_multisite() ) {
			$active_plugins = get_site_option( 'active_sitewide_plugins' );
			if ( isset( $active_plugins[$plugin] ) ) {
				 return true;
			}
		}

		return in_array( $plugin, (array) get_option( 'active_plugins', array() ) ) ;
	}


}