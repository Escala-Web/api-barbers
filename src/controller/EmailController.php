<?php

namespace Src\Controller;

use Src\Http\Response;
use Src\Utils\Validator;
use Exception;
use Src\Service\EmailService;

class EmailController{
    public static function send($data){
        try{

            $fields = Validator::validate([
                "name" => $data['name'] ?? '',
                "email" => $data['email'] ?? '',
                "contact" => $data['contact'] ?? '',
                "message" => $data['message'] ?? ''
            ]);

            $emailService = EmailService::sendMail($fields);

            if(isset($emailService['error'])){
                return Response::json([
                    "status" => false,
                    "message" => $emailService['error']
                ], 400);
            }

            return Response::json([
                "status" => true,
                "message" => $emailService
            ]);

        }catch(Exception $e){   
            Response::json([
                "success" => false,
                "message" => $e->getMessage()
            ], 400);
        }
    }
}