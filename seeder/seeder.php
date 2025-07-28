<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

try {
    $host = $_ENV['DB_HOST'] ?? 'localhost';
    $port = $_ENV['DB_PORT'] ?? '5432';
    $dbname = $_ENV['DB_NAME'] ?? 'app_woyofal';
    $username = $_ENV['DB_USER'] ?? 'pguserWoyofal';
    $password = $_ENV['DB_PASSWORD'] ?? 'pgpassword';

    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    echo "Connexion à la base de données réussie!\n";

    // Données de test
    $clients = [
        ['nom' => 'Niang', 'prenom' => 'Die', 'telephone' => '771234567', 'adresse' => 'Dakar'],
        ['nom' => 'Diop', 'prenom' => 'Aminata', 'telephone' => '778765432', 'adresse' => 'Saint-Louis'],
    ];
    foreach ($clients as $client) {
        $pdo->prepare("INSERT INTO clients (nom, prenom, telephone, adresse) VALUES (:nom, :prenom, :telephone, :adresse)")
            ->execute($client);
    }

    $compteurs = [
        ['numero' => 'CPT123456', 'client_id' => 1],
        ['numero' => 'CPT654321', 'client_id' => 2],
    ];
    foreach ($compteurs as $compteur) {
        $pdo->prepare("INSERT INTO compteurs (numero, client_id) VALUES (:numero, :client_id)")
            ->execute($compteur);
    }

    $tranches = [
        ['libelle' => 'Tranche 1', 'prix_kw' => 98, 'limite_kw' => 100],
        ['libelle' => 'Tranche 2', 'prix_kw' => 120, 'limite_kw' => 200],
        ['libelle' => 'Tranche 3', 'prix_kw' => 150, 'limite_kw' => 999999],
    ];
    foreach ($tranches as $tranche) {
        $pdo->prepare("INSERT INTO tranches (libelle, prix_kw, limite_kw) VALUES (:libelle, :prix_kw, :limite_kw)")
            ->execute($tranche);
    }

    $achats = [
        [
            'reference' => 'WOY-20250727-8007',
            'code' => '4851-2772-3511-5312',
            'nbre_kwt' => 1.02,
            'date' => '2025-07-27 20:57:38',
            'tranche_id' => 1,
            'prix_kw' => 98,
            'client_id' => 1,
            'compteur_id' => 1
        ]
    ];
    foreach ($achats as $achat) {
        $pdo->prepare("INSERT INTO achats (reference, code, nbre_kwt, date, tranche_id, prix_kw, client_id, compteur_id) VALUES (:reference, :code, :nbre_kwt, :date, :tranche_id, :prix_kw, :client_id, :compteur_id)")
            ->execute($achat);
    }

    echo "\nDonnées de test insérées avec succès!\n";

} catch (PDOException $e) {
    echo "Erreur de base de données: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
    exit(1);
}
