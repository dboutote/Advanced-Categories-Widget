<?php

/**
 * The template part for displaying single posts
 *
 * @package Advanced_Posts_Widget
 * @subpackage Templates
 * @since 1.0
 */
$apw_post       = get_post();
$apw_post_id    = Advanced_Posts_Widget_Utils::get_apw_post_id( $apw_post, $instance );
$apw_post_class = Advanced_Posts_Widget_Utils::get_apw_post_class( $apw_post, $instance );
$excerpt_text   = Advanced_Posts_Widget_Utils::get_apw_post_excerpt( $apw_post, $instance );
$post_thumbnail = Advanced_Posts_Widget_Views::apw_post_thumbnail( $apw_post, $instance, false );
$posted_on      = Advanced_Posts_Widget_Views::apw_posted_on( $apw_post, $instance, false );
?>

<?php do_action( 'apw_post_before', $apw_post, $instance ); ?>

<div id="post-<?php echo sanitize_html_class( $apw_post_id ); ?>" <?php post_class( $apw_post_class ); ?>>

	<?php do_action( 'apw_post_top', $apw_post, $instance ); ?>

	<div class="entry-header apw-entry-header">
		<?php  if( $instance['show_thumb'] ) { echo $post_thumbnail; }; ?>
		<?php the_title( sprintf( '<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
		<?php if ( $instance['show_date'] ) {  echo $posted_on; } ?>
	</div><!-- /.entry-header -->

	<?php  if( $instance['show_excerpt'] ) { ?>
		<span class="entry-summary apw-entry-summary">
			<?php echo $excerpt_text; ?>
		</span><!-- /.entry-summary -->
	<?php }; ?>

	<?php do_action( 'apw_post_bottom', $apw_post, $instance ); ?>

</div><!-- #post-## -->

<?php do_action( 'apw_post_after', $apw_post, $instance ); ?>