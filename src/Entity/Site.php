<?php

namespace App\Entity;

use App\Repository\SiteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SiteRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="bill_site")
 * @UniqueEntity(
 *     fields={"designation"},
 *     errorPath="designation",
 *     message="Ce nom est déja utilisé pour un autre site."
 * )
 */
class Site
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity=Ville::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $ville;

    /**
     * @ORM\OneToOne(targetEntity=Client::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $siteClient;

    /**
     * @ORM\Column(type="string", length=255, unique=true )
     * @Assert\NotBlank
     * @Assert\Length(min=3)
     */
    private $designation;

    /**
     * @ORM\Column(type="string", length=255, unique=true )
     */
    private $code;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isWarehouse;

    /**
     * @ORM\Column(type="integer")
     */
    private $validationAttendu;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->validationAttendu = 0;
        $this->code = strtoupper(uniqid('STE-'));
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->setIsWarehouse(false);
    }

    /**
     * @ORM\PrePersist
     */
    public function construireClient()
    {
        if($this->getIsWarehouse()){
            $this->setCode(strtoupper(uniqid('WHS-')));
        }
        $client = new Client();
        $client->setVille($this->getVille());
        $client->setCode($this->getCode());
        $client->setNoms($this->getDesignation());
        $client->setIsSite(true);
        $client->setTelephone($this->getTelephone());
        $client->setAdresse($this->getAdresse());
        $client->setEmail($this->getEmail());
        $this->setSiteClient($client);

    }

    public function __toString()
    {
        return $this->getDesignation();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentite(): ?Identite
    {
        return $this->identite;
    }

    public function setIdentite(?Identite $identite): self
    {
        $this->identite = $identite;

        return $this;
    }

    public function getSiteClient(): ?Client
    {
        return $this->siteClient;
    }

    public function setSiteClient(Client $siteClient): self
    {
        $this->siteClient = $siteClient;

        return $this;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

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

    public function getIsWarehouse(): ?bool
    {
        return $this->isWarehouse;
    }

    public function setIsWarehouse(bool $isWarehouse): self
    {
        $this->isWarehouse = $isWarehouse;

        return $this;
    }

    public function getValidationAttendu(): ?int
    {
        return $this->validationAttendu;
    }

    public function setValidationAttendu(int $validationAttendu): self
    {
        $this->validationAttendu = $validationAttendu;

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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): self
    {
        $this->ville = $ville;

        return $this;
    }
}
