<?php
/**
 * Bootstrap PDC Leges
 *
 * @wordpress-plugin
 * Plugin Name:       PDC Leges
 * Plugin URI:        https://www.yardinternet.nl
 * Description:       Core of PDC Leges
 * Version:           1.0.0
 * Author:            Edwin Siebel
 * Author URI:        https://www.yardinternet.nl/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pdc-leges
 */

use OWC\PDC\Leges\Autoloader;
use OWC\PDC\Leges\Foundation\Plugin;

/**
 * If this file is called directly, abort.
 */
if ( ! defined('WPINC') ) {
	die;
}

/**
 * Only manual loaded file: the autoloader.
 */
require_once __DIR__ . '/autoloader.php';
new Autoloader();

/**
 * Begin execution of the plugin
 *
 * This hook is called once any activated plugins have been loaded. Is generally used for immediate filter setup, or
 * plugin overrides. The plugins_loaded action hook fires early, and precedes the setup_theme, after_setup_theme, init
 * and wp_loaded action hooks.
 */

$plugin = new Plugin(__DIR__);

add_action('plugins_loaded', function() use ($plugin) {
	$plugin->boot();
}, 9);
