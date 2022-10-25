<?php

namespace App\Entity;

use App\Repository\LigneBillcardRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LigneBillcardRepository::class)
  * @ORM\Table(name="bill_ligne_billcard")
 */
class LigneBillcard
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
     * @ORM\Column(type="string", length=255)
     */
    private $machineNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $deliveryNoteNumber;

    /**
     * @ORM\Column(type="float")
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
    private $idNumber;

    /**
     * @ORM\Column(type="float")
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
     * @ORM\ManyToOne(targetEntity=LigneTransfert::class, inversedBy="ligneBillcards")
     */
    private $ligneTransfert;

    public function __construct(ProduitSite $produitSite,\Datetime $date,string $machineNumber, string $delivery,string $received,string $supplier,string $customer, string $idNumber,string $qtySolde,string $totalBalance,Site $site)
    {
        $this->produitSite=$produitSite;
        $this->date=$date;
        $this->machineNumber=$machineNumber;
        $this->deliveryNoteNumber=$delivery;
        $this->quantityReceived=$received;
        $this->supplier=$supplier;
        $this->customer=$customer;
        $this->idNumber=$idNumber;
        $this->quantitySold=$qtySolde;
        $this->totalBalance=$produitSite->getQuantite()+floatval($received)-floatval($qtySolde);
        $this->site=$site;
        $this->createdAt=new \DateTime();
        $this->updatedAt=new \DateTime();
    }

    public function setUpdate(ProduitSite $produitSite,\Datetime $date,string $machineNumber, string $delivery,string $received,string $supplier,string $customer, string $idNumber,string $qtySolde,string $totalBalance,Site $site){
        $this->produitSite=$produitSite;
        $this->date=$date;
        $this->machineNumber=$machineNumber;
        $this->deliveryNoteNumber=$delivery;
        $this->quantityReceived=$received;
        $this->supplier=$supplier;
        $this->customer=$customer;
        $this->idNumber=$idNumber;
        $this->quantitySold=$qtySolde;
        $this->totalBalance=$produitSite->getQuantite()+floatval($received)-floatval($qtySolde);
        $this->site=$site;
        $this->updatedAt=new \DateTime();
    }


    public function getId(): ?int
    {
        return $this->id;
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

    public function getIdNumber(): ?string
    {
        return $this->idNumber;
    }

    public function setIdNumber(string $idNumber): self
    {
        $this->idNumber = $idNumber;

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

    public function getLigneTransfert(): ?LigneTransfert
    {
        return $this->ligneTransfert;
    }

    public function setLigneTransfert(?LigneTransfert $ligneTransfert): self
    {
        $this->ligneTransfert = $ligneTransfert;

        return $this;
    }
}
