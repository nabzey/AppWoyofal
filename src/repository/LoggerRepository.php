<?php

namespace App\Repository;

use App\Core\Abstract\AbstractRepository;
use PDO;
use PDOException;

class LoggerRepository extends AbstractRepository implements ILoggerRepository
{
    public function getTable(): string
    {
        return 'logs';
    }

    public function insertLog(array $data): void
    {
        $query = "INSERT INTO logs (message, type, user_id, ip, created_at, statut, reference, compteur, code, tranche, prix, nbre_kwt)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $params = [
            $data['message'] ?? '',
            $data['type'] ?? 'achat',
            $data['user_id'] ?? null,
            $data['ip'] ?? '',
            $data['created_at'] ?? date('Y-m-d H:i:s'),
            $data['statut'] ?? 'success',
            $data['reference'] ?? '',
            $data['compteur'] ?? '',
            $data['code'] ?? '',
            $data['tranche'] ?? '',
            $data['prix'] ?? 0,
            $data['nbre_kwt'] ?? 0
        ];

        $this->execute($query, $params);
    }

    /**
     * Récupère les logs bruts pour audit, analyse ou supervision.
     */
    public function getRequestLogs(int $limit = 100, int $offset = 0): array
    {
        $query = "SELECT * FROM logs ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $stmt = $this->getConnection()->prepare($query);
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // brut → tableau
    }

    public function saveLog(string $message): void
    {
        // Ici, tu peux ajouter la logique pour sauvegarder le log, par exemple dans un fichier ou une base de données
        // Exemple simple :
        file_put_contents(__DIR__ . '/../../logs/app.log', date('Y-m-d H:i:s') . ' - ' . $message . PHP_EOL, FILE_APPEND);
    }
}
