<?php
namespace App\Controller;

use App\Service\CompteurService;

class CompteurController
{
    private CompteurService $compteurService;

    public function __construct(CompteurService $compteurService)
    {
        $this->compteurService = $compteurService;
    }

    public function getCompteurById(int $id): array
    {
        $compteur = $this->compteurService->getCompteurById($id);
        if ($compteur) {
            return [
                'data' => $compteur->toArray(),
                'statut' => 'success',
                'code' => 200,
                'message' => 'Compteur trouvé'
            ];
        }
        return [
            'data' => null,
            'statut' => 'error',
            'code' => 404,
            'message' => 'Compteur non trouvé'
        ];
    }

    public function getCompteurByNumero(string $numero): array
    {
        $compteur = $this->compteurService->getCompteurByNumero($numero);
        if ($compteur) {
            return [
                'data' => $compteur->toArray(),
                'statut' => 'success',
                'code' => 200,
                'message' => 'Compteur trouvé'
            ];
        }
        return [
            'data' => null,
            'statut' => 'error',
            'code' => 404,
            'message' => 'Compteur non trouvé'
        ];
    }

    public function createCompteur(array $data): array
    {
        $compteur = $this->compteurService->createCompteur($data);
        if ($compteur) {
            return [
                'data' => $compteur->toArray(),
                'statut' => 'success',
                'code' => 201,
                'message' => 'Compteur créé'
            ];
        }
        return [
            'data' => null,
            'statut' => 'error',
            'code' => 400,
            'message' => 'Erreur lors de la création du compteur'
        ];
    }

    public function updateCompteur(int $id, array $data): array
    {
        $compteur = $this->compteurService->updateCompteur($id, $data);
        if ($compteur) {
            return [
                'data' => $compteur->toArray(),
                'statut' => 'success',
                'code' => 200,
                'message' => 'Compteur mis à jour'
            ];
        }
        return [
            'data' => null,
            'statut' => 'error',
            'code' => 404,
            'message' => 'Compteur non trouvé ou erreur de mise à jour'
        ];
    }

    public function deleteCompteur(int $id): array
    {
        $success = $this->compteurService->deleteCompteur($id);
        if ($success) {
            return [
                'data' => null,
                'statut' => 'success',
                'code' => 200,
                'message' => 'Compteur supprimé'
            ];
        }
        return [
            'data' => null,
            'statut' => 'error',
            'code' => 404,
            'message' => 'Compteur non trouvé ou erreur de suppression'
        ];
    }
}
