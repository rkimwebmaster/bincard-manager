<?php

namespace App\Entity;

use App\Repository\ControleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ControleRepository::class)
  * @ORM\Table(name="bill_controle")
 */
class Controle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Site::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $site;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\Column(type="text", nullable=false)
          * @Assert\NotBlank
     */
    private $observationFinale;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isValidee;

    /**
     * @ORM\OneToMany(targetEntity=LigneControle::class, mappedBy="controle", orphanRemoval=true,cascade={"persist"})
     */
    private $ligneControles;

    public function __construct()
    {
        $this->ligneControles = new ArrayCollection();
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
        $this->code= strtoupper(uniqid('CTR-'));
        $this->setIsValidee(false);
        $this->setDate(new \DateTime());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getObservationFinale(): ?string
    {
        return $this->observationFinale;
    }

    public function setObservationFinale(?string $observationFinale): self
    {
        $this->observationFinale = $observationFinale;

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

    public function getIsValidee(): ?bool
    {
        return $this->isValidee;
    }

    public function setIsValidee(bool $isValidee): self
    {
        $this->isValidee = $isValidee;

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
            $ligneControle->setControle($this);
        }

        return $this;
    }

    public function removeLigneControle(LigneControle $ligneControle): self
    {
        if ($this->ligneControles->removeElement($ligneControle)) {
            // set the owning side to null (unless already changed)
            if ($ligneControle->getControle() === $this) {
                $ligneControle->setControle(null);
            }
        }

        return $this;
    }
}
