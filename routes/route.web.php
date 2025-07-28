<?php
// Fichier de définition des routes web

global $container, $router;

$clientController = $container->get('ClientController');
$router->addRoute('GET', '/client/compteur', function () use ($clientController) {
    $numero = $_GET['numero'] ?? '';
    return json_encode($clientController->getClientByCompteur($numero));
});

// Ajoute ici d'autres routes et contrôleurs selon tes besoins
// Exemple :
// $compteurController = $container->get('CompteurController');
// $router->addRoute('GET', '/compteur/{id}', function ($id) use ($compteurController) {
//     return json_encode($compteurController->getCompteurById($id));
// });
