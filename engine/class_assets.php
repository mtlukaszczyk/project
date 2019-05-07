<?php

namespace Engine;

class Assets {

    protected static $resources;
    protected static $resourcesDown;

    public static function init() {
        self::$resources = [];
        self::$resourcesDown = [];
    }

    public static function add($name, $type = null) {

        if (is_array($name)) {
            foreach ($name as $n) {
                self::_addOne($n);
            }
        } else {
            self::_addOne($name, 'up', $type);
        }
    }

    public static function addDown($name, $type = null) {

        if (is_array($name)) {
            foreach ($name as $n) {
                self::_addOne($n, 'down');
            }
        } else {
            self::_addOne($name, 'down', $type);
        }
    }

    private static function _addOne($name, $upDown = 'up', $type = null) {

        if (substr($name, 0, 2) == "//" || substr($name, 0, 4) == "http") {
            $external = true;
        } else {
            $external = false;
        }

        if (is_null($type)) {
            $type = last(explode('.', $name));
        }

        if ($upDown == 'up') {
            if (!in_array([$name, $type, $external], self::$resources)) {
                self::$resources[] = [$name, $type, $external];
            }
        } else {
            if (!in_array([$name, $type, $external], self::$resources)) {
                self::$resourcesDown[] = [$name, $type, $external];
            }
        }
    }

    public static function render($upDown = 'up') {

        echo PHP_EOL;

        if ($upDown == 'up') {
            $resourcesCopy = self::$resources;
        } else {
            $resourcesCopy = self::$resourcesDown;
        }

        foreach ($resourcesCopy as $resource) {

            $dir = $resource[1];

            if (self::isMinificableAsset($resource)) {
                $dir .= '/min';
            }

            $link = ($resource[2]) ? $resource[0] : base_url . $dir . '/' . $resource[0];

            if ($resource[1] == 'css') {
                echo '        <link href="' . $link . '" rel="stylesheet" type="text/css" />' . PHP_EOL;
            } else if ($resource[1] == 'js') {
                echo '        <script src="' . $link . '" type="text/javascript"></script>' . PHP_EOL;
            }
        }

        echo PHP_EOL;
    }

    private static function isMinificableAsset($resource) {

        if ($resource[1] == 'css' || $resource[1] == 'js') {
            if ('false') {
                if (strpos($resource[0], '/') === false) {
                    return true;
                }
            }
        }

        return false;
    }

    public static function globals() {

        self::add([
            'external/jquery.js',
            'external/bootstrap.min.css',
            'main.css',
            'external/bootstrap.min.js']
        );
    }

}
