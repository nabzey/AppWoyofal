<?php

namespace App\Service;

use App\Entity\Compteur;
use App\Repository\CompteurRepository;

class CompteurService
{
    private CompteurRepository $compteurRepository;

    public function __construct(CompteurRepository $compteurRepository)
    {
        $this->compteurRepository = $compteurRepository;
    }

    public function createCompteur(array $data): ?Compteur
    {
        $compteur = Compteur::toObject($data);
        $success = $this->compteurRepository->save($compteur);
        return $success ? $compteur : null;
    }

    public function getCompteurById(int $id): ?Compteur
    {
        return $this->compteurRepository->findById($id);
    }

    public function getCompteurByNumero(string $numero): ?Compteur
    {
        return $this->compteurRepository->find($numero);
    }

    public function updateCompteur(int $id, array $data): ?Compteur
    {
        $existing = $this->compteurRepository->findById($id);
        if (!$existing) {
            return null;
        }

        $updated = Compteur::toObject($data);
        $updated->setId($id);
        $success = $this->compteurRepository->update($updated);
        return $success ? $updated : null;
    }

    public function deleteCompteur(int $id): bool
    {
        return $this->compteurRepository->delete($id);
    }

    public function getTrancheByCompteurNumero(string $numero): ?string
    {
        $compteur = $this->compteurRepository->find($numero);
        if (!$compteur) {
            return null;
        }

        return $compteur->getTranche(); 
    }

    public function logTransaction(Compteur $compteur, float $montant, string $type): void
    {
    }

    public function validateCompteurExternally(string $numero): bool
    {
        return true;
    }

    // 📦 Format JSON compatible pour API
    public function serializeCompteur(Compteur $compteur): array
    {
        return [
            'id' => $compteur->getId(),
            'numero' => $compteur->getNumero(),
            'client' => [
                'id' => $compteur->getClient()->getId(),
                'nom' => $compteur->getClient()->getNom(),
                'prenom' => $compteur->getClient()->getPrenom(),
                'numero' => $compteur->getClient()->getNumero()
            ],
            // Ajoute 'solde' et 'tranche' si ces attributs existent
        ];
    }
}
