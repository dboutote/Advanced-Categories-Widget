<?php
/**
 * Widget_ACW_Advanced_Categories Class
 *
 * Adds a Categories widget with extended functionality
 *
 * @package Advanced_Categories_Widget
 * @subpackage Widget_ACW_Advanced_Categories
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
 * Core class used to implement a Categories widget.
 *
 * @since 1.0
 *
 * @see WP_Widget
 */
class Widget_ACW_Advanced_Categories extends WP_Widget
{

	/**
	 * Sets up a new widget instance.
	 *
	 * @access public
	 *
	 * @since 1.0
	 */
	public function __construct()
	{
		$widget_options = array(
			'classname'                   => 'widget_acw_advanced_categories advanced-categories-widget',
			'description'                 => __( 'A categories widget with extended features.' ),
			'customize_selective_refresh' => true,
			);

		$control_options = array();

		parent::__construct(
			'advanced-categories-widget', // $this->id_base
			__( 'Advanced Categories' ),  // $this->name
			$widget_options,              // $this->widget_options
			$control_options              // $this->control_options
		);

		$this->alt_option_name = 'widget_acw_advanced_categories';

	}


	/**
	 * Outputs the content for the current widget instance.
	 *
	 * Use 'widget_title' to filter the widget title.
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Categories widget instance.
	 */
	public function widget( $args, $instance )
	{
		if ( ! isset( $args['widget_id'] ) ){
			$args['widget_id'] = $this->id;
		}

		$_defaults = Advanced_Categories_Widget_Utils::instance_defaults();
		$instance = wp_parse_args( (array) $instance, $_defaults );

		// build out the instance for devs
		$instance['id_base']       = $this->id_base;
		$instance['widget_number'] = $this->number;
		$instance['widget_id']     = $this->id;

		// widget title
		$_title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

_debug( $instance );

		$r = array(
			'taxonomy' => 'category',
			'orderby' => $instance['orderby'],
			#'order' => $instance['order'],
			'include' => $instance['tax_term']['category']
		);
		$categories = get_terms($r);
		
_debug( $categories);

		echo $args['before_widget'];

		if( $_title ) {
			echo $args['before_title'] . $_title . $args['after_title'];
		};

		do_action( 'advcatswdgt_widget_title_after', $instance );

		/**
		 * Prints out the css url only if in Customizer
		 *
		 * Actual stylesheet is enqueued if the user selects to use default styles
		 *
		 * @since 1.0
		 */
		if( ! empty( $instance['css_default'] ) && is_customize_preview() ) {
			echo Advanced_Categories_Widget_Utils::css_preview( $instance, $this );
		}
		?>

		<div class="advanced-categories-widget advcatswgt-recent-categories advcatswgt-categories-wrap">

			<?php

			do_action( 'advcatswdgt_category_list_before', $instance );

			Advanced_Categories_Widget_Views::start_list( $instance );

			Advanced_Categories_Widget_Views::start_list_item( $instance );

				Advanced_Categories_Widget_Utils::get_template( "content-{$instance['item_format']}", $load = true, $require_once = false, $instance );

			Advanced_Categories_Widget_Views::end_list_item( $instance );


			Advanced_Categories_Widget_Views::end_list( $instance );

			do_action( 'advcatswdgt_category_list_after', $instance );

			?>

		</div><!-- /.advcatswgt-categories-wrap -->

		<?php Advanced_Categories_Widget_Views::colophon(); ?>

		<?php echo $args['after_widget']; ?>

		

		
	<?php
	}


	/**
	 * Handles updating settings for the current widget instance.
	 *
	 * Use 'advcatswdgt_update_instance' to filter updating/sanitizing the widget instance.
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 *
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance )
	{
		$instance = $old_instance;

		// general
		$instance['title']     = sanitize_text_field( $new_instance['title'] );
		$instance['orderby']   = sanitize_text_field( $new_instance['orderby'] );
		$instance['order']     = sanitize_text_field( $new_instance['order'] );

		// taxonomies & filters
		if( is_array( $new_instance['tax_term'] ) ) {
			$_tax_terms = array();
			foreach( $new_instance['tax_term'] as $key => $val ) {
				if( is_array( $val ) ){
					$_val = array_map( 'absint', $val );
					$_val = array_filter( $_val );
				} else {
					$_val = absint( $val );
				}
				$_tax_terms[$key] = $_val;
			}
			$instance['tax_term'] = $_tax_terms;
		} else {
			$instance['tax_term'] = absint( $new_instance['tax_term'] );
		}

		// thumbnails
		$instance['show_thumb']   = isset( $new_instance['show_thumb'] ) ? 1 : 0 ;
		$instance['thumb_size']   = sanitize_text_field( $new_instance['thumb_size'] );

		$_thumb_size_w            = absint( $new_instance['thumb_size_w'] );
		$instance['thumb_size_w'] = ( $_thumb_size_w < 1 ) ? 55 : $_thumb_size_w ;

		$_thumb_size_h            = absint( $new_instance['thumb_size_h'] );
		$instance['thumb_size_h'] = ( $_thumb_size_h < 1 ) ? $_thumb_size_w : $_thumb_size_h ;

		// excerpts
		$instance['show_desc']    = isset( $new_instance['show_desc'] ) ? 1 : 0 ;
		$instance['desc_length']  = absint( $new_instance['desc_length'] );

		// list format
		$instance['list_style']   = ( '' !== $new_instance['list_style'] ) ? sanitize_key( $new_instance['list_style'] ) : 'ul ';

		// post count
		$instance['show_count']   = isset( $new_instance['show_count'] ) ? 1 : 0 ;

		// styles & layout
		$instance['css_default'] = isset( $new_instance['css_default'] ) ? 1 : 0 ;

		// build out the instance for devs
		$instance['id_base']       = $this->id_base;
		$instance['widget_number'] = $this->number;
		$instance['widget_id']     = $this->id;

		$instance = apply_filters('advcatswdgt_update_instance', $instance, $new_instance, $old_instance, $this );

		do_action( 'advcatswdgt_update_widget', $this, $instance, $new_instance, $old_instance );

		return $instance;
	}


	/**
	 * Outputs the settings form for the Categories widget.
	 *
	 * Applies 'advcatswdgt_form_defaults' filter on form fields to allow extension by plugins.
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance )
	{
		$defaults = Advanced_Categories_Widget_Utils::instance_defaults();

		$instance = wp_parse_args( (array) $instance, $defaults );

		include( Advanced_Categories_Widget_Utils::get_plugin_sub_path('inc') . 'widget-form.php' );

	}

}