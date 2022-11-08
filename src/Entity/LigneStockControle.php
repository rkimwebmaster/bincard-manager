<?php

namespace App\Entity;

use App\Repository\LigneStockControleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LigneStockControleRepository::class)
 * @ORM\Table(name="bill_ligne_stock_controle")
 * @ORM\HasLifecycleCallbacks()
 */
class LigneStockControle
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
    private $site;

    /**
     * @ORM\ManyToOne(targetEntity=ProduitSite::class,cascade={"persist"}))
     * @ORM\JoinColumn(nullable=false)
     */
    private $produitSite;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true )
     */
    private $machineNumber='-';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $deliveryNoteNumber;

    /**
     * @ORM\Column(type="float",nullable=true)
     */
    private $quantityReceived;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $supplier;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $customer;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ijdNumber='-';

    /**
     * @ORM\Column(type="float",nullable=true)
     */
    private $quantitySold;

    /**
     * @ORM\Column(type="float")
     */
    private $totalBalance;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=LigneTransfert::class, inversedBy="ligneStockControles")
     */
    private $ligneTransfert;

    /**
     * @ORM\Column(type="integer")
     */
    private $numeroDNN = 1;

    /**
     * @ORM\Column(type="integer")
     */
    private $numeroMouvement = 0;

    /**
     * @ORM\OneToOne(targetEntity=LigneStockControle::class, cascade={"persist", "remove"})
     */
    private $ligneStockControle;

    /**
     * @ORM\OneToMany(targetEntity=LigneControle::class, mappedBy="ligneStockControle")
     */
    private $ligneControles;



    public function __construct(object  $objetApellant, ProduitSite $produitSite, string $customer = null)
    {
       $this->setUpdate( $objetApellant,  $produitSite,  $customer );
       $this->ligneControles = new ArrayCollection();
    }


    
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * @ORM\PostLoad
     */
    public function updateTotalBalance(){
        $this->totalBalance = $this->produitSite->getQuantite() + floatval($this->getQuantityReceived()) - floatval($this->getQuantitySold());
  
    }
    public function setUpdate(object  $objetApellant, ProduitSite $produitSite, string $customer = null)
    {
        //initialisation generaux 
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->setSite($produitSite->getSite());
        $this->produitSite = $produitSite;
        if ($customer == null) {
            $this->customer = $this->getSite()->getDesignation();
        } else {
            $this->customer = $customer;
        }

        /// traiter le cas des entrées fournisseurs , speciales et reuse 
        if (get_class($objetApellant) == LigneEntree::class) {

            $this->setNumeroMouvement(1);
            $this->date = $objetApellant->getEntree()->getDate();
            //corrigé commenter ci dessous pour checking 
            // $this->supplier = $objetApellant->getEntree()->getFournisseur();

            $this->quantityReceived=$objetApellant->getQuantite();
            if ($objetApellant->getEntree()->getIsEntreeSpeciale()) {
                $this->deliveryNoteNumber = 'ENTREE SPECIALE ';
                $this->setNumeroDNN(3);
                // $this->customer = $objetApellant->getEntree()->getCode();
                $this->customer = $objetApellant->getEntree()->getSiteReception()->getDesignation();
                $this->supplier= $objetApellant->getEntree()->getUser();
                ///traitement des reuse 
            } elseif ($objetApellant->getEntree()->getIsReuse()) {
                $this->deliveryNoteNumber = 'ENTREE REUSE ';
                $this->setNumeroDNN(2);
                $this->ijdNumber = $objetApellant->getEntree()->getCode();
                
                $this->customer = $objetApellant->getEntree()->getSiteReception()->getDesignation();

                $this->supplier= $objetApellant->getEntree()->getUser();
                // dd($supplier);


            } else {
                //traitement des entrees fournisseurs 
                $this->deliveryNoteNumber = 'ENTREE FOURNISSEUR ';
                $this->setNumeroDNN(1);
                $this->customer = $objetApellant->getEntree()->getSiteReception();
                //mise a jour ce 8 mars 
                $this->setIjdNumber($objetApellant->getEntree()->getNumeroBonFournisseur());
                // dd('banba');
                $this->supplier = $objetApellant->getEntree()->getFournisseur();


            }
        }
        /// traiter le cas des sortie client , damage et spciales 
        if (get_class($objetApellant) == LigneSortie::class) {
            $this->date = $objetApellant->getSortie()->getDate();
            $this->quantityReceived=$objetApellant->getQuantite();
            $this->supplier = $objetApellant->getSortie()->getSiteEnvoie()->getDesignation();

            if ($objetApellant->getSortie()->getIsSortieSpeciale()) {

                $this->deliveryNoteNumber = 'SORTIE SPECIALE ';
                $this->quantityReceived=0;
                $this->quantitySold=$objetApellant->getQuantite();

                $this->setNumeroDNN(7);
                // $this->customer = $objetApellant->getSortie()->getCode();
                $this->customer = $objetApellant->getSortie()->getSiteEnvoie()->getDesignation();
                // $this->ijdNumber=$objetApellant->getControle()->getCode();

            } elseif ($objetApellant->getSortie()->getIsDamage()) {
                $this->deliveryNoteNumber = ' DAMAGE ';
                $this->ijdNumber=$objetApellant->getSortie()->getCode();

                $this->quantityReceived=0;
                $this->quantitySold=$objetApellant->getQuantite();

                $this->setNumeroDNN(6);
                // $this->customer = $objetApellant->getSortie()->getCode();
                $this->supplier = $objetApellant->getSortie()->getSiteEnvoie()->getDesignation();
                ///le receiver est placé dans les observations 
                $this->customer= $objetApellant->getSortie()->getObservation();


            } else {
                $this->deliveryNoteNumber = 'SORTIE CLIENT ';
                $this->ijdNumber = $objetApellant->getSortie()->getIddNumber();
                $this->machineNumber = $objetApellant->getSortie()->getMachineNumber();
                $this->setNumeroDNN(5);
                $this->customer = $objetApellant->getSortie()->getClient();
                $this->quantityReceived=0;
                $this->quantitySold=$objetApellant->getQuantite();
            }
        }

        /// traiter le cas des transfert, validation transfert 
        if (get_class($objetApellant) == LigneTransfert::class) {
            // dd('dans le debut de ligne transfert');

            $this->date = $objetApellant->getTransfert()->getDate();
            $this->ijdNumber=$objetApellant->getTransfert()->getCode();
            //mise a jour ce 8 mars 
            $this->customer=$objetApellant->getTransfert()->getSiteReception();
            if ($objetApellant->getTransfert()->getIsValidee()) {
                // dd('dans la validation');
                $this->deliveryNoteNumber = 'TRANSFERT RECU ';
                $this->quantityReceived=$objetApellant->getQuantite();
                $this->supplier = $objetApellant->getTransfert()->getSiteEnvoie()->getDesignation();
                //bien verifier lors de test 
                $this->setNumeroDNN(8);
                // $this->setSite($objetApellant->getTransfert()->getSiteReception());
                // dd($produitSite->getSite());
            } else {
                $this->deliveryNoteNumber = 'TRANSFERT NON VALIDE ';
                $this->quantitySold = $objetApellant->getQuantite();
                $this->setNumeroDNN(4);
                $this->supplier = $objetApellant->getTransfert()->getSiteEnvoie()->getDesignation();

            }
        }

        /// traiter le cas des controles 
        if (get_class($objetApellant) == LigneControle::class) {

            if ($objetApellant->getControle()->getIsValidee()) {
                $this->date = $objetApellant->getControle()->getDate();
                // $this->quantityReceived=$objetApellant->getQuantitePhysique();
                $this->deliveryNoteNumber = 'STOCK TAKE  ';
                $this->ijdNumber=$objetApellant->getControle()->getCode();

                $this->setNumeroDNN(9);
                $this->supplier = $objetApellant->getControle()->getUser();
                $this->customer = $objetApellant->getControle()->getSite();
                
                // $this->customer = $objetApellant->getSortie()->getSiteEnvoie()->getDesignation();

                $controle=$objetApellant->getControle();

                $controle->addLigneControle($objetApellant);
               // bien verifier lors de test 
            } else {
                return;
                dd('non validée ');
            }
        }

        $this->totalBalance = $produitSite->getQuantite() + floatval($this->getQuantityReceived()) - floatval($this->getQuantitySold());
    }




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLigneTransfert(): ?LigneTransfert
    {
        return $this->ligneTransfert;
    }

    public function setLigneTransfert(?LigneTransfert $ligneTransfert): self
    {
        $this->ligneTransfert = $ligneTransfert;

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

    public function getMachineNumber(): ?string
    {
        return $this->machineNumber;
    }

    public function setMachineNumber(string $machineNumber): self
    {
        $this->machineNumber = $machineNumber;

        return $this;
    }

    public function getDeliveryNoteNumber(): ?string
    {
        return $this->deliveryNoteNumber;
    }

    public function setDeliveryNoteNumber(string $deliveryNoteNumber): self
    {
        $this->deliveryNoteNumber = $deliveryNoteNumber;

        return $this;
    }

    public function getQuantityReceived(): ?float
    {
        return $this->quantityReceived;
    }

    public function setQuantityReceived(float $quantityReceived): self
    {
        $this->quantityReceived = $quantityReceived;

        return $this;
    }

    public function getSupplier(): ?string
    {
        return $this->supplier;
    }

    public function setSupplier(string $supplier): self
    {
        $this->supplier = $supplier;

        return $this;
    }

    public function getCustomer(): ?string
    {
        return $this->customer;
    }

    public function setCustomer(string $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getIjdNumber(): ?string
    {
        return $this->ijdNumber;
    }

    public function setIjdNumber(string $ijdNumber): self
    {
        $this->ijdNumber = $ijdNumber;

        return $this;
    }

    public function getQuantitySold(): ?float
    {
        return $this->quantitySold;
    }

    public function setQuantitySold(float $quantitySold): self
    {
        $this->quantitySold = $quantitySold;

        return $this;
    }

    public function getTotalBalance(): ?float
    {
        return $this->totalBalance;
    }

    public function setTotalBalance(float $totalBalance): self
    {
        $this->totalBalance = $totalBalance;

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

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

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

    public function getNumeroDNN(): ?int
    {
        return $this->numeroDNN;
    }

    public function setNumeroDNN(int $numeroDNN): self
    {
        $this->numeroDNN = $numeroDNN;

        return $this;
    }

    public function getNumeroMouvement(): ?int
    {
        return $this->numeroMouvement;
    }

    public function setNumeroMouvement(int $numeroMouvement): self
    {
        $this->numeroMouvement = $numeroMouvement;

        return $this;
    }

    public function getLigneStockControle(): ?self
    {
        return $this->ligneStockControle;
    }

    public function setLigneStockControle(?self $ligneStockControle): self
    {
        $this->ligneStockControle = $ligneStockControle;

        return $this;
    }

    /**
     * @return Collection|LigneControle[]
     */
    public function getLigneControles(): Collection
    {
        return $this->ligneControles;
    }

    public function addLigneControle(LigneControle $ligneControle): self
    {
        if (!$this->ligneControles->contains($ligneControle)) {
            $this->ligneControles[] = $ligneControle;
            $ligneControle->setLigneStockControle($this);
        }

        return $this;
    }

    public function removeLigneControle(LigneControle $ligneControle): self
    {
        if ($this->ligneControles->removeElement($ligneControle)) {
            // set the owning side to null (unless already changed)
            if ($ligneControle->getLigneStockControle() === $this) {
                $ligneControle->setLigneStockControle(null);
            }
        }

        return $this;
    }
}
