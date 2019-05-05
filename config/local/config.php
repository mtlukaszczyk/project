<?php
/** Session panel */
define('session_panel', 'app');

/** App Version */
define('app_version', 'local');

if (app_version == 'local') {
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}

/** Base URL */
define('base_url', 'http://127.0.0.1/gitproj/');