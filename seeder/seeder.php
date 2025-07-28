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
        ['tranche_num' => 1, 'seuil_min' => 0, 'seuil_max' => 100, 'prix_unitaire' => 98],
        ['tranche_num' => 2, 'seuil_min' => 101, 'seuil_max' => 200, 'prix_unitaire' => 120],
        ['tranche_num' => 3, 'seuil_min' => 201, 'seuil_max' => 999999, 'prix_unitaire' => 150],
    ];
    foreach ($tranche as $t) {
        $pdo->prepare("INSERT INTO tranche (tranche_num, seuil_min, seuil_max, prix_unitaire) VALUES (:tranche_num, :seuil_min, :seuil_max, :prix_unitaire)")
            ->execute($t);
    }

    $achat = [
        [
            'compteur_id' => 1,
            'reference' => 'WOY-20250727-8007',
            'code_recharge' => '4851-2772-3511-5312',
            'nombre_kwh' => 1,
            'tranche' => 1,
            'prix_unitaire' => 98,
            'montant_total' => 98,
            'client_nom' => 'Niang',
            'client_prenom' => 'Die'
        ]
    ];
    foreach ($achat as $a) {
        $pdo->prepare("INSERT INTO achat (compteur_id, reference, code_recharge, nombre_kwh, tranche, prix_unitaire, montant_total, client_nom, client_prenom) VALUES (:compteur_id, :reference, :code_recharge, :nombre_kwh, :tranche, :prix_unitaire, :montant_total, :client_nom, :client_prenom)")
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
