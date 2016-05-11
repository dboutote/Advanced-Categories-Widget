<?php
/**
 * Advanced Categories Widget Uninstall actions
 *
 * Removes all options set by the plugin.
 *
 * @package Advanced_Categories_Widget
 * @subpackage Admin
 *
 * @since 1.0
 */


if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// remove our options
delete_option( 'acatw_use_css' );