<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\Type(type: "integer", message: "The value {{ value }} is not a valid {{ type }}.")]
    private ?int $cin = null;

    #[ORM\Column(length: 255)]
    #[Assert\Regex(pattern: '/^[a-zA-Z ]+$/', message: "The value {{ value }} is not a valid {{ type }}.")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\Regex(pattern: '/^[a-zA-Z ]+$/', message: "The value {{ value }} is not a valid {{ type }}.")]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    #[Assert\Regex(pattern: '/^[a-zA-Z ]+$/', message: "The value {{ value }} is not a valid {{ type }}.")]
    private ?string $ville = null;

    #[ORM\Column]
    #[Assert\Type(type: "integer", message: "The value {{ value }} is not a valid {{ type }}.")]
    private ?int $num_tel = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse_m = null;

    #[ORM\Column]
    #[Assert\Type(type: "\DateTimeImmutable", message: "The value {{ value }} is not a valid {{ type }}.")]
    private ?\DateTimeImmutable $date_livraison = null;

    #[ORM\Column(length: 255)]
    #[Assert\Regex(pattern: '/^[a-zA-Z ]+$/', message: "The value {{ value }} is not a valid {{ type }}.")]
    private ?string $type_produit = null;

    #[ORM\Column(length: 255)]
    #[Assert\Regex(pattern: '/^[a-zA-Z ]+$/', message: "The value {{ value }} is not a valid {{ type }}.")]
    private ?string $lieu_depart = null;

    #[ORM\Column(length: 255)]
    #[Assert\Regex(pattern: '/^[a-zA-Z ]+$/', message: "The value {{ value }} is not a valid {{ type }}.")]
    private ?string $lieu_arrivee = null;

    #[ORM\Column]
    #[Assert\Type(type: "integer", message: "The value {{ value }} is not a valid {{ type }}.")]
    private ?int $poids = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Drone $drone = null;

    // getters and setters ...

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

    public function getNumTel(): ?int
    {
        return $this->num_tel;
    }

    public function setNumTel(int $num_tel): self
    {
        $this->num_tel = $num_tel;

        return $this;
    }

    public function getAdresseM(): ?string
    {
        return $this->adresse_m;
    }

    public function setAdresseM(string $adresse_m): self
    {
        $this->adresse_m = $adresse_m;

        return $this;
    }

    public function getDateLivraison(): ?\DateTimeImmutable
    {
        return $this->date_livraison;
    }

    public function setDateLivraison(\DateTimeImmutable $date_livraison): self
    {
        $this->date_livraison = $date_livraison;

        return $this;
    }

    public function getTypeProduit(): ?string
    {
        return $this->type_produit;
    }

    public function setTypeProduit(string $type_produit): self
    {
        $this->type_produit = $type_produit;

        return $this;
    }

    public function getLieuDepart(): ?string
    {
        return $this->lieu_depart;
    }

    public function setLieuDepart(string $lieu_depart): self
    {
        $this->lieu_depart = $lieu_depart;

        return $this;
    }

    public function getLieuArrivee(): ?string
    {
        return $this->lieu_arrivee;
    }

    public function setLieuArrivee(string $lieu_arrivee): self
    {
        $this->lieu_arrivee = $lieu_arrivee;

        return $this;
    }

    public function getPoids(): ?int
    {
        return $this->poids;
    }

    public function setPoids(int $poids): self
    {
        $this->poids = $poids;

        return $this;
    }

    public function getDrone(): ?Drone
    {
        return $this->drone;
    }

    public function setDrone(?Drone $drone): self
    {
        $this->drone = $drone;

        return $this;
    }
}
