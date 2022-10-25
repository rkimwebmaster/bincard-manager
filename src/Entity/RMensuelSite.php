<?php

namespace App\Entity;

use App\Repository\RMensuelSiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="bill_rmensuel")
 * @ORM\Entity(repositoryClass=RMensuelSiteRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class RMensuelSite
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
     * @ORM\Column(type="integer")
     */
    private $mois;

    /**
     * @ORM\Column(type="integer")
     */
    private $annee;

    /**
     * @ORM\OneToMany(targetEntity=LigneRMensuel::class, mappedBy="rMensuelSite",cascade={"persist", "remove"})
     */
    private $ligneRMensuels;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $designationMois;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isCloture;


    public function __toString()
    {
        return 'Rapport mensuel ' . $this->getDesignationMois() . '-' . $this->getAnnee();
    }

    public function __construct(Site $site, int $annee, int $mois)
    {
        $this->ligneRMensuels = new ArrayCollection();
        $this->site = $site;
        $this->mois = $mois;
        $this->annee = $annee;
        $this->isCloture = false;
        $this->verrouMouvements = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function creationVerrou()
    {
        $date=new \DateTime();
        $site=$this->site;
        $rapport=$this;
        $annee=$this->annee;
        $mois=$this->mois;
        $verrouMouvement=new VerrouMouvement($date,$site,$mois,$annee,$rapport);
    }
    
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateAlerte()
    {
        $mois = $this->mois;

        if ($mois == 1) {
            $this->designationMois = 'janvier';
        } elseif ($mois == 2) {
            $this->designationMois = 'février';
        } elseif ($mois == 3) {
            $this->designationMois = 'mars';
        } elseif ($mois == 4) {
            $this->designationMois = 'avril';
        } elseif ($mois == 5) {
            $this->designationMois = 'mai';
        } elseif ($mois == 6) {
            $this->designationMois = 'juin';
        } elseif ($mois == 7) {
            $this->designationMois = 'juillet';
        } elseif ($mois == 8) {
            $this->designationMois = 'août';
        } elseif ($mois == 9) {
            $this->designationMois = 'septembre';
        } elseif ($mois == 10) {
            $this->designationMois = 'octobre';
        } elseif ($mois == 11) {
            $this->designationMois = 'novembre';
        } elseif ($mois == 12) {
            $this->designationMois = 'décembre';
        }
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

    public function getMois(): ?int
    {
        return $this->mois;
    }

    public function setMois(int $mois): self
    {
        $this->mois = $mois;

        return $this;
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
     * @return Collection|LigneRMensuel[]
     */
    public function getLigneRMensuels(): Collection
    {
        return $this->ligneRMensuels;
    }

    public function addLigneRMensuel(LigneRMensuel $ligneRMensuel): self
    {
        if (!$this->ligneRMensuels->contains($ligneRMensuel)) {
            $this->ligneRMensuels[] = $ligneRMensuel;
            $ligneRMensuel->setRMensuelSite($this);
        }

        return $this;
    }

    public function removeLigneRMensuel(LigneRMensuel $ligneRMensuel): self
    {
        if ($this->ligneRMensuels->removeElement($ligneRMensuel)) {
            // set the owning side to null (unless already changed)
            if ($ligneRMensuel->getRMensuelSite() === $this) {
                $ligneRMensuel->setRMensuelSite(null);
            }
        }

        return $this;
    }

    public function getDesignationMois(): ?string
    {
        return $this->designationMois;
    }

    public function setDesignationMois(string $designationMois): self
    {
        $this->designationMois = $designationMois;

        return $this;
    }

    public function getIsCloture(): ?bool
    {
        return $this->isCloture;
    }

    public function setIsCloture(bool $isCloture): self
    {
        $this->isCloture = $isCloture;

        return $this;
    }

    /**
     * @return Collection|VerrouMouvement[]
     */
    public function getVerrouMouvements(): Collection
    {
        return $this->verrouMouvements;
    }

    public function addVerrouMouvement(VerrouMouvement $verrouMouvement): self
    {
        if (!$this->verrouMouvements->contains($verrouMouvement)) {
            $this->verrouMouvements[] = $verrouMouvement;
            $verrouMouvement->setRapportMensuel($this);
        }

        return $this;
    }

    public function removeVerrouMouvement(VerrouMouvement $verrouMouvement): self
    {
        if ($this->verrouMouvements->removeElement($verrouMouvement)) {
            // set the owning side to null (unless already changed)
            if ($verrouMouvement->getRapportMensuel() === $this) {
                $verrouMouvement->setRapportMensuel(null);
            }
        }

        return $this;
    }
}
