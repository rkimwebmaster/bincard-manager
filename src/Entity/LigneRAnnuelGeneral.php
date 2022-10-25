<?php

namespace App\Entity;

use App\Repository\LigneRAnnuelGeneralRepository;
use Doctrine\ORM\Mapping as ORM;

/**
   * @ORM\Table(name="bill_ligne_ranuelgeneral")
 * @ORM\Entity(repositoryClass=LigneRAnnuelGeneralRepository::class)
 */
class LigneRAnnuelGeneral
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Produit::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $pn;

    /**
     * @ORM\ManyToOne(targetEntity=RAnnuelGeneral::class, inversedBy="ligneRAnnuelGenerals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $rapport;

    /**
     * @ORM\Column(type="float")
     */
    private $qteInitiale;

    /**
     * @ORM\Column(type="float")
     */
    private $qteEntreeFournisseur;

    /**
     * @ORM\Column(type="float")
     */
    private $qteEntreeReuse;

    /**
     * @ORM\Column(type="float")
     */
    private $qteSortieClient;

    /**
     * @ORM\Column(type="float")
     */
    private $qteSortieDamage;

    /**
     * @ORM\Column(type="float")
     */
    private $qteSolde;

    /**
     * @ORM\Column(type="float")
     */
    private $qteEntreeSpeciale;

    /**
     * @ORM\Column(type="float")
     */
    private $qteSortieSpeciale;

    public function __construct(){
        $this->qteInitiale=0;
        $this->qteEntreeFournisseur=0;
        $this->qteEntreeReuse=0;
        $this->qteSortieClient=0;
        $this->qteSortieDamage=0;
        $this->qteEntreeSpeciale=0;
        $this->qteSortieSpeciale=0;
        $this->qteSolde=0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQteInitiale(): ?float
    {
        return $this->qteInitiale;
    }

    public function setQteInitiale(float $qteInitiale): self
    {
        $this->qteInitiale = $qteInitiale;

        return $this;
    }

    public function getQteEntreeFournisseur(): ?float
    {
        return $this->qteEntreeFournisseur;
    }

    public function setQteEntreeFournisseur(float $qteEntreeFournisseur): self
    {
        $this->qteEntreeFournisseur = $qteEntreeFournisseur;

        return $this;
    }

    public function getQteEntreeReuse(): ?float
    {
        return $this->qteEntreeReuse;
    }

    public function setQteEntreeReuse(float $qteEntreeReuse): self
    {
        $this->qteEntreeReuse = $qteEntreeReuse;

        return $this;
    }

    public function getQteSortieClient(): ?float
    {
        return $this->qteSortieClient;
    }

    public function setQteSortieClient(float $qteSortieClient): self
    {
        $this->qteSortieClient = $qteSortieClient;

        return $this;
    }

    public function getQteSortieDamage(): ?float
    {
        return $this->qteSortieDamage;
    }

    public function setQteSortieDamage(float $qteSortieDamage): self
    {
        $this->qteSortieDamage = $qteSortieDamage;

        return $this;
    }

    public function getQteSolde(): ?float
    {
        return $this->qteSolde;
    }

    public function setQteSolde(float $qteSolde): self
    {
        $this->qteSolde = $qteSolde;

        return $this;
    }

    public function getPn(): ?Produit
    {
        return $this->pn;
    }

    public function setPn(?Produit $pn): self
    {
        $this->pn = $pn;

        return $this;
    }

    public function getRapport(): ?RAnnuelGeneral
    {
        return $this->rapport;
    }

    public function setRapport(?RAnnuelGeneral $rapport): self
    {
        $this->rapport = $rapport;

        return $this;
    }

    public function getQteEntreeSpeciale(): ?float
    {
        return $this->qteEntreeSpeciale;
    }

    public function setQteEntreeSpeciale(float $qteEntreeSpeciale): self
    {
        $this->qteEntreeSpeciale = $qteEntreeSpeciale;

        return $this;
    }

    public function getQteSortieSpeciale(): ?float
    {
        return $this->qteSortieSpeciale;
    }

    public function setQteSortieSpeciale(float $qteSortieSpeciale): self
    {
        $this->qteSortieSpeciale = $qteSortieSpeciale;

        return $this;
    }

    }
