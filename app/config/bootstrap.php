<?php

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Core\ServiceContainer;
use App\Core\Router;

// Initialisation du conteneur unique
global $container;
$container = ServiceContainer::fromYaml(__DIR__ . '/services.yml');

global $router;
$router = new Router();

// Ajout des routes
$clientController = $container->get('ClientController');

// Route GET /client/compteur?numero=XYZ
$router->addRoute('GET', '/client/compteur', function () use ($clientController) {
    $numero = $_GET['numero'] ?? '';
    $clientController->findByNumero($numero); // Méthode correcte
});

// Tu peux ajouter ici d'autres routes comme :
// $router->addRoute('POST', '/client', function () use ($clientController) { ... });

// Dispatch de la requête
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->dispatch($_SERVER['REQUEST_METHOD'], $uri);
