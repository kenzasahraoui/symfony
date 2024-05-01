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
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation")
 * @ORM\Entity
 */
class Reservation
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nomparticipant;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prenomparticipant;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Email(message="L'adresse email doit être valide.")
     */
    private $email;

    /**
     * @var int|null
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Length(
     *     min=8,
     *     max=8,
     *     exactMessage="Le numéro de téléphone doit être composé de 8 chiffres."
     * )
     */
    private $numtel;

    /**
     * @var string|null
     *
     * @ORM\Column(name="typeDeParticipant", type="string", length=255, nullable=true)
     * @Assert\Choice(choices={"Etudiant(e)", "Etudiant(e) premium"}, message="Le type de participant doit être 'Etudiant(e)' ou 'Etudiant(e) premium'.")
     */
    private $typedeparticipant;

    /**
     * @var User|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     */
    private $idUser;

    /**
     * @var Evenement|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Evenement")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    private $event;

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
    // Reservation.php

public function getEventNom(): ?string
{
    // Assuming that your Evenement entity has a property called "nom" representing the name of the event
    return $this->event ? $this->event->getNom() : null;
}

}
