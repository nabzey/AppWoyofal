<?php
namespace App\Service;

use App\Entity\Achat;
use App\Repository\CompteurRepository;
use App\Repository\TrancheRepository;
use App\Repository\ClientRepository;
use App\Service\LoggerService;

class AchatService
{
    private LoggerService $loggerService;
    private CompteurRepository $compteurRepository;
    private TrancheRepository $trancheRepository;
    private ClientRepository $clientRepository;

    public function __construct(
        CompteurRepository $compteurRepository,
        TrancheRepository $trancheRepository,
        ClientRepository $clientRepository,
        LoggerService $loggerService
    ) {
        $this->compteurRepository = $compteurRepository;
        $this->trancheRepository = $trancheRepository;
        $this->clientRepository = $clientRepository;
        $this->loggerService = $loggerService;
    }

    public function genererAchat(string $numeroCompteur, float $montant): array
    {
        $compteur = $this->compteurRepository->find($numeroCompteur);
        if (!$compteur) {
            $this->loggerService->log("Achat échoué : numéro de compteur non trouvé ($numeroCompteur)");
            return [
                'data' => null,
                'statut' => 'error',
                'code' => 404,
                'message' => 'Le numéro de compteur non retrouvé'
            ];
        }
        $client = $compteur->toArray()['client'] ?? null;
        $tranche = $this->trancheRepository->findTranchePourMontant($montant);
        $prixKw = $tranche ? $tranche->toArray()['prixKw'] : 0;
        $nbreKwt = $prixKw ? floor($montant / $prixKw) : 0;
        $achat = new \App\Entity\Achat(
            null,
            uniqid('ref_'),
            bin2hex(random_bytes(4)),
            $nbreKwt,
            date('Y-m-d H:i:s'),
            $tranche ? $tranche->toArray()['libelle'] : '',
            $prixKw,
            $client,
            $compteur
        );
        $this->loggerService->log("Achat effectué : " . json_encode($achat->toArray()));
        return [
            'data' => $achat->toArray(),
            'statut' => 'success',
            'code' => 200,
            'message' => 'Achat effectué avec succès'
        ];
    }
}
