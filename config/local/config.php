<?php
/** Session panel */
define('SESSION_PANEL', 'app');

/** App Version */
define('APP_VERSION', 'local');

if (APP_VERSION == 'local') {
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}

/** Base URL */
define('base_url', 'http://127.0.0.1/gitproj/');
define('BASE_SERVER_URL', '/Applications/XAMPP/xamppfiles/htdocs/gitproj/');