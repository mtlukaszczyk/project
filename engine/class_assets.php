<?php

namespace Engine;

class assets {

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

    public static function dataTableExport() {
        self::add([
            'external/datatables/buttons/dataTables.buttons.min.js',
            'external/datatables/buttons/buttons.flash.min.js',
            'external/datatables/export/jszip.min.js',
            'external/datatables/export/pdfmake.min.js',
            'external/datatables/export/vfs_fonts.js',
            'external/datatables/buttons/buttons.html5.min.js',
            'external/datatables/buttons/buttons.print.min.js',
            'https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css'
        ]);
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

            $link = ($resource[2]) ? $resource[0] : base_url . $resource[1] . '/' . $resource[0];

            if ($resource[1] == 'css') {
                echo '        <link href="' . $link . '" rel="stylesheet" type="text/css" />' . PHP_EOL;
            } else if ($resource[1] == 'js') {
                echo '        <script src="' . $link . '" type="text/javascript"></script>' . PHP_EOL;
            }
        }

        echo PHP_EOL;
    }

    public static function globals() {

        self::add(
                [
                    'jquery.js',
                    'bootstrap.min.css',
                    'main.css'
                ]
        );
    }

}
