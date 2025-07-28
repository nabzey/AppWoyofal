<?php
namespace App\Service;

use App\Entity\Tranche;

class TrancheService
{
    public function createTranche(array $data): Tranche
    {
        $tranche = Tranche::toObject($data);
        // ... logique de création
        return $tranche;
    }

    public function getTrancheById($id): ?Tranche
    {
        // ... logique de récupération
        return null;
    }

    public function updateTranche($id, array $data): ?Tranche
    {
        // ... logique de mise à jour
        return null;
    }

    public function deleteTranche($id): bool
    {
        // ... logique de suppression
        return false;
    }
}
