<?php

namespace config;

use \mysqli;

class Db
{

    private static $conn = null;

    public function __construct()
    {
        static::$conn =  new mysqli('localhost', 'root', '', 'task_api');

        if (!self::$conn) {

            die(self::$conn->connect_error);
        }
    }
    public static function getConnection()
    {
        if (!self::$conn) {
            new self;
        }
        return self::$conn;
    }
}
