<?php
namespace App\Repository;

use App\Entity\Tranche;

class TrancheRepository implements RepositoryInterface
{
    private static ?TrancheRepository $instance = null;

    private function __construct() {}

    public static function getInstance(): static
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function find($id): ?Tranche
    {
        // ... logique de récupération
        return null;
    }

    public function save($entity): bool
    {
        // ... logique de sauvegarde
        return true;
    }

    public function delete($id): bool
    {
        // ... logique de suppression
        return false;
    }

    public function findTranchePourMontant($montant)
    {
        // Exemple de logique de tranche progressive
        $tranches = [
            ['libelle' => 'Tranche 1', 'prixKw' => 98, 'limiteKw' => 100],
            ['libelle' => 'Tranche 2', 'prixKw' => 120, 'limiteKw' => 200],
            ['libelle' => 'Tranche 3', 'prixKw' => 150, 'limiteKw' => 999999],
        ];
        foreach ($tranches as $tranche) {
            if ($montant <= $tranche['limiteKw'] * $tranche['prixKw']) {
                return new \App\Entity\Tranche(null, $tranche['libelle'], $tranche['prixKw'], $tranche['limiteKw']);
            }
        }
        return null;
    }
}
