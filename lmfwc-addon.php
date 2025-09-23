<?php
/**
 * Plugin Name: License Manager for WooCommerce – Endpoint Addon
 * Plugin URI: https://github.com/WeDesignIt/LMFWC-Addon
 * Description: Adds REST API endpoints to LicenseManager for WooCommerce.
 * Version: 1.0.2
 * Author: WeDesignIt
 * Author URI: https://wedesignit.nl/
 * Text Domain: lmfwc-addon
 */

defined( 'ABSPATH' ) || exit;

// Autoload includes
foreach (glob(plugin_dir_path(__FILE__) . 'includes/*.php') as $filename) {
    require_once $filename;
}