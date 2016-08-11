<?php
/**
 * Advanced Categories Widget
 *
 * @package Advanced_Categories_Widget
 *
 * @license     http://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 * @version     1.2
 *
 * Plugin Name: Advanced Categories Widget
 * Plugin URI:  http://darrinb.com/plugins/advanced-categories-widget
 * Description: A highly customizable recent categories widget.
 * Version:     1.2
 * Author:      Darrin Boutote
 * Author URI:  http://darrinb.com
 * Text Domain: advanced-categories-widget
 * Domain Path: /lang
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */


// No direct access
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}


define( 'ADVANCED_CATS_WIDGET_FILE', __FILE__ );


/**
 * Instantiates the main Advanced Categories Widget instance
 *
 * @since 1.0
 */
function _advanced_categories_widget_init() {

	include dirname( __FILE__ ) . '/inc/class-advanced-categories-widget-utils.php';
	include dirname( __FILE__ ) . '/inc/class-advanced-categories-widget-fields.php';
	require dirname( __FILE__ ) . '/inc/class-widget-acw-advanced-categories.php';
	include dirname( __FILE__ ) . '/inc/class-advanced-categories-widget-views.php';
	include dirname( __FILE__ ) . '/inc/class-advanced-categories-widget-init.php';
	
	$Advanced_Categories_Widget_Init = new Advanced_Categories_Widget_Init( __FILE__ );
	$Advanced_Categories_Widget_Init->init();

}
add_action( 'plugins_loaded', '_advanced_categories_widget_init', 99 );