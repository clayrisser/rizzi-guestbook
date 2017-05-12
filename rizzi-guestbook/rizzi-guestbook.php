<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://jamrizzi.com/
 * @since             4.0.0
 * @package           Rizzi_Guestbook
 *
 * @wordpress-plugin
 * Plugin Name:       Rizzi Guestbook
 * Plugin URI:        https://wordpress.org/plugins/rizzi-guestbook/
 * Description:       Create a guestbook using the built-in commenting capabilities of WordPress
 * Version:           4.0.0
 * Author:            Jam Risser
 * Author URI:        https://jamrizzi.com/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       rizzi-guestbook
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Import vendor
require_once __DIR__ . '/vendor/autoload.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-rizzi-guestbook-activator.php
 */
function activate_rizzi_guestbook() {
  $option_name = 'rizzi_guestbook';
  $pages = get_pages();
  update_option( $option_name . '_guestbook_page', $pages[0]->ID );
  update_option( $option_name . '_sign_guestbook_title', 'Sign Guestbook' );
  update_option( $option_name . '_show_guestbook_title', 'Show Guestbook' );
  update_option( $option_name . '_only_registered', false );
  update_option( $option_name . '_enable_recaptcha', false );
  update_option( $option_name . '_recaptcha_public_key', '' );
  update_option( $option_name . '_recaptcha_private_key', '' );
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rizzi-guestbook-activator.php';
	Rizzi_Guestbook_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-rizzi-guestbook-deactivator.php
 */
function deactivate_rizzi_guestbook() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rizzi-guestbook-deactivator.php';
	Rizzi_Guestbook_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_rizzi_guestbook' );
register_deactivation_hook( __FILE__, 'deactivate_rizzi_guestbook' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-rizzi-guestbook.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    4.0.0
 */
function run_rizzi_guestbook() {

	$plugin = new Rizzi_Guestbook();
	$plugin->run();

}
run_rizzi_guestbook();
