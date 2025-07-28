<?php

namespace App\Repository;

use App\Entity\Achat;
use App\Core\Abstract\AbstractRepository;
use PDO;

class AchatRepository extends AbstractRepository implements RepositoryInterface
{
    private static ?AchatRepository $instance = null;

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

    public function save($achat): bool
    {
        $query = "INSERT INTO achat (
            compteur_id, reference, code, nbre_kwt, date, tranche_id, prix_kw, client_id
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $params = [
            $achat->getCompteur() ? $achat->getCompteur()->getId() : null,
            $achat->getReference(),
            $achat->getCode(),
            $achat->getNbreKwt(),
            $achat->getDate(),
            $achat->getTranche() ? $achat->getTranche()->getId() : null,
            $achat->getPrixKw(),
            $achat->getClient() ? $achat->getClient()->getId() : null
        ];

        try {
            $this->execute($query, $params);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function find($id): ?Achat
    {
        $query = "SELECT * FROM achat WHERE id = ?";
        $stmt = $this->getConnection()->prepare($query);
        $stmt->execute([$id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return Achat::toObject($row);
        }

        return null;
    }

    public function delete($id): bool
    {
        $query = "DELETE FROM achat WHERE id = ?";
        $stmt = $this->getConnection()->prepare($query);
        return $stmt->execute([$id]);
    }
}
