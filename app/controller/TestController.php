<?php

namespace App\Controller;

use \App\Model\User as User;
use \Engine\Redis as Redis;

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
    
    //sudo redis-server /usr/local/etc/redis.conf
    
    public static function redisTest() {
        
        Redis::flushAll();
        Redis::lpush('lista', ['adam', 'jacek', 'wacek']);
        $keys = Redis::keys('*');
        var_dump($keys);
        
        $len = Redis::llen('lista');
        
        $data = Redis::lrange('lista', 0, $len-1);
        var_dump($data);
        
    }

}
