<?php

namespace App\Entity;

use App\Repository\LigneRAnnuelSiteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="bill_ligne_rannuel_site")
 * @ORM\Entity(repositoryClass=LigneRAnnuelSiteRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class LigneRAnnuelSite
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
    private $pn;

    /**
     * @ORM\Column(type="float")
     */
    private $quantiteInitiale;

    /**
     * @ORM\Column(type="float")
     */
    private $quantiteEntree;

    /**
     * @ORM\Column(type="float")
     */
    private $quantiteEntreeSpeciale;

    /**
     * @ORM\Column(type="float")
     */
    private $quantiteEntreeTransfert;

    /**
     * @ORM\Column(type="float")
     */
    private $quantiteEntreeReuse;

    /**
     * @ORM\Column(type="float")
     */
    private $sortieClient;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sortieSpeciale;

    /**
     * @ORM\Column(type="float")
     */
    private $quantiteSortieTransfert;

    /**
     * @ORM\Column(type="float")
     */
    private $quantiteFinale;

    /**
     * @ORM\Column(type="float")
     */
    private $sortieDamage;

    /**
     * @ORM\ManyToOne(targetEntity=RapportAnnuelSite::class, inversedBy="ligneRAnnuelSites")
     * @ORM\JoinColumn(nullable=false)
     */
    private $rapport;

    public function __construct()
    {
        $this->setQuantiteEntree(0);
        $this->setQuantiteEntreeReuse(0);
        $this->setQuantiteEntreeSpeciale(0);
        $this->setQuantiteEntreeTransfert(0);
        $this->setQuantiteInitiale(0);
        $this->setQuantiteSortieTransfert(0);
        $this->setSortieClient(0);
        $this->setSortieSpeciale(0);
        $this->setSortieDamage(0);

        $this->setQuantiteFinale(0);
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateObject()
    {


        $totalEntreeEtInitial = $this->getQuantiteInitiale() +
            $this->getQuantiteEntree() +
            $this->getQuantiteEntreeReuse() +
            $this->getQuantiteEntreeSpeciale() +
            $this->getQuantiteEntreeTransfert();
        $totalSortie =
            $this->getQuantiteSortieTransfert() +
            $this->getSortieClient() +
            $this->getSortieSpeciale() +
            $this->getSortieDamage();


        $this->setQuantiteFinale($totalEntreeEtInitial - $totalSortie);
    }

    public function getRapport(): ?RapportAnnuelSite
    {
        return $this->rapport;
    }

    public function setRapport(?RapportAnnuelSite $rapport): self
    {
        $this->rapport = $rapport;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantiteInitiale(): ?float
    {
        return $this->quantiteInitiale;
    }

    public function setQuantiteInitiale(float $quantiteInitiale): self
    {
        $this->quantiteInitiale = $quantiteInitiale;

        return $this;
    }

    public function getQuantiteEntree(): ?float
    {
        return $this->quantiteEntree;
    }

    public function setQuantiteEntree(float $quantiteEntree): self
    {
        $this->quantiteEntree = $quantiteEntree;

        return $this;
    }

    public function getQuantiteEntreeSpeciale(): ?float
    {
        return $this->quantiteEntreeSpeciale;
    }

    public function setQuantiteEntreeSpeciale(float $quantiteEntreeSpeciale): self
    {
        $this->quantiteEntreeSpeciale = $quantiteEntreeSpeciale;

        return $this;
    }

    public function getQuantiteEntreeTransfert(): ?float
    {
        return $this->quantiteEntreeTransfert;
    }

    public function setQuantiteEntreeTransfert(float $quantiteEntreeTransfert): self
    {
        $this->quantiteEntreeTransfert = $quantiteEntreeTransfert;

        return $this;
    }

    public function getQuantiteEntreeReuse(): ?float
    {
        return $this->quantiteEntreeReuse;
    }

    public function setQuantiteEntreeReuse(float $quantiteEntreeReuse): self
    {
        $this->quantiteEntreeReuse = $quantiteEntreeReuse;

        return $this;
    }

    public function getSortieClient(): ?float
    {
        return $this->sortieClient;
    }

    public function setSortieClient(float $sortieClient): self
    {
        $this->sortieClient = $sortieClient;

        return $this;
    }

    public function getSortieSpeciale(): ?string
    {
        return $this->sortieSpeciale;
    }

    public function setSortieSpeciale(string $sortieSpeciale): self
    {
        $this->sortieSpeciale = $sortieSpeciale;

        return $this;
    }

    public function getQuantiteSortieTransfert(): ?float
    {
        return $this->quantiteSortieTransfert;
    }

    public function setQuantiteSortieTransfert(float $quantiteSortieTransfert): self
    {
        $this->quantiteSortieTransfert = $quantiteSortieTransfert;

        return $this;
    }

    public function getQuantiteFinale(): ?float
    {
        return $this->quantiteFinale;
    }

    public function setQuantiteFinale(float $quantiteFinale): self
    {
        $this->quantiteFinale = $quantiteFinale;

        return $this;
    }

    public function getSortieDamage(): ?float
    {
        return $this->sortieDamage;
    }

    public function setSortieDamage(float $sortieDamage): self
    {
        $this->sortieDamage = $sortieDamage;

        return $this;
    }

    public function getPn(): ?ProduitSite
    {
        return $this->pn;
    }

    public function setPn(?ProduitSite $pn): self
    {
        $this->pn = $pn;

        return $this;
    }
}
