<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/config/bootstrap.php';

// Le bootstrap initialise le conteneur et le routeur
// Ici, on récupère le routeur et on dispatch la requête

global $router;

$method = $_SERVER['REQUEST_METHOD'];
$path = strtok($_SERVER['REQUEST_URI'], '?');
$response = $router->dispatch($method, $path);
header('Content-Type: application/json');
echo is_string($response) ? $response : json_encode($response);

