<?php

namespace Engine;
use Illuminate\Database\Capsule\Manager as DB;

class localizator {

    private static $data;
    private static $dataDef;
    private static $data_js;

    /**
     * Initialize class
     */
    public static function init() {
        $field = (!empty(LANG_SYMBOL)) ? 'content_' . LANG_SYMBOL : 'content_de';

        try {
            
            $data = DB::table('translations')
                    ->get(['name', $field, 'js']);
            
        } catch (Exception $e) {
            
            $field = 'content_de';
            
            $data = DB::table('translations')
                ->get(['name', $field, 'js']);
        }
        
        if ($field != 'content_de') {
            $dataDef = DB::table('translations')
                ->get(['name', 'content_de', 'js']);
        } else {
            $dataDef = $data;
        }

        foreach($data as $d) {
            if ($d->js == '1') {
                self::$data_js[$d->name] = $d->{$field};
            }
        }

        self::$data = \App\Helper\Arrays::transpone(\App\Helper\Arrays::getArray($data), 'name', $field);
        self::$dataDef = \App\Helper\Arrays::transpone(\App\Helper\Arrays::getArray($dataDef), 'name', 'content_de');
    }

    /**
     * Returns localization string
     * @param string $name message id
     * @param array $params additional parameters to inject into string by vsprintf function
     * @return string data
     */
    public static function get($name, $params = []) {
        if (!empty(trim(self::$data[$name]))) {
            if (empty($params)) {
                return self::$data[$name];
            } else {
                return vsprintf(self::$data[$name], $params);
            }
        } else {
            self::reportMissingTranslation($name);
            if (empty($params)) {
                return self::$dataDef[$name];
            } else {
                return vsprintf(self::$dataDef[$name], $params);
            }
        }
    }

    /**
     * Returns array with translations used in JS code.
     * @return string array in JSON
     */
    public static function getDataForJs() {
        return json_encode(self::$data_js);
    }

    /**
     * Saves info about missing translation in system log table
     * @param string $name message id
     */
    private static function reportMissingTranslation($name) {

        DB::table('log_error')
                ->insert([
                    'msg' => 'Missing translation: ' . $name,
                    'dt' => DB::raw('NOW()'),
                    'session' => json_encode(session::getFull()),
                    'ip' => getenv('REMOTE_ADDR')
        ]);
    }

}

/**
 * Alias for Localizatior::get
 */
function __($name, $params = []) {
    return Localizator::get($name, $params);
}