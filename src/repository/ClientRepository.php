<?php

namespace App\Repository;

use App\Core\Abstract\AbstractRepository;
use App\Entity\Client;
use PDO;

class ClientRepository extends AbstractRepository implements RepositoryInterface
{
    public function find($id): ?Client
    {
        $query = "SELECT * FROM client WHERE id = ?";
        $stmt = $this->getConnection()->prepare($query);
        $stmt->execute([$id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Client(
                $row['id'],
                $row['nom'],
                $row['prenom'],
                $row['numero_compteur'] ?? null
            );
        }

        return null;
    }

    public function findByCompteur($numero): ?Client
    {
        $query = "SELECT * FROM compteur WHERE numero_compteur = ?";
        $stmt = $this->getConnection()->prepare($query);
        $stmt->execute([$numero]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // On récupère le client lié au compteur
            $clientId = $row['client_id'];
            return $this->find($clientId);
        }

        return null;
    }

    public function findByNumero($numero): ?Client
    {
        $query = "SELECT * FROM compteur WHERE numero_compteur = ?";
        $stmt = $this->getConnection()->prepare($query);
        $stmt->execute([$numero]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // On récupère le client lié au compteur
            $clientId = $row['client_id'];
            return $this->find($clientId);
        }

        return null;
    }

    public function save($client): bool
    {
        $query = "INSERT INTO client (nom, prenom) VALUES (?, ?)";
        $params = [
            $client->getNom(),
            $client->getPrenom()
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
        $query = "DELETE FROM client WHERE id = ?";
        $stmt = $this->getConnection()->prepare($query);
        return $stmt->execute([$id]);
    }

    public function findAll(): array
    {
        $query = "SELECT * FROM client";
        $stmt = $this->getConnection()->query($query);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $clients = [];
        foreach ($rows as $row) {
            $clients[] = new Client(
                $row['id'],
                $row['nom'],
                $row['prenom'],
                $row['telephone'] ?? null,
                $row['adresse'] ?? null
            );
        }
        return $clients;
    }
}
