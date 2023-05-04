<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Facture
 *
 * @ORM\Table(name="facture", uniqueConstraints={@ORM\UniqueConstraint(name="id_F", columns={"id"})})
 * @ORM\Entity
 */
class Facture
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * 
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="Cin", type="integer", nullable=false)
     * /**
     * @Assert\NotBlank(
     * message="Le champs est vide") 
     * @Assert\Regex(
     *     pattern="/^\d{8}$/",
     *     message="Le champ Cin doit contenir exactement 8 chiffres"
     * )
     */
    private $cin;

    /**
     * @var string
     *
     * @ORM\Column(name="Nom", type="string", length=100, nullable=false)
     * /**
     * @Assert\NotBlank(
     * message="Le champs est vide")
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z]*$/",
     *     message="Nom should contain only letters"
     * )
     */
    
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="Prenom", type="string", length=100, nullable=false)
     *  @Assert\NotBlank(
     * message="Le champs est vide")
     * /**
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z]*$/",
     *     message="Prenom should contain only letters"
     * )
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="Ville", type="string", length=100, nullable=false)
     *  @Assert\NotBlank(
     * message="Le champs est vide")
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z]*$/",
     *     message="Tu dois choisir une ville"
     * )
     */
    private $ville;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date", type="date", nullable=false)
     * /**
     * @Assert\NotBlank(
     * message="Le champs est vide")
     * @Assert\Type("\DateTime")
     * @Assert\GreaterThanOrEqual("today")
     */
    
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(name="Prix", type="integer", nullable=false)
     * @Assert\NotBlank(
     * message="Le champs est vide")
     */
    private $prix;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Status", type="string", length=20, nullable=true)
     * @Assert\NotBlank(
     * message="Le champs est vide")
     */
    private $status;
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'iduser')]
    private ?User $User = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCin(): ?int
    {
        return $this->cin;
    }

    public function setCin(int $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

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

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    
    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }


}
