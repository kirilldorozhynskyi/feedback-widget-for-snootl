<?php
namespace Snootl;

use Snootl\Admin\Settings;
use Snootl\Frontend\Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Plugin Class
 */
class Plugin {

	/**
	 * @var Settings
	 */
	public $admin;

	/**
	 * @var Widget
	 */
	public $frontend;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->initialize();
	}

	/**
	 * Initialize components
	 */
	private function initialize() {
		$this->admin    = new Settings();
		$this->frontend = new Widget();
	}
}
