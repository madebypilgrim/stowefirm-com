<?php
/**
 * Craft web bootstrap file
 */

// Set path constants
define('CRAFT_BASE_PATH', dirname(__DIR__));
define('CRAFT_VENDOR_PATH', CRAFT_BASE_PATH.'/vendor');

// Load Composer's autoloader
require_once CRAFT_VENDOR_PATH.'/autoload.php';

// Load dotenv?
if (file_exists(CRAFT_BASE_PATH.'/.env')) {
    (new Dotenv\Dotenv(CRAFT_BASE_PATH))->load();

    // Custom constants
    define('DEV_EMAIL', getenv('DEV_EMAIL'));
    define('CRAFT_CACHE', getenv('CRAFT_CACHE'));
    define('HONEYPOT_HANDLE', 'liquid_gold');
    define('GOOGLE_MAPS_API', getenv('GOOGLE_MAPS_API'));
}

// Load and run Craft
define('CRAFT_ENVIRONMENT', getenv('ENVIRONMENT') ?: 'production');
$app = require CRAFT_VENDOR_PATH.'/craftcms/cms/bootstrap/web.php';
$app->run();
