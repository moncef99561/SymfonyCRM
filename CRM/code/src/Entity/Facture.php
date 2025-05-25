<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
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

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: "Le numéro de facture est obligatoire.")]
    #[Assert\Length(max: 255)]
    private ?string $numFacture = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotNull(message: "La date de la facture est obligatoire.")]
    #[Assert\Type(\DateTimeInterface::class)]
    #[Assert\LessThanOrEqual("today", message: "La date de la facture ne peut pas être dans le futur.", groups: ['manual'])]
    private ?\DateTimeInterface $dateFacture = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Assert\NotNull(message: "Le montant est obligatoire.")]
    #[Assert\Positive(message: "Le montant doit être supérieur à 0.")]
    private ?string $montant = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'état de la facture est obligatoire.")]
    #[Assert\Choice(choices: ['Payée', 'Partiellement payé', 'Non payée'], message: "L'état doit être 'Payée', 'Partiellement payé' ou 'Non payée'.")]
    private ?string $state = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255)]
    private ?string $commentaire = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    #[Assert\NotNull(message: "La facture doit être liée à un client.")]
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

    public function setDateFacture(\DateTimeInterface $dateFacture): static
    {
        $this->dateFacture = $dateFacture;

        return $this;
    }

    public function getMontant(): ?string
    {
        return $this->montant;
    }

    public function setMontant(string $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): static
    {
        $this->commentaire = $commentaire;

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
