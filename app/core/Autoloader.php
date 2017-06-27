<?php

class Autoloader {

    public static function load($name) {
        // Do not allow / in $name, simplest sanitation I could think of
        $name = basename($name);
        // We'll mostly be loading core files, so check for those first
        if(file_exists(SYSTEMDIR . "core/$name.php")) {
            require SYSTEMDIR . "core/$name.php";
            // Next up, we'll probably be lauching a bunch of models
        } elseif(file_exists(SYSTEMDIR . "models/$name.php")) {
            require SYSTEMDIR . "models/$name.php";
            // Fetch some controllers
        } elseif(file_exists(SYSTEMDIR . "controllers/$name.php")) {
            require SYSTEMDIR . "controllers/$name.php";
            // With a little luck, none of theese
        } elseif(file_exists(SYSTEMDIR . "exceptions/$name.php")) {
            require SYSTEMDIR . "exceptions/$name.php";
        }
    }

}
