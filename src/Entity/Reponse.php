<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReponseRepository::class)]
class Reponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

//     #[ORM\OneToOne(cascade: ['persist', 'remove'])]
//    // #[ORM\ManyToOne(cascade: ['persist', 'remove'])]
// #[ORM\JoinColumn(name: "reclam_id", referencedColumnName: "id", onDelete: "CASCADE")]
//     private ?Reclamation $reclam = null;





//...

#[ORM\OneToOne(targetEntity: Reclamation::class, inversedBy: 'reponse')]
#[ORM\JoinColumn(name: 'id_reclamation', referencedColumnName: 'id', onDelete: 'CASCADE')]
private ?Reclamation $reclamation = null;

public function getReclamation(): ?Reclamation
{
    return $this->reclamation;
}

public function setReclamation(?Reclamation $reclamation): self
{
    $this->reclamation = $reclamation;

    return $this;
}
    #[ORM\Column(length: 255)]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
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

    // public function getReclam(): ?Reclamation
    // {
    //     return $this->reclam;
    // }

    // public function setReclam(?Reclamation $reclam): self
    // {
    //     $this->reclam = $reclam;

    //     return $this;
    // }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
