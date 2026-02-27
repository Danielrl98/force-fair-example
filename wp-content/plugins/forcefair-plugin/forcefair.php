<?php
/**
 * Plugin Name: ForceFair Core
 * Description: Core clinical and pharmacy workflow integration for WooCommerce.
 * Version: 1.0.0
 * Author: ForceFair
 */

if (!defined('ABSPATH')) {
    exit;
}

define('FF_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('FF_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once FF_PLUGIN_DIR . 'includes/database/create-tables.php';
require_once FF_PLUGIN_DIR . 'includes/api/prescriptions-controller.php';
require_once FF_PLUGIN_DIR . 'includes/hooks/woocommerce-hooks.php';

register_activation_hook(__FILE__, ['FF_Create_Tables', 'install']);

add_action('rest_api_init', function () {
    FF_Prescriptions_Controller::register_routes();
});
