<?php

namespace App\Core\Abstract;

use PDO;
use PDOException;

abstract class AbstractRepository extends Singleton
{
    protected PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    protected function getConnection(): PDO
    {
        return $this->pdo;
    }

    protected function execute(string $query, array $params = []): void
    {
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
        } catch (PDOException $e) {
            throw new \Exception("Erreur lors de l'exécution de la requête : " . $e->getMessage());
        }
    }
}
