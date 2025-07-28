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

    // Nettoyage des tables pour éviter les doublons
    $pdo->exec('TRUNCATE TABLE achat RESTART IDENTITY CASCADE');
    $pdo->exec('TRUNCATE TABLE compteur RESTART IDENTITY CASCADE');
    $pdo->exec('TRUNCATE TABLE tranche RESTART IDENTITY CASCADE');
    $pdo->exec('TRUNCATE TABLE client RESTART IDENTITY CASCADE');

    // Données de test
    $client = [
        ['nom' => 'Niang', 'prenom' => 'Die', 'telephone' => '771234567', 'adresse' => 'Dakar'],
        ['nom' => 'Diop', 'prenom' => 'Aminata', 'telephone' => '778765432', 'adresse' => 'Saint-Louis'],
    ];
    foreach ($client as $c) {
        $pdo->prepare("INSERT INTO client (nom, prenom) VALUES (:nom, :prenom)")
            ->execute(['nom' => $c['nom'], 'prenom' => $c['prenom']]);
    }

    $compteur= [
        ['numero_compteur' => 'CPT123456', 'client_id' => 1],
        ['numero_compteur' => 'CPT654321', 'client_id' => 2],
    ];
    foreach ($compteur as $c) {
        $pdo->prepare("INSERT INTO compteur (numero_compteur, client_id) VALUES (:numero_compteur, :client_id)")
            ->execute($c);
    }

    $tranche = [
        ['libelle' => 'Tranche 1', 'prix_kw' => 98, 'limite_kw' => 100],
        ['libelle' => 'Tranche 2', 'prix_kw' => 120, 'limite_kw' => 200],
        ['libelle' => 'Tranche 3', 'prix_kw' => 150, 'limite_kw' => 999999],
    ];
    foreach ($tranche as $t) {
        $pdo->prepare("INSERT INTO tranche (libelle, prix_kw, limite_kw) VALUES (:libelle, :prix_kw, :limite_kw)")
            ->execute($t);
    }

    $achat = [
        [
            'compteur_id' => 1,
            'reference' => 'WOY-20250727-8007',
            'code_recharge' => '4851-2772-3511-5312',
            'nombre_kwh' => 1,
            'tranche_id' => 1,
            'prix_kw' => 98,
            'client_id' => 1
        ]
    ];
    foreach ($achat as $a) {
        $pdo->prepare("INSERT INTO achat (compteur_id, reference, code_recharge, nombre_kwh, tranche_id, prix_kw, client_id) VALUES (:compteur_id, :reference, :code_recharge, :nombre_kwh, :tranche_id, :prix_kw, :client_id)")
            ->execute($a);
    }

    echo "\nDonnées de test insérées avec succès!\n";

} catch (PDOException $e) {
    echo "Erreur de base de données: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
    exit(1);
}
