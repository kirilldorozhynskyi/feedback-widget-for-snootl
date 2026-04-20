<?php
/**
 * Plugin Name: Feedback Widget for Snootl
 * Plugin URI: https://snootl.com/
 * Description: Integrates Snootl widget script with premium visibility controls.
 * Version: 1.1.0
 * Tested up to: 6.9
 * Author: justDev
 * Author URI: https://justdev.org
 * License: GPL2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Load Composer Autoloader
 */
if ( file_exists( plugin_dir_path( __FILE__ ) . 'vendor/autoload.php' ) ) {
	require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
}

/**
 * Initialize the Plugin
 */
if ( class_exists( 'Snootl\Plugin' ) ) {
	new Snootl\Plugin();
} else {
	// Fallback autoloader if vendor is not present (optional, but good for local dev without composer install)
	spl_autoload_register( function( $class ) {
		$prefix = 'Snootl\\';
		$base_dir = __DIR__ . '/src/';
		$len = strlen( $prefix );
		if ( strncmp( $prefix, $class, $len ) !== 0 ) {
			return;
		}
		$relative_class = substr( $class, $len );
		$file = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';
		if ( file_exists( $file ) ) {
			require $file;
		}
	});

	if ( class_exists( 'Snootl\Plugin' ) ) {
		new Snootl\Plugin();
	}
}
