<?php

namespace App\Service;

use App\Repository\LoggerRepository;

class LoggerService
{
    private LoggerRepository $loggerRepository;

    public function __construct(LoggerRepository $loggerRepository)
    {
        $this->loggerRepository = $loggerRepository;
    }

    public function log(string $message, array $context = []): void
    {
        $logData = [
            'message'    => $message,
            'type'       => $context['type'] ?? 'achat',
            'user_id'    => $context['user_id'] ?? null,
            'ip'         => $_SERVER['REMOTE_ADDR'] ?? '',
            'created_at' => date('Y-m-d H:i:s'),
            'statut'     => $context['statut'] ?? 'success',
            'reference'  => $context['reference'] ?? '',
            'compteur'   => $context['compteur'] ?? '',
            'code'       => $context['code'] ?? '',
            'tranche'    => $context['tranche'] ?? '',
            'prix'       => $context['prix'] ?? 0,
            'nbre_kwt'   => $context['nbre_kwt'] ?? 0
        ];

        $this->loggerRepository->insertLog($logData);
    }

    public function raw(string $message): void
    {
        $this->loggerRepository->saveLog($message);
    }
}
