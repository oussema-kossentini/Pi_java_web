<?php

namespace App\Entity;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\OffreRepository;
/**
 * Offre
 *
 * @ORM\Table(name="offre", indexes={@ORM\Index(name="fk_sponsors_offre", columns={"id_sponsor"})})
 * @ORM\Entity
 */
class Offre
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_offre", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idOffre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut", type="date", nullable=false)
     */
    #[Assert\NotBlank(message: "expiration date is required")]
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin", type="date", nullable=false)
     */
   
    #[Assert\NotBlank(message: "expiration date is required")]
    #[Assert\GreaterThan(propertyPath:"dateDebut", message:"expiration date must be greater than start date")]
    private $dateFin;

    /**
     * @var int
     *
     * @ORM\Column(name="remise", type="integer", nullable=false)
     */
    #[Assert\NotBlank(message: "Remise is required")]
    #[Assert\Range(min:0,max:100,notInRangeMessage:"Remise must be between {{ min }}% and {{ max }}%")]
    private $remise;

    /**
     * @var \Sponsors
     *
     * @ORM\ManyToOne(targetEntity="Sponsors")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_sponsor", referencedColumnName="id_sponsor")
     * })
     */
    private $sponsor;

    public function getIdOffre(): ?int
    {
        return $this->idOffre;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getRemise(): ?int
    {
        return $this->remise;
    }

    public function setRemise(int $remise): self
    {
        $this->remise = $remise;

        return $this;
    }
 /**
     * Get the value of idSponsor
     *
     * @return \Sponsors
     */
    public function getSponsor()
    {
        return $this->sponsor;
    }

    public function setSponsor(?Sponsors $sponsor): self
    {
        $this->sponsor = $sponsor;

        return $this;
    }
     /**
     * Get the name of the sponsor
     *
     * @return string|null
     */
    public function getnomSponsor(): ?string
    {
        return $this->sponsor ? $this->sponsor->getNomSponsor() : null;
    }
    public function getdescriptionSponsor(): ?string
    {
        return $this->sponsor ? $this->sponsor->getDescriptionSponsor() : null;
    }

}
