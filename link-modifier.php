<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://localhost
 * @since             1.0.0
 * @package           Links_Modifier
 *
 * @wordpress-plugin
 * Plugin Name:       Link Modifier
 * Plugin URI:        https://github.com/dkrys7ev/books-online
 * Description:       Adds `hreflang` attributes to all internal links based on what language the current page is
 * Version:           1.0.0
 * Author:            Danislav Krastev
 * Author URI:        https://localhost
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       link-modifier
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Load plugin classes
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-hreflang-meta-box.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-link-modifier.php';
