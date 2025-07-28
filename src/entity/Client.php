<?php
namespace App\Entity;

class Client
{
    private int $id;
    private string $nom;
    private string $prenom;
    private ?string $telephone;
    private ?string $adresse;

    public function __construct(
        int $id = 0,
        string $nom = '',
        string $prenom = '',
        ?string $telephone = null,
        ?string $adresse = null
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->telephone = $telephone;
        $this->adresse = $adresse;
    }

    public static function toObject(array $data): static
    {
        return new static(
            $data['id'] ?? 0,
            $data['nom'] ?? '',
            $data['prenom'] ?? '',
            $data['telephone'] ?? null,
            $data['adresse'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'telephone' => $this->telephone,
            'adresse' => $this->adresse
        ];
    }

    // 👇 Les getters pour le Repository
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }
}
