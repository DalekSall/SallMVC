<?php

class Config {

    private static $loaded = array();
    private static $db;

    public static function get($type) {
        if(!array_key_exists($type,self::$loaded)) {
            self::$loaded[$type] = require(SYSTEMDIR . "/config/$type.php");
        }
        return self::$loaded[$type];
    }

    public static function db() {
        if(is_null(self::$db)) {
            $dbConfig = self::get('db');
            self::$db = new PDO("mysql:host={$dbConfig['server']};dbname={$dbConfig['database']};charset=utf8", $dbConfig['username'], $dbConfig['password']);
        }
        $selectQuery = self::$db->prepare("SET NAMES utf8");
        $success = $selectQuery->execute();

        return self::$db;
    }

}
