<?php

/**
 * Advanced_Categories_Widget_Views Class
 *
 * Handles generation of all front-facing html.
 * All methods are static, this is basically a namespacing class wrapper.
 *
 * @package Advanced_Categories_Widget
 * @subpackage Advanced_Categories_Widget_Views
 *
 * @since 1.0
 */


class Advanced_Categories_Widget_Views
{

	private function __construct(){}


	/**
	 * Opens the list for the current widget instance.
	 *
	 * Use 'acatw_list_class' filter to filter list classes before output.
	 * Use 'acatw_start_list' filter to filter $html before output.
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance   Settings for the current Categories widget instance.
	 * @param array  $categories Array of term objects.
	 * @param bool   $echo       Flag to echo or return the method's output.
	 *
	 * @return string $html Opening tag element for the list.
	 */
	public static function start_list( $instance, $categories, $echo = true )
	{
		$tag = 'ul';

		switch ( $instance['list_style'] ) {
			case 'div':
				$tag = 'div';
				break;
			case 'ol':
				$tag = 'ol';
				break;
			case 'ul':
			default:
				$tag = 'ul';
				break;
		}

		$_classes = array();
		$_classes[] = 'acatw-term-list';

		$classes = apply_filters( 'acatw_list_class', $_classes, $instance, $categories );
		$classes = ( ! is_array( $classes ) ) ? (array) $classes : $classes ;
		$classes = array_map( 'sanitize_html_class', $classes );

		$class_str = implode( ' ', $classes );

		$_html = sprintf( '<%1$s class="%2$s">',
			$tag,
			$class_str
			);

		$html = apply_filters( 'acatw_start_list', $_html, $instance, $categories );

		if( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	}


	/**
	 * Opens each list item for the current widget instance.
	 *
	 * Use 'acatw_start_list_item' filter to filter $html before output.
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param object $term       Term object.
	 * @param array  $instance   Settings for the current Categories widget instance.
	 * @param array  $categories Array of term objects.
	 * @param bool   $echo       Flag to echo or return the method's output.
	 *
	 * @return string $html Opening tag element for the list item.
	 */
	public static function start_list_item( $term, $instance, $categories, $echo = true )
	{
		if( ! $term ){
			return;
		}

		$item_id    = Advanced_Categories_Widget_Utils::get_item_id( $term, $instance );
		$item_class = Advanced_Categories_Widget_Utils::get_item_class( $term, $instance );
		$class      = 'acatw-list-item ' . $item_class;
		
		$tag = ( 'div' === $instance['list_style'] ) ? 'div' : 'li';

		$_html = sprintf( '<%1$s id="%2$s" class="%3$s">',
			$tag,
			$item_id,
			$class
			);

		$html = apply_filters( 'acatw_start_list_item', $_html, $term, $instance, $categories );

		if( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	}


	/**
	 * Builds each list item for the current widget instance.
	 *
	 * Use 'acatw_list_item' filter to filter $html before output.
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param object $term       Term object.
	 * @param array  $instance   Settings for the current Categories widget instance.
	 * @param array  $categories Array of term objects.
	 * @param bool   $echo       Flag to echo or return the method's output.
	 *
	 * @return string $html Closing tag element for the list item.
	 */
	public static function list_item( $term, $instance, $categories, $echo = true )
	{
		$item_desc  = Advanced_Categories_Widget_Utils::get_term_excerpt( $term, $instance );
		$item_id    = Advanced_Categories_Widget_Utils::get_item_id( $term, $instance );
		$item_class = Advanced_Categories_Widget_Utils::get_item_class( $term, $instance );
		
		$thumb_div = ( ! empty( $instance['show_thumb'] ) ) 
			? self::the_item_thumbnail_div( $term, $instance, false ) 
			: '' ;
		$post_count = ( ! empty( $instance['show_count'] ) ) 
			? self::the_term_post_count( $term, $instance, false )
			: '' ;

		ob_start();

		do_action( 'acatw_item_before', $term, $instance );
		?>
			<div id="term-<?php echo $item_id ;?>" class="<?php echo $item_class ;?>" >

				<?php do_action( 'acatw_item_top', $term, $instance ); ?>

					<div class="term-header acatw-term-header">
						<?php  if( ! empty( $thumb_div ) ) { echo $thumb_div; }; ?>
						<?php 
						printf( '<h3 class="term-title acatw-term-title"><a href="%s" rel="bookmark">%s</a></h3>',
							esc_url( get_term_link( $term ) ),
							sprintf( __( '%s', 'advanced-categories-widget'), $term->name )
						);
						?>
						<?php if ( $instance['show_count'] ) {  echo $post_count; } ?>
					</div><!-- /.term-header -->
					
					<?php  if( $instance['show_desc'] ) { ?>
						<span class="term-summary acatw-term-summary">
							<?php echo $item_desc; ?>
						</span><!-- /.term-summary -->
					<?php }; ?>					

				<?php do_action( 'acatw_item_bottom', $term, $instance ); ?>

			</div><!-- #term-## -->
		<?php
		do_action( 'acatw_item_after', $term, $instance );

		$_html = ob_get_clean();
		
		$html = apply_filters( 'acatw_list_item', $_html, $term, $instance, $categories );

		if( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	}



	/**
	 * Closes the list item for the current widget instance.
	 *
	 * Use 'acatw_end_list_item' filter to filter $html before output.
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param object $term       Term object.
	 * @param array  $instance   Settings for the current Categories widget instance.
	 * @param array  $categories Array of term objects.
	 * @param bool   $echo       Flag to echo or return the method's output.
	 *
	 * @return string $html Closing tag element for the list item.
	 */
	public static function end_list_item( $term, $instance, $categories, $echo = true )
	{
		$tag = ( 'div' === $instance['list_style'] ) ? 'div' : 'li';

		$_html = sprintf( '</%1$s>', $tag );

		$html = apply_filters( 'acatw_end_list_item', $_html, $term, $instance, $categories );

		if( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	}


	/**
	 * Closes the list for the current widget instance.
	 *
	 * Use 'acatw_end_list' filter to filter $html before output.
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance   Settings for the current Categories widget instance.
	 * @param array  $categories Array of term objects.
	 * @param bool   $echo       Flag to echo or return the method's output.
	 *
	 * @return string $html Closing tag element for the list.
	 */
	public static function end_list( $instance, $categories, $echo = true )
	{
		$_html = '';

		switch ( $instance['list_style'] ) {
			case 'div':
				$_html = "</div>\n";
				break;
			case 'ol':
				$_html = "</ol>\n";
				break;
			case 'ul':
			default:
				$_html = "</ul>\n";
				break;
		}

		$html = apply_filters( 'acatw_end_list', $_html, $instance, $categories );

		if( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	}


	/**
	 * Outputs plugin attribution
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @return string Plugin attribution.
	 */
	public static function colophon( $echo = true )
	{
		$attribution = '<!-- Advanced Categories Widget generated by http://darrinb.com/plugins/advanced-categories-widget -->';

		if ( $echo ) {
			echo $attribution;
		} else {
			return $attribution;
		}
	}


	/**
	 * Builds html for thumbnail section
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param object $term     Term object.
	 * @param array  $instance Settings for the current Categories widget instance.
	 * @param bool   $echo     Flag to echo or return the method's output.
	 *
	 * @return string $html Term thumbnail section.
	 */
	public static function the_item_thumbnail_div( $term = 0, $instance = array(), $echo = true )
	{
		if ( empty( $term ) ) {
			return '';
		}

		$_html = '';
		$_thumb = Advanced_Categories_Widget_Utils::get_term_thumbnail( $term, $instance );

		$_classes = array();
		$_classes[] = 'acatw-term-thumbnail';

		$classes = apply_filters( 'acatw_thumbnail_div_class', $_classes, $instance, $term );
		$classes = ( ! is_array( $classes ) ) ? (array) $classes : $classes ;
		$classes = array_map( 'sanitize_html_class', $classes );

		$class_str = implode( ' ', $classes );

		if( '' !== $_thumb ) {

			$_html .= sprintf('<span class="%1$s">%2$s</span>',
				$class_str,
				sprintf('<a href="%s">%s</a>',
					esc_url( get_term_link( $term ) ),
					$_thumb
				)
			);

		};

		$html = apply_filters( 'acatw_item_thumbnail_div', $_html, $term, $instance );

		if( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	}



	/**
	 * Builds html for post count section
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param object $term     Term object.
	 * @param array  $instance Settings for the current Categories widget instance.
	 * @param bool   $echo     Flag to echo or return the method's output.
	 *
	 * @return string $html Post count span.
	 */
	public static function the_term_post_count( $term = 0, $instance = array(), $echo = true )
	{
		if ( empty( $term ) ) {
			return '';
		}
		
		$type_text = ( $term->count > 1 ) ? 'Posts' : 'Post' ;
		$type_text = apply_filters( 'acatw_post_count_posttype', $type_text, $term->count );

		$term_count = number_format_i18n( $term->count );
		
		/* translators: 1: Number of posts 2: post type name */
		$_count_text = sprintf( __( '%1$d %2$s', 'advanced-categories-widget'), 
			$term_count,
			$type_text
		);

		$_html = sprintf( '<span class="acatw-post-count term-post-count"><a href="%1$s" rel="bookmark">%2$s</a></span>',
			esc_url( get_term_link( $term ) ),
			$_count_text			
		);
		
		$html = apply_filters( 'acatw_post_count_text', $_html, $term, $instance );

		if( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	}
	
	
	/**
	 * Builds category list
	 *
	 * Note: This is for a later release.
	 * 
	 * @see Widget_ACW_Advanced_Categories::widget()
	 *
	 * @access public
	 *
	 * @since xx
	 *
	 * @return string $html Post count span.
	 */
	public static function walk_categories()
	{
		$args = func_get_args();
		$walker = new Advanced_Categories_Widget_Walker;
		return call_user_func_array( array( $walker, 'walk' ), $args );
	}

}

