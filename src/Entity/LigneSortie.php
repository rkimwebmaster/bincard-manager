<?php

namespace App\Entity;

use App\Repository\LigneSortieRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=LigneSortieRepository::class)
 * @ORM\Table(name="bill_ligne_sortie")
 * @ORM\HasLifecycleCallbacks()
 */
class LigneSortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Sortie::class, inversedBy="ligneSorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sortie;

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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $observation;

    /**
     * @ORM\OneToOne(targetEntity=LigneStockControle::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $ligneStockControle;


    public function updateProduit()
    {
    }

    /**
     * @ORM\PrePersist
     */
    public function updateLigneBincard()
    {
        if (!$this->getSortie()->getIsSortieSpeciale()) {
            $produitSite = $this->getProduitSite();
            $ligneStockControle = new LigneStockControle($this,  $produitSite,  null);
            $this->setLigneStockControle($ligneStockControle);
        }

        //dd($this->getLigneStockControle());

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSortie(): ?Sortie
    {
        return $this->sortie;
    }

    public function setSortie(?Sortie $sortie): self
    {
        $this->sortie = $sortie;

        return $this;
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
