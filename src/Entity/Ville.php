<?php

namespace App\Entity;

use App\Repository\VilleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=VilleRepository::class)
  * @ORM\Table(name="bill_ville")
   * @UniqueEntity(
 *     fields={"designation"},
 *     errorPath="designation",
 *     message="Ce nom de ville existe déjà dans la base de données."
 * )
 */
class Ville
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true )
     * @Assert\NotBlank
* @Assert\Length(min=3)
     */
    private $designation;

    public function __toString()
    {
        return $this->getDesignation();
    }

    public function getId(): ?int
    {
        return $this->id;
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
}
