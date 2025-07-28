<?php
namespace App\Entity;

class Compteur
{
    private $id;
    private $numero;
    private $client;
    private $tranche;

    public function __construct($id = null, $numero = '', $client = null, $tranche = null)
    {
        $this->id = $id;
        $this->numero = $numero;
        $this->client = $client;
        $this->tranche = $tranche;
    }

    public static function toObject(array $data): static
    {
        return new static(
            $data['id'] ?? null,
            $data['numero'] ?? '',
            $data['client'] ?? null,
            $data['tranche'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'numero' => $this->numero,
            'client' => $this->client,
            'tranche' => $this->tranche
        ];
    }

    public function getId()
    {
        return $this->id;
    }
    public function getNumero()
    {
        return $this->numero;
    }
    public function getClient()
    {
        return $this->client;
    }
    public function getTranche()
    {
        return $this->tranche;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setNumero($numero)
    {
        $this->numero = $numero;
    }
    public function setClient($client)
    {
        $this->client = $client;
    }
    public function setTranche($tranche)
    {
        $this->tranche = $tranche;
    }
}
