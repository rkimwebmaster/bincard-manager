<?php

namespace App\Entity;

use App\Repository\RAnnuelGeneralRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
  * @ORM\Table(name="bill_ranuelgeneral")
 * @ORM\Entity(repositoryClass=RAnnuelGeneralRepository::class)
 */
class RAnnuelGeneral
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $annee;

    /**
     * @ORM\OneToMany(targetEntity=LigneRAnnuelGeneral::class, mappedBy="rapport",cascade={"persist","remove"})
     */
    private $ligneRAnnuelGenerals;

    public function __construct()
    {
        $this->ligneRAnnuelGenerals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * @return Collection|LigneRAnnuelGeneral[]
     */
    public function getLigneRAnnuelGenerals(): Collection
    {
        return $this->ligneRAnnuelGenerals;
    }

    public function addLigneRAnnuelGeneral(LigneRAnnuelGeneral $ligneRAnnuelGeneral): self
    {
        if (!$this->ligneRAnnuelGenerals->contains($ligneRAnnuelGeneral)) {
            $this->ligneRAnnuelGenerals[] = $ligneRAnnuelGeneral;
            $ligneRAnnuelGeneral->setRapport($this);
        }

        return $this;
    }

    public function removeLigneRAnnuelGeneral(LigneRAnnuelGeneral $ligneRAnnuelGeneral): self
    {
        if ($this->ligneRAnnuelGenerals->removeElement($ligneRAnnuelGeneral)) {
            // set the owning side to null (unless already changed)
            if ($ligneRAnnuelGeneral->getRapport() === $this) {
                $ligneRAnnuelGeneral->setRapport(null);
            }
        }

        return $this;
    }
}
