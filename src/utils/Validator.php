<?php

namespace Src\Utils;

use Exception;

class Validator{
    public static function validate(array $fields)
    {
        $errors = [];

        foreach ($fields as $field => $value) {
            if (is_string($value) && trim($value) === "") {
                $errors[] = $field;
            }
        }

        if (!empty($errors)) {

            $qtdErrors = count($errors);

            if ($qtdErrors > 1) {
                $message = "Os campos [" . implode(", ", $errors) . "] são obrigatórios";
            } else {
                $message = "O campo [" . implode(", ", $errors) . "] é obrigatório";
            }

            throw new Exception($message);
        }

        return $fields;
    }
}