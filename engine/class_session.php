<?php
namespace Engine;

class session {

    public static function set($name, $val) {
        if (!isset($_SESSION[session_panel])) {
            $_SESSION[session_panel] = [];
        }

        $_SESSION[session_panel][$name] = $val;
    }

    public static function get($name) {
        if (isset($_SESSION[session_panel][$name])) {
            return $_SESSION[session_panel][$name];
        } else {
            return null;
        }
    }

    public static function check($name) {
        return isset($_SESSION[session_panel][$name]);
    }

    public static function remove($name) {
        unset($_SESSION[session_panel][$name]);
    }

    public static function getFull() {
        if (isset($_SESSION[session_panel])) {
            return $_SESSION[session_panel];
        } else {
            return null;
        }
    }
}