<?php

class Session{

    private static $flash_prefix = "__flash__";

    public static function init(){
        session_start();
    }

    public static function set($key, $value){
        $_SESSION[$key] = $value;
    }

    public static function get($key){
        if(isset($_SESSION[$key])){
            return $_SESSION[$key];
        }
    }

    public static function unsetKey($key){
        unset($_SESSION[$key]);
    }

    private static function get_flash_key($key) {
        return self::$flash_prefix.$key;
    }

    public static function set_flash($key, $value){
        self::set(self::get_flash_key($key), $value);
    }

    public static function get_flash($key){
        $flashSession = self::get(self::get_flash_key($key));
        self::unsetKey(self::get_flash_key($key));
        return $flashSession;

    }

    public static function destroy(){
        unset($_SESSION);
        session_destroy();
	}

}
