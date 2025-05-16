<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FactureRepository::class)]
class Facture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $numFacture = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateFacture = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0)]
    private ?string $Montant = null;

    #[ORM\Column(length: 255)]
    private ?string $State = null;

    #[ORM\Column(length: 255)]
    private ?string $Commentaire = null;

    #[ORM\ManyToOne(inversedBy: 'Factures')]
    private ?Client $client = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumFacture(): ?string
    {
        return $this->numFacture;
    }

    public function setNumFacture(string $numFacture): static
    {
        $this->numFacture = $numFacture;

        return $this;
    }

    public function getDateFacture(): ?\DateTime
    {
        return $this->dateFacture;
    }

    public function setDateFacture(\DateTime $dateFacture): static
    {
        $this->dateFacture = $dateFacture;

        return $this;
    }

    public function getMontant(): ?string
    {
        return $this->Montant;
    }

    public function setMontant(string $Montant): static
    {
        $this->Montant = $Montant;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->State;
    }

    public function setState(string $State): static
    {
        $this->State = $State;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->Commentaire;
    }

    public function setCommentaire(string $Commentaire): static
    {
        $this->Commentaire = $Commentaire;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }
}
