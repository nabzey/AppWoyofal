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
            $data['prix_kw'] ?? 0,  // Note: prix_kw dans la BDD
            $data['limite_kw'] ?? 0  // Note: limite_kw dans la BDD
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

    // Getters
    public function getId()
    {
        return $this->id;
    }

    public function getLibelle()
    {
        return $this->libelle;
    }

    public function getPrixKw()
    {
        return $this->prixKw;
    }

    public function getLimiteKw()
    {
        return $this->limiteKw;
    }

    // Setters
    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setLibelle($libelle): void
    {
        $this->libelle = $libelle;
    }

    public function setPrixKw($prixKw): void
    {
        $this->prixKw = $prixKw;
    }

    public function setLimiteKw($limiteKw): void
    {
        $this->limiteKw = $limiteKw;
    }
}