<?php

namespace App\Entity;

use App\Repository\TransfertRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TransfertRepository::class)
  * @ORM\Table(name="bill_transfert")
 * @ORM\HasLifecycleCallbacks()
 */
class Transfert
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Site::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $siteEnvoie;

    /**
     * @ORM\ManyToOne(targetEntity=Site::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $siteReception;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity=LigneTransfert::class, mappedBy="transfert", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $ligneTransferts;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isValidee;

    /**
     * @ORM\Column(type="integer")
     */
    private $mois;

    /**
     * @ORM\Column(type="integer")
     */
    private $annee;


    public function __construct(Site $siteEnvoie,Site $siteReception)
    {
        $this->siteReception=$siteReception;
        $this->siteEnvoie=$siteEnvoie;


        $this->ligneTransferts = new ArrayCollection();
        $this->createdAt=new \DateTime();
        $this->updatedAt=new \DateTime();

        $this->date=new \DateTime();
        $this->isValidee=false;
        $this->code=strtoupper(uniqid('TRF-'));
        //mettre a jour le nbre de validation attendues 
        //$validattionAttendue =$siteReception->getNombreAttendu();
       // $siteReception->setNombreAttendu($validattionAttendue+1);
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate 
     */
    public function mettreAJour()
    {
        $this->mois=$this->date->format('m');
        $this->annee=$this->date->format('Y');
    }

    
    /**
     * @ORM\PreUpdate 
     */
    public function valider()
    {
        if($this->isValidee==true){

        }
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSiteEnvoie(): ?Site
    {
        return $this->siteEnvoie;
    }

    public function setSiteEnvoie(?Site $siteEnvoie): self
    {
        $this->siteEnvoie = $siteEnvoie;

        return $this;
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection|LigneTransfert[]
     */
    public function getLigneTransferts(): Collection
    {
        return $this->ligneTransferts;
    }

    public function addLigneTransfert(LigneTransfert $ligneTransfert): self
    {
        if (!$this->ligneTransferts->contains($ligneTransfert)) {
            $this->ligneTransferts[] = $ligneTransfert;
            $ligneTransfert->setTransfert($this);
        }

        return $this;
    }

    public function removeLigneTransfert(LigneTransfert $ligneTransfert): self
    {
        if ($this->ligneTransferts->removeElement($ligneTransfert)) {
            // set the owning side to null (unless already changed)
            if ($ligneTransfert->getTransfert() === $this) {
                $ligneTransfert->setTransfert(null);
            }
        }

        return $this;
    }

    public function getIsValidee(): ?bool
    {
        return $this->isValidee;
    }

    public function setIsValidee(bool $isValidee): self
    {
        $this->isValidee = $isValidee;

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
