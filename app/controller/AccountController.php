<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Capsule\Manager as DB;

namespace App\Controller;

class Account extends Controller {

    private static $User;

    public static function init() {
        if (CONFIG['SERVER_MAINTENANCE']) {
            (function() {
                $AUTH_USER = 'test';
                $AUTH_PASS = 'ttt';
                header('Cache-Control: no-cache, must-revalidate, max-age=0');
                $has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
                $is_not_authenticated = (
                        !$has_supplied_credentials ||
                        $_SERVER['PHP_AUTH_USER'] != $AUTH_USER ||
                        $_SERVER['PHP_AUTH_PW'] != $AUTH_PASS
                        );

                if ($is_not_authenticated) {
                    header('HTTP/1.1 401 Authorization Required');
                    header('WWW-Authenticate: Basic realm="Server maintenance. Please try again later."');
                    echo 'Server maintenance';
                    exit;
                }
            })();
        }

    }

    public static function index() {

    }

    public static function logIn($user, $password) {

        $data = \App\Model\User::getUserLoginData($user, $password);

        if (count($data) === 1) {

            try {
                self::$User = \App\Model\User::findOrFail($data[0]->id);
                session_regenerate_id(true);

                \Engine\session::set("user", ['id' => $data[0]->id,
                    'email' => $data[0]->email,
                ]);
                \Engine\session::set('user-agent', $_SERVER['HTTP_USER_AGENT']);
                \Engine\session::set('ip', $_SERVER['REMOTE_ADDR']);

                if (\Engine\session::check('redirect')) {
                    $redirect = \Engine\session::get('redirect');
                    \Engine\session::remove('redirect');
                    self::render('json', [
                        'link' => \Engine\app::getLink($data[0]->lang_id, $redirect['controller'], $redirect['action'], $redirect['params'])
                    ]);
                } else {
                    self::render('json', [
                        'link' => \Engine\app::getLink($data[0]->lang_id)
                    ]);
                }
            } catch (ModelNotFoundException $e) {
                \Engine\session::set('message', "Wrong mail or password");
                self::render('json', [
                    'link' => \Engine\app::getLink()
                ]);
            }
        } else {
            \Engine\session::set('message', "Wrong mail or password");
            self::render('json', [
                'link' => \Engine\app::getLink()
            ]);
        }
    }

    public static function logout() {
        \Engine\session::remove('user');
        \Engine\session::remove('user-agent');
        \Engine\session::remove('ip');

        header("Location: " . base_url);
        die();
    }
}
