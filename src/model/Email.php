<?php

namespace Src\Model;

use Src\Model\Database;
use PDO;

class Email extends Database{
    
    public static function createLead(array $data, PDO $pdo):bool
    {   
        if(empty($pdo)){
            $pdo = self::getConnection();
        }

        $sql = "INSERT INTO LEADS_EMAIL (name, email, contact, message) VALUES (:name, :email, :contact, :message)";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":name", $data['name'], PDO::PARAM_STR);
        $stmt->bindParam(":email", $data['email'], PDO::PARAM_STR);
        $stmt->bindParam(":contact", $data['contact'], PDO::PARAM_STR);
        $stmt->bindParam(":message", $data['message'], PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->rowCount() > 0;
    }   

}