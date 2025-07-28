<?php

namespace App\Repository;

use App\Core\Abstract\AbstractRepository;
use App\Entity\Client;
use PDO;

class ClientRepository extends AbstractRepository implements RepositoryInterface
{
    public function find($id): ?Client
    {
        $query = "SELECT * FROM clients WHERE id = ?";
        $stmt = $this->getConnection()->prepare($query);
        $stmt->execute([$id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Client(
                $row['id'],
                $row['nom'],
                $row['prenom'],
                $row['numero_compteur']
            );
        }

        return null;
    }

    public function findByCompteur($numero): ?Client
    {
        $query = "SELECT * FROM clients WHERE numero_compteur = ?";
        $stmt = $this->getConnection()->prepare($query);
        $stmt->execute([$numero]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Client(
                $row['id'],
                $row['nom'],
                $row['prenom'],
                $row['numero_compteur']
            );
        }

        return null;
    }

    public function findByNumero($numero): ?Client
    {
        $query = "SELECT * FROM clients WHERE numero_compteur = ?";
        $stmt = $this->getConnection()->prepare($query);
        $stmt->execute([$numero]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Client(
                $row['id'],
                $row['nom'],
                $row['prenom'],
                $row['numero_compteur']
            );
        }

        return null;
    }

    public function save($client): bool
    {
        $query = "INSERT INTO clients (nom, prenom, numero_compteur) VALUES (?, ?, ?)";
        $params = [
            $client->getNom(),
            $client->getPrenom(),
            $client->getNumeroCompteur()
        ];

        try {
            $this->execute($query, $params);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function delete($id): bool
    {
        $query = "DELETE FROM clients WHERE id = ?";
        $stmt = $this->getConnection()->prepare($query);
        return $stmt->execute([$id]);
    }
}
