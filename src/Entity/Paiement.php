<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Paiement
 *
 * @ORM\Table(name="paiement", uniqueConstraints={@ORM\UniqueConstraint(name="id_P", columns={"id"})})
 * @ORM\Entity
 */
class Paiement
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse_m", type="string", length=255, nullable=false)
     */
    private $adresseM;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date", type="date", nullable=false)
     * /**
     * @Assert\NotBlank
     * @Assert\Type("\DateTime")
     * @Assert\GreaterThanOrEqual("today")
     */
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(name="CVC", type="integer", nullable=false)
     */
    private $cvc;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdresseM(): ?string
    {
        return $this->adresseM;
    }

    public function setAdresseM(string $adresseM): self
    {
        $this->adresseM = $adresseM;

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

    public function getCvc(): ?int
    {
        return $this->cvc;
    }

    public function setCvc(int $cvc): self
    {
        $this->cvc = $cvc;

        return $this;
    }


}
