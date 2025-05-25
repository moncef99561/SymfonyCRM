<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Utilisateur;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom du gérant est obligatoire.")]
    #[Assert\Length(min: 2, max: 255)]
    private ?string $gerantNom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le prénom du gérant est obligatoire.")]
    #[Assert\Length(min: 2, max: 255)]
    private ?string $gerantPrenom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La raison sociale est obligatoire.")]
    private ?string $raisonSocial = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le téléphone est obligatoire.")]
    #[Assert\Regex(
        pattern: '/^0[6-7][0-9]{8}$/',
        message: "Numéro de téléphone invalide."
    )]
    private ?string $telephone = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'adresse est obligatoire.")]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La ville est obligatoire.")]
    private ?string $ville = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le pays est obligatoire.")]
    private ?string $pays = null;

    #[ORM\ManyToOne(inversedBy: 'clients')]
    private ?Utilisateur $utilisateurOwner = null;

    /**
     * @var Collection<int, Facture>
     */
    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Facture::class, cascade: ['remove'], orphanRemoval: true)]
    private Collection $factures;

    public function __construct()
    {
        $this->factures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGerantNom(): ?string
    {
        return $this->gerantNom;
    }

    public function setGerantNom(string $gerantNom): static
    {
        $this->gerantNom = $gerantNom;
        return $this;
    }

    public function getGerantPrenom(): ?string
    {
        return $this->gerantPrenom;
    }

    public function setGerantPrenom(string $gerantPrenom): static
    {
        $this->gerantPrenom = $gerantPrenom;
        return $this;
    }

    public function getRaisonSocial(): ?string
    {
        return $this->raisonSocial;
    }

    public function setRaisonSocial(string $raisonSocial): static
    {
        $this->raisonSocial = $raisonSocial;
        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;
        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;
        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): static
    {
        $this->pays = $pays;
        return $this;
    }

    public function getUtilisateurOwner(): ?Utilisateur
    {
        return $this->utilisateurOwner;
    }

    public function setUtilisateurOwner(?Utilisateur $utilisateurOwner): static
    {
        $this->utilisateurOwner = $utilisateurOwner;
        return $this;
    }

    /**
     * @return Collection<int, Facture>
     */
    public function getFactures(): Collection
    {
        return $this->factures;
    }

    public function addFacture(Facture $facture): static
    {
        if (!$this->factures->contains($facture)) {
            $this->factures->add($facture);
            $facture->setClient($this);
        }
        return $this;
    }

    public function removeFacture(Facture $facture): static
    {
        if ($this->factures->removeElement($facture)) {
            if ($facture->getClient() === $this) {
                $facture->setClient(null);
            }
        }
        return $this;
    }
}
