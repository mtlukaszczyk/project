<?php
$serverConf = 'local';

define('CONFIG', [
    'CHARSET' => 'UTF-8',
    'TITLE' => '',
    'META' => [
        'VIEWPORT' => 'width=device-width, initial-scale=1'
    ],
    'USER_PAGE' => [
        403 => true,
        404 => true
    ],
    'SERVER_CONF' => $serverConf,
    'SERVER_MAINTENANCE' => false,
    'HASH' => [
        'TYPE' => PASSWORD_BCRYPT,
        'COST' => 12
    ]
]);

define('MAIN_CONTROLLER', 'Test');
define('MAIN_TEMPLATE_DIR', 'test');
define('LANG_SYMBOL', 'de');