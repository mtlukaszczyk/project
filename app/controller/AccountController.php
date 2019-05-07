<?php
namespace App\Controller;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Capsule\Manager as DB;

class Account extends Controller {

    private static $User;

    public static function init() {

    }

    public static function index() {

    }

    public static function logIn($user, $password) {

        $data = \App\Model\User::getUserLoginData($user, $password);

        if (count($data) === 1) {

            try {
                self::$User = \App\Model\User::findOrFail($data[0]->id);
                session_regenerate_id(true);

                \Engine\Session::set("user", ['id' => $data[0]->id,
                    'email' => $data[0]->email,
                ]);
                \Engine\Session::set('user-agent', $_SERVER['HTTP_USER_AGENT']);
                \Engine\Session::set('ip', $_SERVER['REMOTE_ADDR']);

                if (\Engine\Session::check('redirect')) {
                    $redirect = \Engine\Session::get('redirect');
                    \Engine\Session::remove('redirect');
                    self::render('json', [
                        'link' => \Engine\App::getLink($data[0]->lang_id, $redirect['controller'], $redirect['action'], $redirect['params'])
                    ]);
                } else {
                    self::render('json', [
                        'link' => \Engine\App::getLink($data[0]->lang_id)
                    ]);
                }
            } catch (ModelNotFoundException $e) {
                \Engine\Session::set('message', "Wrong mail or password");
                self::render('json', [
                    'link' => \Engine\App::getLink()
                ]);
            }
        } else {
            \Engine\Session::set('message', "Wrong mail or password");
            self::render('json', [
                'link' => \Engine\App::getLink()
            ]);
        }
    }

    public static function logout() {
        \Engine\Session::remove('user');
        \Engine\Session::remove('user-agent');
        \Engine\Session::remove('ip');

        header("Location: " . base_url);
        die();
    }
}