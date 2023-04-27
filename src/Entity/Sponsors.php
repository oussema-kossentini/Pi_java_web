<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\SponsorsRepository;
/**
 * Sponsors
 *
 * @ORM\Table(name="sponsors")
 * @ORM\Entity
 */
class Sponsors
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_sponsor", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idSponsor;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_sponsor", type="string", length=255, nullable=false)
     */
    #[Assert\NotBlank(message: "the name of the Sponsor is required")]
    private $nomSponsor;

    /**
     * @var string
     *
     * @ORM\Column(name="description_sponsor", type="string", length=255, nullable=false)
     */
    #[Assert\NotBlank(message: "description is required")]
    private $descriptionSponsor;

    public function getIdSponsor(): ?int
    {
        return $this->idSponsor;
    }

    public function getNomSponsor(): ?string
    {
        return $this->nomSponsor;
    }

    public function setNomSponsor(string $nomSponsor): self
    {
        $this->nomSponsor = $nomSponsor;

        return $this;
    }

    public function getDescriptionSponsor(): ?string
    {
        return $this->descriptionSponsor;
    }

    public function setDescriptionSponsor(string $descriptionSponsor): self
    {
        $this->descriptionSponsor = $descriptionSponsor;

        return $this;
    }


}
