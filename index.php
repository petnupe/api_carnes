<?php

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

$request = json_decode(file_get_contents("php://input"), true);

if ($path === '/carne' && $_SERVER['REQUEST_METHOD'] === 'POST') {
   include 'src/carne.php';
} else if ($path === '/carne' && $_SERVER['REQUEST_METHOD'] === 'GET') {
   include 'src/consultar.php';
} else {
   http_response_code(404);
   echo json_encode(["error" => "Recurso não encontrado."]);
}
