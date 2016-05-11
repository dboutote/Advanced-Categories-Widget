<?php

/**
* Advanced_Categories_Widget_Fields Class
*
* Handles generation of widget form fields.
* All methods are static, this is basically a namespacing class wrapper.
*
* @package Advanced_Categories_Widget
* @subpackage Advanced_Categories_Widget_Fields
*
* @since 1.0
*/

class Advanced_Categories_Widget_Fields
{

	public function __construct(){}


	/**
	 * Loads fields for a specific fieldset for widget form
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param string $fieldset Name (slug) of fieldset.
	 * @param array  $fields   Fields to load.
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function load_fieldset( $fieldset = 'general', $fields, $instance, $widget )
	{
		if( ! is_array( $fields ) ) {
			return;
		}

		$keys        = array_keys( $fields );
		$first_field = reset( $keys );
		$last_field  = end( $keys );

		ob_start();

		foreach ( $fields as $name => $field ) {

			if ( $first_field === $name ) {
				do_action( "acatw_form_before_fields_{$fieldset}", $instance, $widget );
			}

			do_action( "acatw_form_before_field_{$name}", $instance, $widget );

			// output the actual field
			echo apply_filters( "acatw_form_field_{$name}", $field, $instance, $widget ) . "\n";

			do_action( "acatw_form_after_field_{$name}", $instance, $widget );

			if ( $last_field === $name ) {
				do_action( "acatw_form_after_fields_{$fieldset}", $instance, $widget );
			}

		}

		$fieldset = ob_get_clean();

		echo $fieldset;
	}


	/**
	 * Build section header for widget form
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param string $fieldset Slug of fieldset.
	 * @param array  $title    Name of fieldset.
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_section_header( $fieldset = 'general', $title = 'General Settings', $instance, $widget )
	{
		ob_start();
		?>

		<div class="widgin-section-top" data-fieldset="<?php echo $fieldset; ?>">
			<div class="widgin-top-action">
				<a class="widgin-action-indicator hide-if-no-js" data-fieldset="<?php echo $fieldset; ?>" href="#"></a>
			</div>
			<div class="widgin-section-title">
				<h4 class="widgin-section-heading" data-fieldset="<?php echo $fieldset; ?>">
					<?php printf( __( '%s', 'advanced-categories-widget' ), $title ); ?>
				</h4>
			</div>
		</div>

		<?php
		$field = ob_get_clean();

		return $field;
	}


	/**
	 * Builds form field: title
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_field_title( $instance, $widget )
	{
		ob_start();
		?>

		<p>
			<label for="<?php echo $widget->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'advanced-categories-widget' ); ?></label>
			<input class="widefat" id="<?php echo $widget->get_field_id( 'title' ); ?>" name="<?php echo $widget->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<?php
		$field = ob_get_clean();

		return $field;
	}


	/**
	 * Builds form field: orderby
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_field_orderby( $instance, $widget )
	{
		$_params = self::get_orderby_parameters();
		ob_start();
		?>

		<p>
			<label for="<?php echo $widget->get_field_id('orderby'); ?>">
				<?php _e( 'Order By:', 'advanced-categories-widget' ); ?>
			</label>
			<select name="<?php echo $widget->get_field_name('orderby'); ?>" id="<?php echo $widget->get_field_id('orderby'); ?>" class="widefat">
				<?php foreach( $_params as $query_var => $label  ) { ?>
					<option value="<?php echo esc_attr( $query_var ); ?>" <?php selected( $instance['orderby'] , $query_var ); ?>><?php echo esc_html( $label ); ?></option>
				<?php } ?>
			</select>
		</p>

		<?php
		$field = ob_get_clean();

		return $field;
	}


	/**
	 * Retrieves orderby parameters
	 *
	 * Use 'acatw_allowed_orderby_params' to filter parameters that can be selected in the widget.
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @return array $params Filtered array of orderby parameters.
	 */
	public static function get_orderby_parameters()
	{
		$_orderby = array(
			'name'       => __( 'Category Name', 'advanced-categories-widget' ),
			'count'      => __( 'Post Count', 'advanced-categories-widget' ),
		);

		$params = apply_filters( 'acatw_allowed_orderby_params', $_orderby );
		$params = Advanced_Categories_Widget_Utils::sanitize_select_array( $params );

		return $params;
	}


	/**
	 * Builds form field: order
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_field_order( $instance, $widget )
	{
		ob_start();
		?>

		<p>
			<label for="<?php echo $widget->get_field_id('order'); ?>">
				<?php _e( 'Order:', 'advanced-categories-widget' ); ?>
			</label>
			<select name="<?php echo $widget->get_field_name('order'); ?>" id="<?php echo $widget->get_field_id('order'); ?>" class="widefat">
				<option value="desc" <?php selected( $instance['order'] , 'desc' ); ?>><?php _e( 'Descending', 'advanced-categories-widget' ); ?></option>
				<option value="asc" <?php selected( $instance['order'] , 'asc' ); ?>><?php _e( 'Ascending', 'advanced-categories-widget' ); ?></option>
			</select>
		</p>

		<?php
		$field = ob_get_clean();

		return $field;
	}


	/**
	 * Builds form field: tax_term
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_field_tax_term( $instance, $widget )
	{
		ob_start();
		?>

		<?php
		$taxonomies = Advanced_Categories_Widget_Utils::get_allowed_taxonomies();

		if( count( $taxonomies ) ) :
			foreach ( $taxonomies as $name => $label ) {
				self::build_term_select( $name, $label, $instance, $widget );
			}
		endif;

		?>

		<?php
		$field = ob_get_clean();

		return $field;
	}


	/**
	 * Builds select drop down for form field: tax_term
	 *
	 * Note: prior to WP4.5 get_terms() accepted the taxonomy as a separate argument.  As of 4.5
	 * get_terms() accepts one array of arguments with the taxonomy arg passed as an array value.
	 * We're calling get_terms() using the older format for sites with older versions of WP.
	 *
	 * @uses WordPress get_terms()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param string $taxonomy The registered name of the taxonomy. e.g., post_tag
	 * #param string $label    The common name of the taxonomy. e.g., Post Tag
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_term_select( $taxonomy, $label, $instance, $widget )
	{
		$args = apply_filters( 'acatw_build_term_select_args', array( 'hide_empty' => 0, 'number' => 99 ) );
		$args['fields'] = 'all'; // don't allow override
		$args['taxonomy'] = $taxonomy; // don't allow override
		$_terms = get_terms( $taxonomy, $args );

		if( empty( $_terms ) || is_wp_error( $_terms ) ) {
			return;
		}
		?>

		<?php printf( '<p>%s:</p>', sprintf( __( '%s', 'advanced-categories-widget' ), $label ) ); ?>

		<div class="widgin-multi-check">
			<?php foreach( $_terms as $_term ) : ?>
				<?php
				$checked = (  ! empty( $instance['tax_term'][$_term->taxonomy][$_term->term_id] )) ? 'checked="checked"' : '' ;

				printf( '<input id="%1$s" name="%2$s" value="%3$s" type="checkbox" %4$s/><label for="%1$s">%5$s (%6$s)</label><br />',
					$widget->get_field_id( 'tax_term-' . $taxonomy . '-' . $_term->term_id ),
					$widget->get_field_name( 'tax_term' ) . '['.$taxonomy.']['.$_term->term_id.']',
					$_term->term_id,
					$checked,
					sprintf( __( '%s', 'advanced-categories-widget' ), $_term->name ),
					$_term->count
				);
				?>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * Builds form field: show_thumb
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_field_show_thumb( $instance, $widget )
	{
		ob_start();
		?>

		<p>
			<input class="checkbox" type="checkbox" id="<?php echo $widget->get_field_id( 'show_thumb' ); ?>" name="<?php echo $widget->get_field_name( 'show_thumb' ); ?>" <?php checked( $instance['show_thumb'], 1 ); ?>/>
			<label for="<?php echo $widget->get_field_id( 'show_thumb' ); ?>">
				<?php _e( 'Display Thumbnail?', 'advanced-categories-widget' ); ?>
			</label>
		</p>

		<?php
		$field = ob_get_clean();

		return $field;
	}


	/**
	 * Builds form field: thumb_size
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_field_thumb_size( $instance, $widget )
	{
		ob_start();
		?>
		<p class="widgin-thumb-size-defaults">
			<label for="<?php echo $widget->get_field_id('thumb_size'); ?>">
				<?php _e( 'Choose Registered Image Size:', 'advanced-categories-widget' ); ?>
			</label>
			<?php self::build_img_select( $instance, $widget ); ?>
		</p>
		<?php
		$field = ob_get_clean();

		return $field;
	}


	/**
	 * Builds select drop down for form field: thumb_size
	 *
	 * @uses Advanced_Categories_Widget_Utils::get_allowed_image_sizes()
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 *
	 * @return string <select> drop down for widget form
	 */
	public static function build_img_select( $instance, $widget )
	{
		$sizes = Advanced_Categories_Widget_Utils::get_allowed_image_sizes( $fields = 'all' );

		if( count( $sizes ) ) : ?>

			<select name="<?php echo $widget->get_field_name('thumb_size'); ?>" id="<?php echo $widget->get_field_id('thumb_size'); ?>" class="widefat">
				<option value></option>
				<?php foreach( $sizes as $name => $size  ) {
					$width = absint( $size['width'] );
					$height = absint( $size['height'] );
					$dimensions = ' (' . $width . ' x ' . $height . ')';
					printf( '<option data-width="%1$s" data-height="%2$s" value="%3$s" %4$s>%5$s%6$s</option>' . "\n",
						$width,
						$height,
						esc_attr( $name ),
						selected( $instance['thumb_size'] , $name, false ),
						esc_html( $size['name'] ),
						$dimensions
					);
				} ?>
			</select>

		<?php endif;
	}


	/**
	 * Builds form fields: thumb_size_w / thumb_size_h
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_field_thumb_custom( $instance, $widget )
	{
		ob_start();
		?>
		<div class="widgin-thumbsize-wrap">

			<p>
				<label><?php _e( 'Set Custom Size:', 'advanced-categories-widget' ); ?></label><br />

				<label for="<?php echo $widget->get_field_id( 'thumb_size_w' ); ?>">
					<?php _e( 'Width:', 'advanced-categories-widget' ); ?>
				</label>
				<input class="small-text widgin-thumb-size widgin-thumb-width" id="<?php echo $widget->get_field_id( 'thumb_size_w' ); ?>" name="<?php echo $widget->get_field_name( 'thumb_size_w' ); ?>" type="number" value="<?php echo absint( $instance['thumb_size_w'] ); ?>" />

				<br />

				<label for="<?php echo $widget->get_field_id( 'thumb_size_h' ); ?>">
					<?php _e( 'Height:', 'advanced-categories-widget' ); ?>
				</label>
				<input class="small-text widgin-thumb-size widgin-thumb-height" id="<?php echo $widget->get_field_id( 'thumb_size_h' ); ?>" name="<?php echo $widget->get_field_name( 'thumb_size_h' ); ?>" type="number" value="<?php echo absint( $instance['thumb_size_h'] ); ?>" />
			</p>

			<p>
				<?php _e( 'Preview Custom Size:', 'easy-shuffle-widget' ); ?><br />
				<span class="widgin-preview-container">
					<span class="widgin-thumbnail-preview" style="font-size: <?php echo absint( $instance['thumb_size_h'] ); ?>px; height:<?php echo absint( $instance['thumb_size_h'] ); ?>px; width:<?php echo absint( $instance['thumb_size_w'] ); ?>px">
						<i class="widgin-preview-image dashicons dashicons-format-image"></i>
					</span>
				</span>
			</p>

		</div>

		<?php
		$field = ob_get_clean();

		return $field;
	}


	/**
	 * Builds form field: show_desc
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_field_show_desc( $instance, $widget )
	{
		ob_start();
		?>

		<p>
			<input id="<?php echo $widget->get_field_id( 'show_desc' ); ?>" name="<?php echo $widget->get_field_name( 'show_desc' ); ?>" type="checkbox" <?php checked( $instance['show_desc'], 1 ); ?> />
			<label for="<?php echo $widget->get_field_id( 'show_desc' ); ?>">
				<?php _e( 'Display Category Description?', 'advanced-categories-widget' ); ?>
			</label>
		</p>

		<?php
		$field = ob_get_clean();

		return $field;
	}


	/**
	 * Builds form field: desc_length
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_field_desc_length( $instance, $widget )
	{
		ob_start();
		?>

		<div class="widgin-excerptsize-wrap">

			<p>
				<label for="<?php echo $widget->get_field_id( 'desc_length' ); ?>">
					<?php _e( 'Excerpt Length:', 'advanced-categories-widget' ); ?>
				</label>
				<input class="widefat widgin-excerpt-length" id="<?php echo $widget->get_field_id( 'desc_length' ); ?>" name="<?php echo $widget->get_field_name( 'desc_length' ); ?>" type="number" step="1" min="0" value="<?php echo absint( $instance['desc_length'] ); ?>" />
			</p>

			<p>
				<?php _e( 'Preview:', 'advanced-categories-widget' ); ?><br />

				<span class="widgin-preview-container">
					<span class="widgin-excerpt-preview">
						<span class="widgin-excerpt"><?php echo wp_trim_words( Advanced_Categories_Widget_Utils::sample_description(), 15, '&hellip;' ); ?></span>
						<span class="widgin-excerpt-sample" aria-hidden="true" role="presentation"><?php echo Advanced_Categories_Widget_Utils::sample_description(); ?></span>
					</span>
				</span>
			</p>

		</div>

		<?php
		$field = ob_get_clean();

		return $field;
	}


	/**
	 * Builds form field: list_style
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_field_list_style( $instance, $widget )
	{
		ob_start();
		?>

		<p>
			<label for="<?php echo $widget->get_field_id('list_style'); ?>">
				<?php _e( 'List Format:', 'advanced-categories-widget' ); ?>
			</label>
			<select name="<?php echo $widget->get_field_name('list_style'); ?>" id="<?php echo $widget->get_field_id('list_style'); ?>" class="widefat">
				<option value="ul" <?php selected( $instance['list_style'] , 'ul' ); ?>><?php _e( 'Unordered List (ul)', 'advanced-categories-widget' ); ?></option>
				<option value="ol" <?php selected( $instance['list_style'] , 'ol' ); ?>><?php _e( 'Ordered List (ol)', 'advanced-categories-widget' ); ?></option>
				<option value="div" <?php selected( $instance['list_style'] , 'div' ); ?>><?php _e( 'Div (div)', 'advanced-categories-widget' ); ?></option>
			</select>
		</p>

		<?php
		$field = ob_get_clean();

		return $field;
	}


	/**
	 * Builds form field: show_count
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_field_show_count( $instance, $widget )
	{
		ob_start();
		?>

		<p>
			<input id="<?php echo $widget->get_field_id( 'show_count' ); ?>" name="<?php echo $widget->get_field_name( 'show_count' ); ?>" type="checkbox" <?php checked( $instance['show_count'], 1 ); ?> />
			<label for="<?php echo $widget->get_field_id( 'show_count' ); ?>">
				<?php _e( 'Display Post Count?', 'advanced-categories-widget' ); ?>
			</label>
		</p>

		<?php
		$field = ob_get_clean();

		return $field;
	}


	/**
	 * Builds form field: css_default
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance Current settings.
	 * @param object $widget   Widget object.
	 */
	public static function build_field_css_default( $instance, $widget )
	{
		ob_start();
		?>

		<p>
			<input id="<?php echo $widget->get_field_id( 'css_default' ); ?>" name="<?php echo $widget->get_field_name( 'css_default' ); ?>" type="checkbox" <?php checked( $instance['css_default'], 1 ); ?> />
			<label for="<?php echo $widget->get_field_id( 'css_default' ); ?>">
				<?php _e( 'Use Default Styles?', 'advanced-categories-widget' ); ?>
			</label>
		</p>

		<?php
		$field = ob_get_clean();

		return $field;
	}

}
