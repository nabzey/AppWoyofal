<?php
namespace App\Service;

use App\Entity\Client;
use App\Repository\ClientRepository;
use App\Service\LoggerService;

class ClientService
{
    private ClientRepository $clientRepository;
    private LoggerService $loggerService;
    private \App\Service\AchatService $achatService;
    private \App\Service\CompteurService $compteurService;
    private \App\Service\TrancheService $trancheService;

    public function __construct(ClientRepository $clientRepository, LoggerService $loggerService, \App\Service\AchatService $achatService, \App\Service\CompteurService $compteurService, \App\Service\TrancheService $trancheService)
    {
        $this->clientRepository = $clientRepository;
        $this->loggerService = $loggerService;
        $this->achatService = $achatService;
        $this->compteurService = $compteurService;
        $this->trancheService = $trancheService;
    }

    public function createClient(array $data): Client
    {
        $client = new Client(
            $data['nom'] ?? '',
            $data['prenom'] ?? '',
            $data['telephone'] ?? '',
            $data['adresse'] ?? ''
        );

        $this->clientRepository->save($client);
        $this->loggerService->log("Création du client: " . $client->getNom());

        return $client;
    }

    public function getClientById(int $id): ?Client
    {
        $client = $this->clientRepository->find($id);
        if ($client) {
            $this->loggerService->log("Client récupéré par ID: $id");
        }

        return $client;
    }

    public function getClientByNumero(string $numero): ?Client
    {
        $client = $this->clientRepository->findByNumero($numero);
        if ($client) {
            $this->loggerService->log("Client récupéré par numéro: $numero");
        }

        return $client;
    }

    public function updateClient(int $id, array $data): ?Client
    {
        $existingClient = $this->clientRepository->find($id);
        if (!$existingClient) {
            return null;
        }

        $updatedClient = new Client(
            $data['nom'] ?? $existingClient->getNom(),
            $data['prenom'] ?? $existingClient->getPrenom(),
            $data['telephone'] ?? $existingClient->getTelephone(),
            $data['adresse'] ?? $existingClient->getAdresse()
        );
        $updatedClient->setId($id);

        $this->clientRepository->save($updatedClient);
        $this->loggerService->log("Client mis à jour: " . $updatedClient->getNom());

        return $updatedClient;
    }

    public function deleteClient(int $id): bool
    {
        $success = $this->clientRepository->delete($id);
        if ($success) {
            $this->loggerService->log("Client supprimé (ID: $id)");
        }

        return $success;
    }

    public function serializeClient(Client $client): array
    {
        return [
            'id' => $client->getId(),
            'nom' => $client->getNom(),
            'prenom' => $client->getPrenom(),
            'telephone' => $client->getTelephone(),
            'adresse' => $client->getAdresse(),
        ];
    }

    public function getAllClients(): array
    {
        return $this->clientRepository->findAll();
    }
    
    /**
     * Gère l'achat d'un crédit Woyofal pour un client via un numéro de compteur et un montant.
     * Retourne la structure attendue (success ou error).
     */
    public function acheterCreditWoyofal(string $numeroCompteur, float $montant): array
    {
        // Vérifier l'existence du compteur
        $compteur = $this->compteurService->getCompteurByNumero($numeroCompteur);
        if (!$compteur) {
            $this->loggerService->log("Achat échoué : numéro de compteur non trouvé ($numeroCompteur)");
            return [
                'data' => null,
                'statut' => 'error',
                'code' => 404,
                'message' => 'Le numéro de compteur non retrouvé'
            ];
        }
        // Générer l'achat via AchatService (qui gère tranches, code, etc.)
        $result = $this->achatService->genererAchat($numeroCompteur, $montant);
        // Journalisation déjà faite dans AchatService
        return $result;
    }
}
