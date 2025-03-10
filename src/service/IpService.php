<?php

namespace Src\Service;

use Exception;
use PDOException;
use Src\Model\Ip;

class IpService{
    public static function create(array $data){
        try{
            $ip = Ip::create($data);

            if(!$ip){
                throw new Exception("Não foi possível cadastrar um novo ip: ");
            }

            return "Ip cadastrado com sucesso";
        }catch(PDOException $e){
            return['error' => "Ocorreu um erro inesperado no banco de dados ".$e->getMessage()];
        }catch(Exception $e){
            return ['error' => "Ocorreu um erro Inesperado: ".$e->getMessage()];
        }
    }

    public static function updatePolicy(array $data){
        try{
            $ip = Ip::updatePolicy($data);

            if(!$ip){
                throw new Exception("Não foi possível atualizar politica de privacidade");
            }

            return "Politica de privacidade atualizada com sucesso";
        }catch(PDOException $e){
            return['error' => "Ocorreu um erro inesperado no banco de dados ".$e->getMessage()];
        }catch(Exception $e){
            return ['error' => "Ocorreu um erro Inesperado: ".$e->getMessage()];
        }
    }
}