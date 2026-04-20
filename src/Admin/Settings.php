<?php
namespace Snootl\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin Settings Class
 */
class Settings {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'wp_ajax_snootl_validate_api_key', array( $this, 'validate_api_key_callback' ) );
	}

	public function enqueue_assets( $hook ) {
		if ( 'settings_page_snootl-setting-admin' !== $hook ) {
			return;
		}

		wp_enqueue_style( 
            'snootl-admin-css', 
            plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'assets/css/admin.css', 
            array(), 
            '1.0.0' 
        );
        
		wp_enqueue_script( 
            'snootl-admin-js', 
            plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'assets/js/admin.js', 
            array( 'jquery' ), 
            '1.0.0', 
            true 
        );

		wp_localize_script( 'snootl-admin-js', 'snootl_admin', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'snootl_admin_nonce' ),
		) );
	}

	public function add_plugin_page() {
		add_options_page(
			'Snootl Settings',
			'Snootl',
			'manage_options',
			'snootl-setting-admin',
			array( $this, 'render_admin_page' )
		);
	}

	public function render_admin_page() {
		$options = get_option( 'snootl_options' );
		include plugin_dir_path( dirname( dirname( __FILE__ ) ) ) . 'templates/admin-settings.php';
	}

	public function page_init() {
		register_setting(
			'snootl_option_group',
			'snootl_options',
			array( $this, 'sanitize' )
		);
	}

	public function sanitize( $input ) {
		$new_input = array();
		$new_input['api_key'] = isset( $input['api_key'] ) ? sanitize_text_field( $input['api_key'] ) : '';
		$new_input['visibility_mode'] = isset( $input['visibility_mode'] ) ? sanitize_text_field( $input['visibility_mode'] ) : 'everyone';
		$new_input['min_role'] = isset( $input['min_role'] ) ? sanitize_text_field( $input['min_role'] ) : 'subscriber';

		return $new_input;
	}

	/**
	 * AJAX Callback to validate API Key
	 */
	public function validate_api_key_callback() {
		check_ajax_referer( 'snootl_admin_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'Unauthorized' ) );
		}

		$api_key = isset( $_POST['api_key'] ) ? sanitize_text_field( wp_unslash( $_POST['api_key'] ) ) : '';

		if ( empty( $api_key ) ) {
			wp_send_json_error( array( 'message' => 'API Key is empty' ) );
		}

		$response = wp_remote_get( 'https://app.snootl.com/api/widgets/' . $api_key );

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( array( 'message' => 'Connection error: ' . $response->get_error_message() ) );
		}

		$status_code = wp_remote_retrieve_response_code( $response );

		if ( 200 === $status_code ) {
			$body = json_decode( wp_remote_retrieve_body( $response ), true );
			wp_send_json_success( array( 
				'message'      => 'Valid API Key',
				'project_name' => isset( $body['name'] ) ? $body['name'] : 'Snootl Project'
			) );
		} else {
			wp_send_json_error( array( 'message' => 'Invalid API Key (Status: ' . $status_code . ')' ) );
		}
	}
}
