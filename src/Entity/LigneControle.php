<?php

namespace App\Entity;

use App\Repository\LigneControleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LigneControleRepository::class)
 * @ORM\Table(name="bill_ligne_controle")
 * @ORM\HasLifecycleCallbacks()
 */
class LigneControle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Controle::class, inversedBy="ligneControles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $controle;

    /**
     * @ORM\ManyToOne(targetEntity=ProduitSite::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $produitSite;

    /**
     * @ORM\Column(type="float")
     */
    private $quantitePhysique;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $observation;


    /**
     * @ORM\Column(type="float")
     */
    private $quantiteBillcard;

    /**
     * @ORM\Column(type="float")
     */
    private $manquant;

    /**
     * @ORM\Column(type="float")
     */
    private $surplus;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=LigneStockControle::class, inversedBy="ligneControles",cascade={"persist"})
     */
    private $ligneStockControle;
    
    public function updateLigneBincard()
    {

        $produitSite = $this->getProduitSite();
        $ligneStockControles=$this->getLigneStockControle();
        // dd($ligneStockControles);
        if($ligneStockControles){
            dd('bubishi  ');
        }else{
            $ligneStockControle = new LigneStockControle($this,  $produitSite,  null );
        }
       $this->setLigneStockControle($ligneStockControle);
    }

    public function __construct()
    {
        $this->setManquant(0);
        $this->setSurplus(0);
        $this->setQuantiteBillcard(0);
        $this->setCreatedAt(new \DateTime());
       $this->setUpdatedAt(new \DateTime());
    }

    /**
     * @ORM\PostLoad
     */
    public function updateUpdatedAt()
    {
        $this->setUpdatedAt(new \DateTime());
    }
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateQuantitePhysique()
    {
        $quantiteProduitSite = $this->getProduitSite()->getQuantite();
        $quantitePhysique = $this->getQuantitePhysique();
        if ($quantitePhysique > $quantiteProduitSite) {
            $this->setSurplus($quantitePhysique - $quantiteProduitSite);
        } elseif ($quantitePhysique < $quantiteProduitSite) {
            $this->setManquant($quantiteProduitSite - $quantitePhysique);
        }
        $this->setQuantiteBillcard($quantiteProduitSite);
    }


    public function checkManquantSurplus(){
        if($this->getManquant()>0){
            return -1;
        } elseif($this->getSurplus()>0){
            return 1;

        }
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getControle(): ?Controle
    {
        return $this->controle;
    }

    public function setControle(?Controle $controle): self
    {
        $this->controle = $controle;

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

    public function getQuantitePhysique(): ?float
    {
        return $this->quantitePhysique;
    }

    public function setQuantitePhysique(float $quantitePhysique): self
    {
        $this->quantitePhysique = $quantitePhysique;

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

    public function getLigneBillcard(): ?LigneBillcard
    {
        return $this->ligneBillcard;
    }

    public function setLigneBillcard(LigneBillcard $ligneBillcard): self
    {
        $this->ligneBillcard = $ligneBillcard;

        return $this;
    }

    public function getQuantiteBillcard(): ?float
    {
        return $this->quantiteBillcard;
    }

    public function setQuantiteBillcard(float $quantiteBillcard): self
    {
        $this->quantiteBillcard = $quantiteBillcard;

        return $this;
    }

    public function getManquant(): ?float
    {
        return $this->manquant;
    }

    public function setManquant(float $manquant): self
    {
        $this->manquant = $manquant;

        return $this;
    }

    public function getSurplus(): ?float
    {
        return $this->surplus;
    }

    public function setSurplus(float $surplus): self
    {
        $this->surplus = $surplus;

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

    // public function getLigneStockControle(): ?LigneStockControle
    // {
    //     return $this->ligneStockControle;
    // }

    // public function setLigneStockControle(?LigneStockControle $ligneStockControle): self
    // {
    //     $this->ligneStockControle = $ligneStockControle;

    //     return $this;
    // }

    public function getLigneStockControle(): ?LigneStockControle
    {
        return $this->ligneStockControle;
    }

    public function setLigneStockControle(?LigneStockControle $ligneStockControle): self
    {
        $this->ligneStockControle = $ligneStockControle;

        return $this;
    }
}
