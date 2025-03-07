<?php

namespace Src\Model;

use PDO;
use PDOException;
use Src\Config\Config;

abstract class Database {
    private static $connection = null;

    protected static function connect() {
        if (self::$connection === null) {
            try {
                $dsn = 'mysql:host=' . Config::get("HOST_DB") . ';dbname=' . Config::get("DB_NAME");
                $username = Config::get("DB_USER");
                $password = Config::get("DB_PASSWORD");
                self::$connection = new PDO($dsn, $username, $password);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die('Connection failed: ' . $e->getMessage());
            }
        }
        return self::$connection;
    }

    public static function getConnection() {
        return self::connect();
    }
}