<?php

namespace App\Entity;

use App\Repository\RapportAnnuelSiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
   * @ORM\Table(name="bill_ranuelsite")
 * @ORM\Entity(repositoryClass=RapportAnnuelSiteRepository::class)
 */
class RapportAnnuelSite
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
    private $annee;

    /**
     * @ORM\OneToMany(targetEntity=LigneRAnnuelSite::class, mappedBy="rapport",cascade={"persist", "remove"}))
     */
    private $ligneRAnnuelSites;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isCloture;

    public function __toString()
    {
        return (string) $this->annee;
    }

    public function __construct()
    {
        $this->ligneRAnnuelSites = new ArrayCollection();
        $this->isCloture=false;
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
     * @return Collection|LigneRAnnuelSite[]
     */
    public function getLigneRAnnuelSites(): Collection
    {
        return $this->ligneRAnnuelSites;
    }

    public function addLigneRAnnuelSite(LigneRAnnuelSite $ligneRAnnuelSite): self
    {
        if (!$this->ligneRAnnuelSites->contains($ligneRAnnuelSite)) {
            $this->ligneRAnnuelSites[] = $ligneRAnnuelSite;
            $ligneRAnnuelSite->setRapport($this);
        }

        return $this;
    }

    public function removeLigneRAnnuelSite(LigneRAnnuelSite $ligneRAnnuelSite): self
    {
        if ($this->ligneRAnnuelSites->removeElement($ligneRAnnuelSite)) {
            // set the owning side to null (unless already changed)
            if ($ligneRAnnuelSite->getRapport() === $this) {
                $ligneRAnnuelSite->setRapport(null);
            }
        }

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
}
