<?php
// Fichier de définition des routes web

global $container, $router;

$clientController = $container->get('ClientController');
$router->addRoute('GET', '/client/compteur', function () use ($clientController) {
    $numero = $_GET['numero'] ?? '';
    return json_encode($clientController->getCompteurByNumero($numero));
});

$router->addRoute('GET', '/client', function () use ($clientController) {
    return json_encode($clientController->all());
});

