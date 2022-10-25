<?php

namespace App\Entity;

use App\Repository\ProduitSiteRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



/**
 * @ORM\Entity(repositoryClass=ProduitSiteRepository::class)
 * @ORM\Table(name="bill_produit_site")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *     fields={"produit","site"},
 *     errorPath="produit",
 *     message="Ce produit existe dejà dans ce site/warehouse."
 * )
 * 
 */
class ProduitSite
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Produit::class, fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $produit;

    /**
     * @ORM\ManyToOne(targetEntity=Site::class,cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $site;

    /**
     * @ORM\Column(type="float")
     */
    private $quantite;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isProduitDepot;

    /**
     * @ORM\Column(type="float")
     */
    private $stockAlerte;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDangerStock;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

        /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateAlerte()
    {
        $stockAlerte = $this->stockAlerte;
        $quantite = $this->quantite;

        if ($quantite <= $stockAlerte) {
            $this->isDangerStock = true;
        } elseif($quantite > $stockAlerte) {
            $this->isDangerStock = false;
        }
    }

    /**
     * @ORM\PrePersist
     */
    public function updateIsProduitDepot()
    {
        $site = $this->getSite();
        if ($site->getIsWarehouse()) {
            $this->isProduitDepot = true;
        } else {
            $this->isProduitDepot = false;
        }
    }

    public function __toString()
    {
        return (string) $this->getProduit() . ' ' . ' en Stock sur site =  ' . $this->getQuantite();
    }

    public function __construct()
    {
        $this->stockAlerte=0;
        $this->isProduitDepot=false;
        $this->createdAt= new \DateTime();
        $this->updatedAt=new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

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

    public function getIsProduitDepot(): ?bool
    {
        return $this->isProduitDepot;
    }

    public function setIsProduitDepot(bool $isProduitDepot): self
    {
        $this->isProduitDepot = $isProduitDepot;

        return $this;
    }

    public function getStockAlerte(): ?float
    {
        return $this->stockAlerte;
    }

    public function setStockAlerte(float $stockAlerte): self
    {
        $this->stockAlerte = $stockAlerte;

        return $this;
    }

    public function getIsDangerStock(): ?bool
    {
        return $this->isDangerStock;
    }

    public function setIsDangerStock(bool $isDangerStock): self
    {
        $this->isDangerStock = $isDangerStock;

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
}
