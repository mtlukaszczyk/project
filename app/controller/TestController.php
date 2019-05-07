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
    
    // test commit

    public static function init() {
        //self::checkAutorization();
    }

}
