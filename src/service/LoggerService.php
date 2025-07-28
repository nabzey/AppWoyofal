<?php

namespace App\Service;
use App\Core\Abstract\Singleton;
use App\Repository\ILoggerRepository;
use App\Service\ILoggerService;


class LoggerService extends Singleton implements ILoggerService
{
    private ILoggerRepository $loggerRepository;

    public function __construct(ILoggerRepository $loggerRepository)
    {
        $this->loggerRepository = $loggerRepository;
    }

    public function log(string $message): void
    {
        $this->loggerRepository->saveLog($message);
    }

}