<?php

namespace Src\Config;

class Config
{
    const DOMAIN = 'liderancabarbers.com.br';
    const HOST = 'escalaweb.com.br';

    const HOST_DB = 'localhost';
    const DB_NAME = 'liderancabarbers';
    const DB_USER = 'root';
    const DB_PASSWORD = '';

    const USERNAME_EMAIL = "teste@escalaweb.com.br";
    const PASSWORD_EMAIL = "Escalaweb$17";
    const MAIN_EMAIL = 'teste@escalaweb.com.br';


    public static function get($key)
    {
        $const = "self::" . strtoupper($key);
        if (defined($const)) {
            return constant($const);
        }
        return null;
    }
}