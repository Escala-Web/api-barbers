<?php

namespace Src\Config;

class Config
{
    const DOMAIN = 'liderancabarbers.com.br';
    const HOST = 'liderancabarbers.com.br';

    const HOST_DB = 'mysql.liderancabarbers.com.br';
    // const HOST_DB = 'localhost';
    const DB_NAME = 'liderancabarbe';
    // const DB_NAME = 'liderancabarbers';
    const DB_USER = 'liderancabarbe';
    // const DB_USER = 'root';
    const DB_PASSWORD = 'senha';
    // const DB_PASSWORD = '';

    const USERNAME_EMAIL = "google@liderancabarbers.com.br";
    const PASSWORD_EMAIL = "senha";
    const MAIN_EMAIL = 'agendamento@liderancabarbers.com.br';


    public static function get($key)
    {
        $const = "self::" . strtoupper($key);
        if (defined($const)) {
            return constant($const);
        }
        return null;
    }
}
