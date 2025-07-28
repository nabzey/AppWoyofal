<?php

namespace App\Controller;

use App\Service\ClientService;

class ClientController
{
    private ClientService $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    // GET /clients?numero=XYZ
    public function findByNumero(string $numero): array
    {
        $client = $this->clientService->getClientByNumero($numero);

        if ($client) {
            return [
                'data' => $this->clientService->serializeClient($client),
                'statut' => 'success',
                'code' => 200,
                'message' => 'Client trouvé'
            ];
        } else {
            return [
                'data' => null,
                'statut' => 'error',
                'code' => 404,
                'message' => 'Numéro de client non reconnu'
            ];
        }
    }

    // GET /clients/{id}
    public function show(int $id): array
    {
        $client = $this->clientService->getClientById($id);

        if ($client) {
            return [
                'data' => $this->clientService->serializeClient($client),
                'statut' => 'success',
                'code' => 200,
                'message' => 'Client trouvé'
            ];
        } else {
            return [
                'data' => null,
                'statut' => 'error',
                'code' => 404,
                'message' => 'Aucun client trouvé avec cet ID'
            ];
        }
    }

    // POST /clients
    public function store(array $body): array
    {
        if (!isset($body['nom']) || !isset($body['prenom'])) {
            return [
                'data' => null,
                'statut' => 'error',
                'code' => 400,
                'message' => 'Champs obligatoires manquants : nom, prenom'
            ];
        }

        $client = $this->clientService->createClient($body);

        if ($client) {
            return [
                'data' => $this->clientService->serializeClient($client),
                'statut' => 'success',
                'code' => 201,
                'message' => 'Client créé avec succès'
            ];
        } else {
            return [
                'data' => null,
                'statut' => 'error',
                'code' => 500,
                'message' => 'Erreur lors de la création du client'
            ];
        }
    }

    // PUT /clients/{id}
    public function update(int $id, array $body): array
    {
        $updated = $this->clientService->updateClient($id, $body);

        if ($updated) {
            return [
                'data' => $this->clientService->serializeClient($updated),
                'statut' => 'success',
                'code' => 200,
                'message' => 'Client mis à jour avec succès'
            ];
        } else {
            return [
                'data' => null,
                'statut' => 'error',
                'code' => 404,
                'message' => 'Échec de mise à jour : client introuvable'
            ];
        }
    }

    // DELETE /clients/{id}
    public function delete(int $id): array
    {
        $success = $this->clientService->deleteClient($id);

        if ($success) {
            return [
                'data' => null,
                'statut' => 'success',
                'code' => 200,
                'message' => 'Client supprimé avec succès'
            ];
        } else {
            return [
                'data' => null,
                'statut' => 'error',
                'code' => 400,
                'message' => 'Échec lors de la suppression du client'
            ];
        }
    }

    // GET /client/compteur?numero=XYZ
    public function getCompteurByNumero(string $numero): array
    {
        // On utilise le CompteurRepository pour récupérer le compteur
        $compteurRepository = \App\Repository\CompteurRepository::getInstance();
        $compteur = $compteurRepository->find($numero);

        if ($compteur) {
            return [
                'data' => $compteur->toArray(),
                'statut' => 'success',
                'code' => 200,
                'message' => 'Compteur trouvé pour ce client'
            ];
        } else {
            return [
                'data' => null,
                'statut' => 'error',
                'code' => 404,
                'message' => 'Aucun compteur trouvé pour ce numéro'
            ];
        }
    }

    // GET /client
    public function all(): array
    {
        $clients = $this->clientService->getAllClients();
        $data = array_map(fn($c) => $this->clientService->serializeClient($c), $clients);
        return [
            'data' => $data,
            'statut' => 'success',
            'code' => 200,
            'message' => count($data) ? 'Liste des clients' : 'Aucun client trouvé'
        ];
    }
    
    // POST /clients/achat
    public function acheterCredit(array $body): array
{
    if (!isset($body['numero_compteur']) || !isset($body['montant'])) {
        return [
            'data' => null,
            'statut' => 'error',
            'code' => 400,
            'message' => 'Champs obligatoires manquants : numero_compteur, montant'
        ];
    }
    $numeroCompteur = $body['numero_compteur'];
    $montant = (float)$body['montant'];
    $result = $this->clientService->acheterCreditWoyofal($numeroCompteur, $montant);
    return $result;
}
}
