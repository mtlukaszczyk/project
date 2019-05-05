<?php

namespace App\Controller;

class Test extends Controller {
    
    use \App\Middleware\Auth;
    
    public static function beforeRender() {
        parent::beforeRender();
        //self::checkAutorization();
        
        return true;
    }

    public static function index() {
        \Engine\assets::add('test.js');
        
        self::render('sample.twig', [
            'sampleData' => 'sample data from controller'
        ]);
    }
    
    public static function init() {

    }
}