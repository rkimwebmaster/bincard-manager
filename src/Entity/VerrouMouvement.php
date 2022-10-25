<?php

namespace App\Entity;

use App\Repository\IdentiteRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IdentiteRepository::class)
 * @ORM\Table(name="bill_verrou_mouvement")
 */
class VerrouMouvement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

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
     * @ORM\OneToOne(targetEntity=RMensuelSite::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $rapportMensuel;


    public function __construct(DateTime $date, Site $site, int $mois, int $annee, RMensuelSite $rapportMensuel)
    {
        $this->createdAt=$date;
        $this->site=$site;
        $this->mois=$mois;
        $this->annee=$annee;
        $this->rapportMensuel=$rapportMensuel;

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

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }

    public function getRapportMensuel(): ?RMensuelSite
    {
        return $this->rapportMensuel;
    }

    public function setRapportMensuel(?RMensuelSite $rapportMensuel): self
    {
        $this->rapportMensuel = $rapportMensuel;

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
}
