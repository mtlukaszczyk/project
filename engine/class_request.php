<?php
namespace Engine;

class request {

    public static $_type;
    public static $_controller;
    public static $_action;
    public static $_params = [];
    public static $_requestParams = [];
    public static $_isAjax = false;
    public static $_customRoute = false;

    public static function decode() {

        self::$_type = $_SERVER['REQUEST_METHOD'];
        $params = [];
        $req_params = [];

        self::$_isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

        $controller = "";
        $action = "";
        $get = [];

        if (isset($_REQUEST['custom'])) {
            if ($_REQUEST['custom'] == 1) {
                self::$_customRoute = true;
                $controller = $_REQUEST['controller'] ?? MAIN_CONTROLLER;
                $action = $_REQUEST['action'] ?? "index";
            }

            $get_params = explode('/', ($_REQUEST['params'] ?? ""));
            $get = array_merge([$_REQUEST['controller'], $_REQUEST['action']], $get_params);
        }

        if (isset($_REQUEST['params']) && self::$_customRoute === false) {

            $get = explode('/', ($_REQUEST['params'] ?? ""));

            $controller = $get[0] ?? MAIN_CONTROLLER;
            $action = $get[1] ?? "index";
        }

        if (self::$_isAjax) {

            $controller = fixName(ucfirst($controller));

            $f = new \ReflectionMethod('\App\Controller\\' . $controller, fixName($action));
            $params = [];

            if (self::$_type == 'DELETE' || self::$_type == 'PUT') {
                parse_str(file_get_contents("php://input"), $req_params);
            } else {
                $req_params = $_REQUEST;
            }

            foreach ($f->getParameters() as $param) {
                $params[$param->name] = $req_params[$param->name] ?? null;
            }
        } else {

            $controller = $controller !== "" ? $controller : MAIN_CONTROLLER;
            $action = $action !== "" ? $action : "index";

            $params = [];

            for ($i = 2, $cGet = count($get); $i < $cGet; $i++) {
                $params[] = $get[$i];
            }
        }

        self::$_controller = $controller;
        self::$_action = $action;
        self::$_params = $params;
        self::$_requestParams = $req_params;
    }

}
