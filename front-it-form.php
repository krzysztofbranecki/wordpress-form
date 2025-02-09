<?php
/**
 * Plugin Name: Front IT Form
 * Version: 1.0.0
 * Author: Krzystof Branecki
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: front-it-form
 */

use FrontIT\Form\Constants;
use FrontIT\Form\Plugin;
use FrontIT\Form\Database\Installer;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Composer autoloader
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

// Define plugin constants
define( 'FRONT_IT_FORM_VERSION', Constants::VERSION );
define( 'FRONT_IT_FORM_DB_VERSION', Constants::DB_VERSION );
define( 'FRONT_IT_FORM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'FRONT_IT_FORM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Initialize plugin
function front_it_form_init() {
	$plugin = new Plugin();
	$plugin->init();
}
add_action( 'plugins_loaded', 'front_it_form_init' );

// Activation Hook
register_activation_hook( __FILE__, function() {
	$installer = new Installer();
	$installer->install();
	add_option( Constants::OPTION_VERSION, Constants::VERSION );
	flush_rewrite_rules();
} );

// Deactivation Hook
register_deactivation_hook( __FILE__, function() {
	flush_rewrite_rules();
} );
