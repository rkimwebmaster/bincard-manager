<?php

namespace App\Entity;

use App\Repository\EntreeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;




/**
 * @ORM\Entity(repositoryClass=EntreeRepository::class)
 * @ORM\Table(name="bill_entree")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *     fields={"code"},
 *     errorPath="code",
 *     message="Ce code est déjà utilisé pour une autre entrée."
 * )
 * 
 */
class Entree
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Site::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $siteReception;

    /**
     * @ORM\ManyToOne(targetEntity=Fournisseur::class)
     */
    private $fournisseur;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true )
     */
    private $numeroBonFournisseur;

    /**
     * @ORM\Column(type="string", length=255, unique=true )
     */
    private $code;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isReuse;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isEntreeSpeciale;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=LigneEntree::class, mappedBy="entree", cascade={"persist","remove"}, orphanRemoval=true)
     */
    private $ligneEntrees;

    public $oldLigneEntrees;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $observation;

    /**
     * @ORM\Column(type="integer")
     */
    private $mois;

    /**
     * @ORM\Column(type="integer")
     */
    private $annee;

    public function __construct(Fournisseur $fournisseur = null, Site $site)
    {
        $this->oldLigneEntrees = new ArrayCollection();

        $this->fournisseur = $fournisseur;
        $this->ligneEntrees = new ArrayCollection();
        $this->date = new \DateTime();
        $this->isValidee = false;
        $this->isReuse = false;
        $this->isEntreeSpeciale = false;
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->setSiteReception($site);
        $this->code = strtoupper(uniqid('ENT-FSS-'));
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateEntree()
    {
        if ($this->getIsReuse()) {
            $this->code = strtoupper(uniqid('ENT-REU-'));
        } elseif ($this->getIsEntreeSpeciale()) {
            $this->code = strtoupper(uniqid('ENT-SPE-'));
        } else {
            $this->code = strtoupper(uniqid('ENT-FSS-'));
        }
        $this->mois=$this->date->format('m');
        $this->annee=$this->date->format('Y');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSiteReception(): ?Site
    {
        return $this->siteReception;
    }

    public function setSiteReception(?Site $siteReception): self
    {
        $this->siteReception = $siteReception;

        return $this;
    }

    public function getFournisseur(): ?Fournisseur
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?Fournisseur $fournisseur): self
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getNumeroBonFournisseur(): ?string
    {
        return $this->numeroBonFournisseur;
    }

    public function setNumeroBonFournisseur(string $numeroBonFournisseur): self
    {
        $this->numeroBonFournisseur = $numeroBonFournisseur;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getIsReuse(): ?bool
    {
        return $this->isReuse;
    }

    public function setIsReuse(bool $isReuse): self
    {
        $this->isReuse = $isReuse;

        return $this;
    }

    public function getIsEntreeSpeciale(): ?bool
    {
        return $this->isEntreeSpeciale;
    }

    public function setIsEntreeSpeciale(bool $isEntreeSpeciale): self
    {
        $this->isEntreeSpeciale = $isEntreeSpeciale;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|LigneEntree[]
     */
    public function getLigneEntrees(): Collection
    {
        return $this->ligneEntrees;
    }

    public function addLigneEntree(LigneEntree $ligneEntree): self
    {
        if (!$this->ligneEntrees->contains($ligneEntree)) {
            $this->ligneEntrees[] = $ligneEntree;
            $ligneEntree->setEntree($this);
        }

        return $this;
    }

    public function removeLigneEntree(LigneEntree $ligneEntree): self
    {
        if ($this->ligneEntrees->removeElement($ligneEntree)) {
            // set the owning side to null (unless already changed)
            if ($ligneEntree->getEntree() === $this) {
                $ligneEntree->setEntree(null);
            }
        }

        return $this;
    }

    public function getObservation(): ?string
    {
        return $this->observation;
    }

    public function setObservation(?string $observation): self
    {
        $this->observation = $observation;

        return $this;
    }

    public function getMois(): ?int
    {
        return $this->mois;
    }

    public function setMois(int $mois): self
    {
        $this->mois = $mois;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): self
    {
        $this->annee = $annee;

        return $this;
    }
}
