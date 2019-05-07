<?php

namespace App\Controller;

use \App\Model\User as User;

class Test extends Controller {

    use \App\Middleware\Auth;

    public static function index() {
        self::render('userlist.twig', [
            'users' => User::get()
        ]);
    }

    public static function init() {
        //self::checkAutorization();
    }

}
