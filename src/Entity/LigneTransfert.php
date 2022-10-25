<?php

namespace App\Entity;

use App\Repository\LigneTransfertRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LigneTransfertRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="bill_ligne_transfert")
 */
class LigneTransfert
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ProduitSite::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $produitSite;

    /**
     * @ORM\Column(type="float")
     */
    private $quantite;

    /**
     * @ORM\Column(type="string", length=255, nullable= true )
     */
    private $observation;

    /**
     * @ORM\ManyToOne(targetEntity=Transfert::class, inversedBy="ligneTransferts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $transfert;

    /**
     * @ORM\OneToMany(targetEntity=LigneBillcard::class, mappedBy="ligneTransfert",cascade={"persist","remove"})
     */
    private $ligneBillcards;

    /**
     * @ORM\OneToMany(targetEntity=LigneStockControle::class, mappedBy="ligneTransfert",cascade={"persist","remove"})
     */
    private $ligneStockControles;

    public function __construct()
    {
        $this->ligneBillcards = new ArrayCollection();
        $this->ligneStockControles = new ArrayCollection();
    }

     /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateLigneBincard()
    {
        $produitSite = $this->getProduitSite();
        $ligneStockControle = new LigneStockControle($this,  $produitSite,  null );
       $this->addLigneStockControle($ligneStockControle);
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduitSite(): ?ProduitSite
    {
        return $this->produitSite;
    }

    public function setProduitSite(?ProduitSite $produitSite): self
    {
        $this->produitSite = $produitSite;

        return $this;
    }

    public function getQuantite(): ?float
    {
        return $this->quantite;
    }

    public function setQuantite(float $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getObservation(): ?string
    {
        return $this->observation;
    }

    public function setObservation(string $observation): self
    {
        $this->observation = $observation;

        return $this;
    }

    public function getTransfert(): ?Transfert
    {
        return $this->transfert;
    }

    public function setTransfert(?Transfert $transfert): self
    {
        $this->transfert = $transfert;

        return $this;
    }

    /**
     * @return Collection|LigneBillcard[]
     */
    public function getLigneBillcards(): Collection
    {
        return $this->ligneBillcards;
    }

    public function addLigneBillcard(LigneBillcard $ligneBillcard): self
    {
        if (!$this->ligneBillcards->contains($ligneBillcard)) {
            $this->ligneBillcards[] = $ligneBillcard;
            $ligneBillcard->setLigneTransfert($this);
        }

        return $this;
    }

    public function removeLigneBillcard(LigneBillcard $ligneBillcard): self
    {
        if ($this->ligneBillcards->removeElement($ligneBillcard)) {
            // set the owning side to null (unless already changed)
            if ($ligneBillcard->getLigneTransfert() === $this) {
                $ligneBillcard->setLigneTransfert(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LigneStockControle[]
     */
    public function getLigneStockControles(): Collection
    {
        return $this->ligneStockControles;
    }

    public function addLigneStockControle(LigneStockControle $ligneStockControle): self
    {
        if (!$this->ligneStockControles->contains($ligneStockControle)) {
            $this->ligneStockControles[] = $ligneStockControle;
            $ligneStockControle->setLigneTransfert($this);
        }

        return $this;
    }

    public function removeLigneStockControle(LigneStockControle $ligneStockControle): self
    {
        if ($this->ligneStockControles->removeElement($ligneStockControle)) {
            // set the owning side to null (unless already changed)
            if ($ligneStockControle->getLigneTransfert() === $this) {
                $ligneStockControle->setLigneTransfert(null);
            }
        }

        return $this;
    }
}
