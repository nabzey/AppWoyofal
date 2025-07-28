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

    // GET /clients/{id}
    public function show(int $id): void
    {
        header('Content-Type: application/json');
        $client = $this->clientService->getClientById($id);

        if ($client) {
            echo json_encode([
                'data' => $this->clientService->serializeClient($client),
                'statut' => 'success',
                'code' => 200,
                'message' => 'Client trouvé'
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                'data' => null,
                'statut' => 'error',
                'code' => 404,
                'message' => 'Aucun client trouvé avec cet ID'
            ]);
        }
    }

    // GET /clients?numero=XYZ
    public function findByNumero(string $numero): void
    {
        header('Content-Type: application/json');
        $client = $this->clientService->getClientByNumero($numero);

        if ($client) {
            echo json_encode([
                'data' => $this->clientService->serializeClient($client),
                'statut' => 'success',
                'code' => 200,
                'message' => 'Client trouvé'
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                'data' => null,
                'statut' => 'error',
                'code' => 404,
                'message' => 'Numéro de client non reconnu'
            ]);
        }
    }

    // POST /clients
    public function store(): void
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'data' => null,
                'statut' => 'error',
                'code' => 405,
                'message' => 'Méthode non autorisée'
            ]);
            return;
        }

        $body = json_decode(file_get_contents('php://input'), true);

        if (!isset($body['nom']) || !isset($body['prenom'])) {
            http_response_code(400);
            echo json_encode([
                'data' => null,
                'statut' => 'error',
                'code' => 400,
                'message' => 'Champs obligatoires manquants : nom, prenom'
            ]);
            return;
        }

        $client = $this->clientService->createClient($body);

        if ($client) {
            http_response_code(201);
            echo json_encode([
                'data' => $this->clientService->serializeClient($client),
                'statut' => 'success',
                'code' => 201,
                'message' => 'Client créé avec succès'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'data' => null,
                'statut' => 'error',
                'code' => 500,
                'message' => 'Erreur lors de la création du client'
            ]);
        }
    }

    // PUT /clients/{id}
    public function update(int $id): void
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            http_response_code(405);
            echo json_encode([
                'data' => null,
                'statut' => 'error',
                'code' => 405,
                'message' => 'Méthode non autorisée'
            ]);
            return;
        }

        $body = json_decode(file_get_contents('php://input'), true);

        $updated = $this->clientService->updateClient($id, $body);

        if ($updated) {
            echo json_encode([
                'data' => $this->clientService->serializeClient($updated),
                'statut' => 'success',
                'code' => 200,
                'message' => 'Client mis à jour avec succès'
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                'data' => null,
                'statut' => 'error',
                'code' => 404,
                'message' => 'Échec de mise à jour : client introuvable'
            ]);
        }
    }

    // DELETE /clients/{id}
    public function delete(int $id): void
    {
        header('Content-Type: application/json');
        $success = $this->clientService->deleteClient($id);

        if ($success) {
            echo json_encode([
                'data' => null,
                'statut' => 'success',
                'code' => 200,
                'message' => 'Client supprimé avec succès'
            ]);
        } else {
            http_response_code(400);
            echo json_encode([
                'data' => null,
                'statut' => 'error',
                'code' => 400,
                'message' => 'Échec lors de la suppression du client'
            ]);
        }
    }
}
