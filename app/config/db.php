<?php

// esiest setup is with Apache config:
// Apache: SetEnv DB_USERNAME test
// PHP: getenv("DB_USERNAME");
return array(
    "server" => "localhost",
    "username" => "test",
    "password" => "test",
    "database" => "test",
    "charset" => null, // Really? You need to ask?
);
