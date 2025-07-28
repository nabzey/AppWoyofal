<?php
namespace App\Repository;

use App\Core\Abstract\AbstractRepository;
use App\Entity\Tranche;
use PDO;

class TrancheRepository extends AbstractRepository implements RepositoryInterface
{
    private static ?TrancheRepository $instance = null;

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

    public function find($id): ?Tranche
    {
        $query = "SELECT * FROM tranche WHERE id = ?";
        $stmt = $this->getConnection()->prepare($query);
        $stmt->execute([$id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return Tranche::toObject($row);
        }

        return null;
    }

    public function save($tranche): bool
    {
        $query = "INSERT INTO tranche (libelle, prix_kw, limite_kw) VALUES (?, ?, ?)";
        $params = [
            $tranche->getLibelle(),
            $tranche->getPrixKw(),
            $tranche->getLimiteKw()
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
        $query = "DELETE FROM tranche WHERE id = ?";
        $stmt = $this->getConnection()->prepare($query);
        return $stmt->execute([$id]);
    }

    public function findTranchePourMontant($montant): ?Tranche
    {
        // Récupérer toutes les tranches ordonnées par limite_kw
        $query = "SELECT * FROM tranche ORDER BY limite_kw ASC";
        $stmt = $this->getConnection()->query($query);
        $tranches = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($tranches as $trancheData) {
            $tranche = Tranche::toObject($trancheData);
            $limiteMax = $tranche->getLimiteKw() * $tranche->getPrixKw();
            
            if ($montant <= $limiteMax) {
                return $tranche;
            }
        }

        // Si aucune tranche trouvée, retourner la dernière (tranche la plus élevée)
        if (!empty($tranches)) {
            return Tranche::toObject(end($tranches));
        }

        return null;
    }

    public function findAll(): array
    {
        $query = "SELECT * FROM tranche ORDER BY limite_kw ASC";
        $stmt = $this->getConnection()->query($query);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $tranches = [];
        foreach ($rows as $row) {
            $tranches[] = Tranche::toObject($row);
        }
        
        return $tranches;
    }
}