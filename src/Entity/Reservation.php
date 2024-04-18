<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use App\Entity\User;
use App\Entity\Evenement;
use Doctrine\ORM\Mapping as ORM;

#[Entity(tableName: "reservation")]
class Reservation
{
  #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private ?int $id;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $nomparticipant;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $prenomparticipant;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $email;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $numtel;

    #[ORM\Column(type: "string", length: 255, nullable: true, name: "typeDeParticipant")]
    private ?string $typedeparticipant;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "id_user", referencedColumnName: "id")]
    private ?User $idUser = null;

    #[ORM\ManyToOne(targetEntity: Evenement::class)]
    #[ORM\JoinColumn(name: "event_id", referencedColumnName: "id")]
    private ?Evenement $event = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomparticipant(): ?string
    {
        return $this->nomparticipant;
    }

    public function setNomparticipant(?string $nomparticipant): self
    {
        $this->nomparticipant = $nomparticipant;

        return $this;
    }

    public function getPrenomparticipant(): ?string
    {
        return $this->prenomparticipant;
    }

    public function setPrenomparticipant(?string $prenomparticipant): self
    {
        $this->prenomparticipant = $prenomparticipant;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getNumtel(): ?int
    {
        return $this->numtel;
    }

    public function setNumtel(?int $numtel): self
    {
        $this->numtel = $numtel;

        return $this;
    }

    public function getTypedeparticipant(): ?string
    {
        return $this->typedeparticipant;
    }

    public function setTypedeparticipant(?string $typedeparticipant): self
    {
        $this->typedeparticipant = $typedeparticipant;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getEvent(): ?Evenement
    {
        return $this->event;
    }

    public function setEvent(?Evenement $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getEventNom(): ?string
    {
        return $this->event ? $this->event->getNom() : null;
    }
}
