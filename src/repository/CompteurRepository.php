<?php

namespace App\Repository;

use App\Core\Abstract\AbstractRepository;
use App\Entity\Compteur;
use App\Entity\Client;
use PDO;

class CompteurRepository extends AbstractRepository implements RepositoryInterface
{
    private static ?CompteurRepository $instance = null;

    private function __construct()
    {
        parent::__construct();
    }

    public static function getInstance(): static
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function find($numero): ?Compteur
    {
        $query = "SELECT c.id AS compteur_id, c.numero, cl.id AS client_id, cl.nom, cl.prenom
                  FROM compteurs c
                  JOIN clients cl ON c.client_id = cl.id
                  WHERE c.numero = ?";

        $stmt = $this->getConnection()->prepare($query);
        $stmt->execute([$numero]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $client = new Client(
                $row['client_id'],
                $row['nom'],
                $row['prenom'],
                $row['numero']
            );

            return new Compteur(
                $row['compteur_id'],
                $row['numero'],
                $client
            );
        }

        return null;
    }

    public function findById($id): ?Compteur
    {
        $query = "SELECT c.id AS compteur_id, c.numero, cl.id AS client_id, cl.nom, cl.prenom
                  FROM compteurs c
                  JOIN clients cl ON c.client_id = cl.id
                  WHERE c.id = ?";
        $stmt = $this->getConnection()->prepare($query);
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $client = new Client(
                $row['client_id'],
                $row['nom'],
                $row['prenom'],
                $row['numero']
            );
            return new Compteur(
                $row['compteur_id'],
                $row['numero'],
                $client
            );
        }
        return null;
    }

    public function save($compteur): bool
    {
        $query = "INSERT INTO compteurs (numero, client_id) VALUES (?, ?)";
        $params = [
            $compteur->getNumero(),
            $compteur->getClient()->getId()
        ];

        try {
            $this->execute($query, $params);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function update($compteur): bool
    {
        $query = "UPDATE compteurs SET numero = ?, client_id = ? WHERE id = ?";
        $params = [
            $compteur->getNumero(),
            $compteur->getClient()->getId(),
            $compteur->getId()
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
        $query = "DELETE FROM compteurs WHERE id = ?";
        $stmt = $this->getConnection()->prepare($query);
        return $stmt->execute([$id]);
    }
}
