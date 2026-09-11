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
		add_action( 'wp_head', array( $this, 'print_widget_script' ), 0 );
		add_action( 'admin_head', array( $this, 'print_admin_widget_script' ), 0 );
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
	 * Decide whether to show the widget in wp-admin.
	 */
	private function should_show_admin_widget() {
		$options = get_option( 'snootl_options' );
		$api_key = isset( $options['api_key'] ) ? $options['api_key'] : '';

		if ( empty( $api_key ) ) {
			return false;
		}

		return ! empty( $options['show_in_admin'] );
	}

	/**
	 * Print the Snootl script at the beginning of the head.
	 */
	public function print_widget_script() {
		if ( $this->should_show_widget() ) {
			$this->print_script_tag();
		}
	}

	/**
	 * Print the Snootl script in wp-admin when enabled.
	 */
	public function print_admin_widget_script() {
		if ( $this->should_show_admin_widget() ) {
			$this->print_script_tag();
		}
	}

	/**
	 * Print the widget script tag.
	 */
	private function print_script_tag() {
		$options = get_option( 'snootl_options' );
		$api_key = isset( $options['api_key'] ) ? $options['api_key'] : '';

		printf(
			'<script type="module" src="%1$s" data-api-key="%2$s" data-feedback-source="plugin" data-snootl-load="interaction" fetchpriority="low"></script>' . "\n",
			esc_url( 'https://cdn.snootl.com/scripts/widget/snootl-widget.esm.js?ver=1.1.0' ),
			esc_attr( $api_key )
		);
	}
}
