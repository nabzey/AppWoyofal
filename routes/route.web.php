<?php
// Fichier de définition des routes web

global $container, $router;

$clientController = $container->get('ClientController');

// Route pour récupérer un compteur par numéro (GET)
$router->addRoute('GET', '/client/compteur', function () use ($clientController) {
    $numero = $_GET['numero'] ?? '';
    if (empty($numero)) {
        return json_encode([
            'data' => null,
            'statut' => 'error',
            'code' => 400,
            'message' => 'Paramètre numero manquant'
        ]);
    }
    return json_encode($clientController->getCompteurByNumero($numero));
});

// Route pour lister tous les clients (GET)
$router->addRoute('GET', '/client', function () use ($clientController) {
    return json_encode($clientController->all());
});

// ✅ Route pour acheter du crédit (POST) - Méthode correcte
$router->addRoute('POST', '/client/achat', function () use ($clientController) {
    $body = json_decode(file_get_contents('php://input'), true);
    
    if ($body === null) {
        return json_encode([
            'data' => null,
            'statut' => 'error',
            'code' => 400,
            'message' => 'Corps de requête JSON invalide'
        ]);
    }
    
    return json_encode($clientController->acheterCredit($body));
});

// ✅ AJOUT: Route GET pour /client/achat (pour les tests depuis le navigateur)
$router->addRoute('GET', '/client/achat', function () use ($clientController) {
    return json_encode([
        'data' => null,
        'statut' => 'info',
        'code' => 200,
        'message' => 'Utilisez POST pour effectuer un achat. Paramètres requis: {"numero_compteur": "CPT123456", "montant": 500}',
        'exemple_curl' => 'curl -X POST ' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/client/achat -H "Content-Type: application/json" -d \'{"numero_compteur":"CPT123456","montant":500}\''
    ]);
});

// Route de test pour vérifier que le routeur fonctionne
$router->addRoute('GET', '/test', function () {
    return json_encode([
        'data' => 'Route de test',
        'statut' => 'success',
        'code' => 200,
        'message' => 'Le routeur fonctionne correctement',
        'routes_disponibles' => [
            'GET /client' => 'Liste tous les clients',
            'GET /client/compteur?numero=XXX' => 'Récupère un compteur par numéro',
            'GET /client/achat' => 'Informations sur l\'API d\'achat',
            'POST /client/achat' => 'Effectue un achat de crédit',
            'GET /test' => 'Route de test'
        ]
    ]);
});

// ✅ AJOUT: Route pour debug - voir toutes les routes enregistrées
$router->addRoute('GET', '/debug/routes', function () use ($router) {
    return json_encode([
        'data' => $router->getRoutes(),
        'statut' => 'success',
        'code' => 200,
        'message' => 'Liste de toutes les routes enregistrées'
    ]);
});