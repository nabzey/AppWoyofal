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
        $query = "INSERT INTO achats (
            compteur, reference, code, date_achat,
            tranche, prix_kw, nbre_kwt, client_nom, client_prenom
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $params = [
            $achat->getCompteur(),
            $achat->getReference(),
            $achat->getCode(),
            $achat->getDateAchat(),
            $achat->getTranche(),
            $achat->getPrixKw(),
            $achat->getNbreKwt(),
            $achat->getClientNom(),
            $achat->getClientPrenom()
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
        $query = "SELECT * FROM achats WHERE id = ?";
        $stmt = $this->getConnection()->prepare($query);
        $stmt->execute([$id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Achat($row); // instanciation à adapter
        }

        return null;
    }

    public function delete($id): bool
    {
        $query = "DELETE FROM achats WHERE id = ?";
        $stmt = $this->getConnection()->prepare($query);
        return $stmt->execute([$id]);
    }
}
