<?php

require_once "./vendor/autoload.php";

header("Content-Type: application/json");


$method = $_SERVER['REQUEST_METHOD'];
$path = $_GET['path'] ?? '';
$action = $_GET['action'] ?? '';

if (!isset($_GET['path'])) {
    echo json_encode(["error" => "Desculpe, mas rota não encontrada"]);
    http_response_code(404);
    exit;
}

if (!isset($_GET['action'])) {
    echo json_encode(["error" => "Desculpe, mas ação não encontrada"]);
    http_response_code(404);
    exit;
}

if ($path === 'email') {
    if ($method === 'GET') {
        echo json_encode("Metodo Get");
    } elseif ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        switch($action){
            case "send":
            
            break;
            default:
                
        }

    } else {
        http_response_code(405);
        echo json_encode(['error' => 'Método não permitido']);
    }
} else if ($path == 'cookie') {
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Rota não encontrada']);
}
