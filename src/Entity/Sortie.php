<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SortieRepository::class)
 * @ORM\Table(name="bill_sortie")
  * @ORM\HasLifecycleCallbacks()
 */
class Sortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Site::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $siteEnvoie;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(min=2)
     */
    private $iddNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $machineNumber;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDamage;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isSortieSpeciale;

    /**
     * @ORM\OneToMany(targetEntity=LigneSortie::class, mappedBy="sortie", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $ligneSorties;

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

    public function __construct(Client $client=null)
    {
        $this->client = $client;
        $this->ligneSorties = new ArrayCollection();
        $this->isDamage = false;
        $this->setCode(strtoupper(uniqid('SRT-')));
        $this->createdAt= new \DateTime();
        $this->updatedAt=new \DateTime();
        $this->date=new \DateTime();
        $this->isSortieSpeciale=false;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate 
     */
    public function mettreAJour()
    {
        if ($this->getIsDamage()) {
            $this->setCode(strtoupper(uniqid('SRT-DMG-')));
        } elseif($this->getIsSortieSpeciale()){
            $this->setCode(strtoupper(uniqid('SRT-SPC-')));
        }
        $this->mois=$this->date->format('m');
        $this->annee=$this->date->format('Y');
    }

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

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

    public function getSiteEnvoie(): ?site
    {
        return $this->siteEnvoie;
    }

    public function setSiteEnvoie(?site $siteEnvoie): self
    {
        $this->siteEnvoie = $siteEnvoie;

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

    public function getIddNumber(): ?string
    {
        return $this->iddNumber;
    }

    public function setIddNumber(string $iddNumber): self
    {
        $this->iddNumber = $iddNumber;

        return $this;
    }

    public function getMachineNumber(): ?string
    {
        return $this->machineNumber;
    }

    public function setMachineNumber(string $machineNumber): self
    {
        $this->machineNumber = $machineNumber;

        return $this;
    }

    public function getIsDamage(): ?bool
    {
        return $this->isDamage;
    }

    public function setIsDamage(bool $isDamage): self
    {
        $this->isDamage = $isDamage;

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

    public function getIsSortieSpeciale(): ?bool
    {
        return $this->isSortieSpeciale;
    }

    public function setIsSortieSpeciale(bool $isSortieSpeciale): self
    {
        $this->isSortieSpeciale = $isSortieSpeciale;

        return $this;
    }

    /**
     * @return Collection|LigneSortie[]
     */
    public function getLigneSorties(): Collection
    {
        return $this->ligneSorties;
    }

    public function addLigneSorty(LigneSortie $ligneSorty): self
    {
        if (!$this->ligneSorties->contains($ligneSorty)) {
            $this->ligneSorties[] = $ligneSorty;
            $ligneSorty->setSortie($this);
        }

        return $this;
    }

    public function removeLigneSorty(LigneSortie $ligneSorty): self
    {
        if ($this->ligneSorties->removeElement($ligneSorty)) {
            // set the owning side to null (unless already changed)
            if ($ligneSorty->getSortie() === $this) {
                $ligneSorty->setSortie(null);
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
