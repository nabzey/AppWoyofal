<?php
namespace App\Entity;

class Tranche
{
    private $id;
    private $libelle;
    private $prixKw;
    private $limiteKw;

    public function __construct($id = null, $libelle = '', $prixKw = 0, $limiteKw = 0)
    {
        $this->id = $id;
        $this->libelle = $libelle;
        $this->prixKw = $prixKw;
        $this->limiteKw = $limiteKw;
    }

    public static function toObject(array $data): static
    {
        return new static(
            $data['id'] ?? null,
            $data['libelle'] ?? '',
            $data['prixKw'] ?? 0,
            $data['limiteKw'] ?? 0
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'libelle' => $this->libelle,
            'prixKw' => $this->prixKw,
            'limiteKw' => $this->limiteKw
        ];
    }
}
