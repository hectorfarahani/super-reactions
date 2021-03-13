<?php

/**
 * Plugin Name: Super Reactions
 * Description: Get meaningful reactions from users.
 * Version:     1.3.1
 * Author:      Super Reactions Team
 * Text Domain: super-reactions
 * Domain Path: /languages
 * License:     GPLv3
 */

namespace SREA;

use SREA\Front\Init as Front;
use SREA\Admin\Init as Admin;
use SREA\Includes\Assets;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once 'vendor/autoload.php';

register_activation_hook( __FILE__, '\SREA\srea_activation_hook_callback' );

function srea_activation_hook_callback() {
	\SREA\Includes\Init::activate();
}

register_deactivation_hook( __FILE__, '\SREA\srea_deactivation_hook_callback' );

function srea_deactivation_hook_callback() {
	\SREA\Includes\Init::deactivate();
}


Admin::instance();
Assets::instance();
Front::instance();
