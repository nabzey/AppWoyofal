<?php
namespace App\Entity;

use App\Core\Abstract\AbstractEntity;


class Achat extends AbstractEntity
{
    private $id;
    private $reference;
    private $code;
    private $nbreKwt;
    private $date;
    private $tranche;
    private $prixKw;
    private $client;
    private $compteur;

    public function __construct(
        $id = null,
        $reference = '',
        $code = '',
        $nbreKwt = 0,
        $date = '',
        $tranche = '',
        $prixKw = 0,
        $client = null,
        $compteur = null
    ) {
        $this->id = $id;
        $this->reference = $reference;
        $this->code = $code;
        $this->nbreKwt = $nbreKwt;
        $this->date = $date;
        $this->tranche = $tranche;
        $this->prixKw = $prixKw;
        $this->client = $client;
        $this->compteur = $compteur;
    }

    public static function toObject(array $data): static
    {
        return new static(
            $data['id'] ?? null,
            $data['reference'] ?? '',
            $data['code'] ?? '',
            $data['nbreKwt'] ?? 0,
            $data['date'] ?? '',
            $data['tranche'] ?? '',
            $data['prixKw'] ?? 0,
            $data['client'] ?? null,
            $data['compteur'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'code' => $this->code,
            'nbreKwt' => $this->nbreKwt,
            'date' => $this->date,
            'tranche' => $this->tranche,
            'prixKw' => $this->prixKw,
            'client' => is_object($this->client) ? $this->client->getNom() . ' ' . $this->client->getPrenom() : $this->client,
            'compteur' => is_object($this->compteur) ? $this->compteur->toArray()['numero'] : $this->compteur
        ];
    }
    
}
