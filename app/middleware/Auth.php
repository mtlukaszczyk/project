<?php

namespace App\Middleware;

trait Auth {

    public static function checkAutorization() {
        
        if (!parent::isLogged()) {
            if (\Engine\request::$_isAjax) {
                parent::render('json', ['data' => 'err', 'message' => 'not-logged']);
            }

            parent::render('sample.twig');
        } else {
            return true;
        }
    }   
}