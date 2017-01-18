<?php
/*
 * Plugin Name: uCare - Support Ticket System
 * Author: Smartcat
 * Description: A full blown support ticket system, with notifications and user roles. Compatible with WooCommerce and Easy Digital Downloads 
 * Version: 1.0
 * Author: Smartcat
 * Author URI: https://smartcatdesign.net
 * License: GPL V2
 * 
 * 
 */

namespace SmartcatSupport;

// Die if access directly
if( !defined( 'ABSPATH' ) ) {
    die();
}


// Plugin-wide constant declarations
const PLUGIN_VERSION = '1.0';
const PLUGIN_ID = 'smartcat_support';


// Manual includes
include_once 'vendor/autoload.php';


// Boot up the container
Plugin::boot( PLUGIN_ID, PLUGIN_VERSION, __FILE__ );
