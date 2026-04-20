<?php
namespace Snootl\Frontend;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Frontend Widget Class
 */
class Widget {

	/**
	 * Role hierarchy for "Role Level & Above"
	 */
	private $role_hierarchy = array(
		'subscriber'    => 0,
		'contributor'   => 1,
		'author'        => 2,
		'editor'        => 7,
		'administrator' => 10,
	);

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_widget_script' ) );
		add_filter( 'script_loader_tag', array( $this, 'add_script_attributes' ), 10, 3 );
	}

	/**
	 * Decide whether to show the widget
	 */
	private function should_show_widget() {
		$options = get_option( 'snootl_options' );
		$api_key = isset( $options['api_key'] ) ? $options['api_key'] : '';

		if ( empty( $api_key ) ) {
			return false;
		}

		$visibility_mode = isset( $options['visibility_mode'] ) ? $options['visibility_mode'] : 'everyone';

		switch ( $visibility_mode ) {
			case 'disabled':
				return false;

			case 'everyone':
				return true;

			case 'logged_in':
				return is_user_logged_in();

			case 'role_level':
				if ( is_user_logged_in() ) {
					$user = wp_get_current_user();
					$min_role = isset( $options['min_role'] ) ? $options['min_role'] : 'subscriber';
					
					$user_max_level = -1;
					foreach ( (array) $user->roles as $role ) {
						if ( isset( $this->role_hierarchy[$role] ) ) {
							$user_max_level = max( $user_max_level, $this->role_hierarchy[$role] );
						}
					}

					$required_level = isset( $this->role_hierarchy[$min_role] ) ? $this->role_hierarchy[$min_role] : 0;
					return ( $user_max_level >= $required_level );
				}
				return false;
		}

		return false;
	}

	/**
	 * Enqueue the Snootl script properly
	 */
	public function enqueue_widget_script() {
		if ( $this->should_show_widget() ) {
			wp_enqueue_script( 
				'snootl-widget', 
				'https://cdn.snootl.com/scripts/widget/snootl-widget.esm.js', 
				array(), 
				'1.0.0', 
				false 
			);
		}
	}

	/**
	 * Add module type and data-api-key to the script tag
	 */
	public function add_script_attributes( $tag, $handle, $src ) {
		if ( 'snootl-widget' !== $handle ) {
			return $tag;
		}

		$options = get_option( 'snootl_options' );
		$api_key = isset( $options['api_key'] ) ? $options['api_key'] : '';

		// Add type="module" and data-api-key without rebuilding the whole tag string
		$tag = str_replace( '<script ', '<script type="module" data-api-key="' . esc_attr( $api_key ) . '" ', $tag );

		return $tag;
	}
}
