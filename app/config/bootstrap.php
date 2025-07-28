<?php

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Core\ServiceContainer;
use App\Core\Router;

// Initialisation du conteneur unique
// On utilise uniquement ServiceContainer comme singleton

global $container;
$container = ServiceContainer::fromYaml(__DIR__ . '/services.yml');

global $router;
$router = new Router();

// Ajout des routes
$clientController = $container->get('ClientController');
$router->addRoute('GET', '/client/compteur', function () use ($clientController) {
    $numero = $_GET['numero'] ?? '';
    return json_encode($clientController->getClientByCompteur($numero));
});
// Ajoute d'autres routes ici...
