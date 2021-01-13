<?php

/**
 * Plugin Name: Super Reactions
 * Description: Get meaningful reactions from users.
 * Version: 1.0.4
 * Author: Super Reactions Team
 * Text Domain: super-reactions
 * Domain Path: /languages
 * License: GPLv3
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

Admin::instance();
Assets::instance();
Front::instance();
