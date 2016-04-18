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
	 * Opens the post list for the current Categories widget instance.
	 *
	 * Use 'advcatswdgt_post_list_class' filter to filter list classes before output.
	 * Use 'advcatswdgt_start_list' filter to filter $html before output.
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance   Settings for the current Categories widget instance.
	 * @param array  $categories Array of term objects.
	 * @param bool   $echo       Flag to echo or return the method's output.
	 *
	 * @return string $html Opening tag element for the post list.
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
		$_classes[] = 'acw-cats-list';

		$classes = apply_filters( 'advcatswdgt_list_class', $_classes, $instance, $categories );
		$classes = ( ! is_array( $classes ) ) ? (array) $classes : $classes ;
		$classes = array_map( 'sanitize_html_class', $classes );

		$class_str = implode( ' ', $classes );

		$_html = sprintf( '<%1$s class="%2$s">',
			$tag,
			$class_str
			);

		$html = apply_filters( 'advcatswdgt_start_list', $_html, $instance, $categories );

		if( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	}


	/**
	 * Opens the list item for the current Categories widget instance.
	 *
	 * Use 'advcatswdgt_start_list_item' filter to filter $html before output.
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

		$item_id    = Advanced_Categories_Widget_Utils::get_unique_term_id( $term, $instance );
		$item_class = Advanced_Categories_Widget_Utils::get_term_class( $term, $instance );

		$tag = ( 'div' === $instance['list_style'] ) ? 'div' : 'li';

		$_html = sprintf( '<%1$s id="%2$s" class="%3$s">',
			$tag,
			$item_id,
			$item_class
			);

		$html = apply_filters( 'advcatswdgt_start_list_item', $_html, $instance, $categories );

		if( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	}



	public static function list_item( $term, $instance, $categories, $echo = true )
	{

		$item_link  = get_term_link( $term );
		$item_desc  = Advanced_Categories_Widget_Utils::get_term_excerpt( $term, $instance );
		$item_id    = Advanced_Categories_Widget_Utils::get_unique_term_id( $term, $instance );
		$item_class = Advanced_Categories_Widget_Utils::get_term_class( $term, $instance );
		$item_thumb = '';
		if( ! empty( $instance['show_thumb'] ) ){
			$item_thumb = self::term_thumbnail( $term, $instance, false );
		}
		$post_count = '';
		if( ! empty( $instance['show_count'] ) ){
			$post_count = self::term_post_count( $term, $instance, false );
		}

		ob_start();

		do_action( 'advcatswdgt_item_before', $term, $instance );
		?>
			<div id="term-<?php echo sanitize_html_class( $item_id ); ?>">

				<?php do_action( 'advcatswdgt_item_top', $term, $instance ); ?>

					<div class="term-header advcatswdgt-term-header">
						<?php  if( ! empty( $item_thumb ) ) { echo $item_thumb; }; ?>
						<?php 
						printf( '<h3 class="advcatswdgt-term-title"><a href="%s" rel="bookmark">%s</a></h3>',
							esc_url( get_term_link( $term ) ),
							sprintf( __('%s', 'advanced-categories-widget'), $term->name )
						);
						?>
						<?php if ( $instance['show_count'] ) {  echo $post_count; } ?>
					</div><!-- /.term-header -->
					
					<?php  if( $instance['show_desc'] ) { ?>
						<span class="term-summary advcatswdgt-term-summary">
							<?php echo $item_desc; ?>
						</span><!-- /.term-summary -->
					<?php }; ?>					

				<?php do_action( 'advcatswdgt_item_bottom', $term, $instance ); ?>

			</div><!-- #term-## -->
		<?php
		do_action( 'advcatswdgt_item_after', $term, $instance );

		$html = ob_get_clean();

		if( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	}



	/**
	 * Closes the list item for the current Categories widget instance.
	 *
	 * Use 'advcatswdgt_end_list_item' filter to filter $html before output.
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

		$html = apply_filters( 'advcatswdgt_end_list_item', $_html, $instance, $categories );

		if( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	}


	/**
	 * Closes the post list for the current Categories widget instance.
	 *
	 * Use 'advcatswdgt_end_list' filter to filter $html before output.
	 *
	 * @access public
	 *
	 * @since 1.0
	 *
	 * @param array  $instance   Settings for the current Categories widget instance.
	 * @param array  $categories Array of term objects.
	 * @param bool   $echo       Flag to echo or return the method's output.
	 *
	 * @return string $html Closing tag element for the post list.
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

		$html = apply_filters( 'advcatswdgt_end_list', $_html, $instance, $categories );

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
	 * Builds html for thumbnail section of post
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
	public static function term_thumbnail( $term = 0, $instance = array(), $echo = true )
	{
		if ( empty( $term ) ) {
			return '';
		}

		$_html = '';
		$_thumb = Advanced_Categories_Widget_Utils::get_term_thumb( $term, $instance );

		$_classes = array();
		$_classes[] = 'advcatswdgt-term-thumbnail';

		$classes = apply_filters( 'advcatswdgt_thumbnail_div_class', $_classes, $instance, $term );
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

		$html = apply_filters( 'advcatswdgt_post_thumbnail', $_html, $term, $instance );

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
	 * @return string $html Post thumbnail section.
	 */
	public static function term_post_count( $term = 0, $instance = array(), $echo = true )
	{
		if ( empty( $term ) ) {
			return '';
		}

		$_html = sprintf( '<span class="advcatswdgt-post-count term-post-count"><span class="screen-reader-text">%1$s </span><a href="%2$s" rel="bookmark">%3$s</a></span>',
			_x( 'Post count', 'Number of posts in category.' ),
			esc_url( get_term_link( $term ) ),
			$term->count
		);

		$html = apply_filters( 'advcatswdgt_post_count', $_html, $term, $instance );

		if( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	}

}

