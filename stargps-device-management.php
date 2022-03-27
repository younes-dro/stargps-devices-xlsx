<?php

/**
 * Plugin Name:     Stargps Devices Xlsx 
 * Plugin URI:      http://stargps.ma/
 * Description:     Management of GPS devices SIM card credit .
 * Author:          Younes DRO
 * Author URI:      https://github.com/younes-dro/
 * Text Domain:     stargps-devices-management
 * Domain Path:     /languages
 * Version:         1.0.0
 *
 * @package         Stargps_Devices_Management
 */

if ( ! defined ( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

define( 'STARGPSDEVICESMANAGEMENT_BASENAME', plugin_basename( __FILE__ ) );

define( 'STARGPSDEVICESMANAGEMENT_ROOTFILE', __FILE__ );

define( 'STARGPSDEVICESMANAGEMENT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

define( 'STARGPSDEVICESMANAGEMENT_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );

define( 'STARGPSDEVICESMANAGEMENT_XLSX_FOLDER', 'satargps-xlsx' );

/**
 * The code that runs during plugin activation.
 */
function activate_stargps_devices_management() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-stargps-devices-management-activator.php';
	Stargps_Devices_Management_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_stargps_devices_management() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-stargps-devices-management-deactivator.php';
	Stargps_Devices_Management_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_stargps_devices_management' );

register_deactivation_hook( __FILE__, 'deactivate_stargps_devices_management' );


/**
 * The core plugin class
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-stargps-devices-management.php';

/**
 * Begins execution of the plugin.
 * @since    1.0.0
 */


function Stargps_Devices_Management_init() {
    $StarGPSDevicesMAnagement = Stargps_Devices_Management::instance();
}

add_action('plugins_loaded', 'Stargps_Devices_Management_init');
