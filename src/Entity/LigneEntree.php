<?php

namespace App\Entity;

use App\Repository\LigneEntreeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LigneEntreeRepository::class)
 * @ORM\Table(name="bill_ligne_entree")
 * @ORM\HasLifecycleCallbacks()
 */
class LigneEntree
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ProduitSite::class, fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $produitSite;

    /**
     * @ORM\Column(type="float")
     */
    private $quantite;

    /**
     * @ORM\ManyToOne(targetEntity=Entree::class, inversedBy="ligneEntrees")
     * @ORM\JoinColumn(nullable=false)
     */
    private $entree;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $observation;

  

    /**
     * @ORM\ManyToOne(targetEntity=Produit::class, fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $produit;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\OneToOne(targetEntity=LigneStockControle::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $ligneStockControle;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * @ORM\PostLoad
     */
    public function onUpdatedAt()
    {
        $this->setUpdatedAt(new \DateTime());
       // exit(var_dump($this->getUpdatedAt()));
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateProduit()
    {
    }

    public function updateLigneBillcard()
    {
        $produitSite = $this->getProduitSite();
        $ligneStockControle = new LigneStockControle($this,  $produitSite,  null );
       $this->setLigneStockControle($ligneStockControle);
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

    public function getEntree(): ?Entree
    {
        return $this->entree;
    }

    public function setEntree(?Entree $entree): self
    {
        $this->entree = $entree;

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

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

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

    public function getLigneStockControle(): ?LigneStockControle
    {
        return $this->ligneStockControle;
    }

    public function setLigneStockControle(LigneStockControle $ligneStockControle): self
    {
        $this->ligneStockControle = $ligneStockControle;

        return $this;
    }
}
