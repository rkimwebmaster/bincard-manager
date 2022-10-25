<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
  * @ORM\HasLifecycleCallbacks()
   * @ORM\Table(name="bill_produit")
 * @UniqueEntity(
 *     fields={"pn"},
 *     errorPath="pn",
 *     message="Ce patch number est déjà utilisé pour un autre produit."
 * )
 *  
 */
class Produit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
          * @Assert\NotBlank
     */
    private $pn;

    /**
     * @ORM\Column(type="string", length=255,nullable=false)
          * @Assert\NotBlank
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    private $quantite;

    /**
     * @ORM\Column(type="float")
     */
    private $prixVente;

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
        } else {
            $this->isDangerStock = false;
        }
    } 

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
        $this->quantite=0;

    }

    public function __toString()
    {
        return $this->getPn().' '.$this->getQuantite();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPn(): ?string
    {
        return $this->pn;
    }

    public function setPn(string $pn): self
    {
        $this->pn = $pn;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getPrixVente(): ?float
    {
        return $this->prixVente;
    }

    public function setPrixVente(float $prixVente): self
    {
        $this->prixVente = $prixVente;

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
