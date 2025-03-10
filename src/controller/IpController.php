<?php

namespace Src\Controller;

use Exception;
use PDOException;
use Src\Http\Response;
use Src\Service\IpService;
use Src\Utils\Validator;

class IpController{

    public static function create(array $data){
        try{
            $fields = Validator::validate([
                "page" => $data['page'] ?? '',
                "loc" => $data['loc'] ?? '',
                "acao" => $data['acao'] ?? '',
            ]);

            $ipService = IpService::create($fields);

            if(isset($ipService['error'])){
                return Response::json([
                    "success" => false,
                    "message" => $ipService['error'],
                ], 400);
            }

            return Response::json([
                "success" => true,
                "message" => $ipService
            ]);
        }catch(Exception){
            return Response::json([
                "success" => false,
                "message" => "Ocorreu um erro inesperado",
            ], 400);
        }
    }

    public static function updatePolicy(array $data){
        try{

            $fields = Validator::validate([
                "page" => $data['page'] ?? '',
                "acao" => $data['acao'] ?? '',
            ]);

            $ipService = IpService::updatePolicy($fields);

            if(isset($ipService['error'])){
                return Response::json([
                    "success" => false,
                    "message" => $ipService['error'],
                ], 400);
            }

            return Response::json([
                "success" => true,
                "message" => $ipService
            ]);
        }catch(Exception){
            return Response::json([
                "success" => false,
                "message" => "Ocorreu um erro inesperado",
            ], 400);
        }
    }

}