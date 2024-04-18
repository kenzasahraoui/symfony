<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
class User
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
     * @ORM\Column(name="firstname", type="string", length=255, nullable=false)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255, nullable=false)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="text", length=65535, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var int
     *
     * @ORM\Column(name="type", type="integer", nullable=false)
     */
    private $type;

    /**
     * @var int
     *
     * @ORM\Column(name="connected", type="integer", nullable=false)
     */
    private $connected;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Studentgroup", inversedBy="user")
     * @ORM\JoinTable(name="user_studentgroup",
     *   joinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="student_group_id", referencedColumnName="id")
     *   }
     * )
     */
    private $studentGroup = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->studentGroup = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getConnected(): ?int
    {
        return $this->connected;
    }

    public function setConnected(int $connected): static
    {
        $this->connected = $connected;

        return $this;
    }

    public function getNom(): string
    {
        return $this->getFirstname() . ' ' . $this->getLastname();
    }

    /**
     * @return Collection<int, Studentgroup>
     */
    public function getStudentGroup(): Collection
    {
        return $this->studentGroup;
    }

    public function addStudentGroup(Studentgroup $studentGroup): static
    {
        if (!$this->studentGroup->contains($studentGroup)) {
            $this->studentGroup->add($studentGroup);
        }

        return $this;
    }

    public function removeStudentGroup(Studentgroup $studentGroup): static
    {
        $this->studentGroup->removeElement($studentGroup);

        return $this;
    }
    public function __toString(): string
{
    return $this->getFirstname() . ' ' . $this->getLastname(); // Remplacez par les méthodes appropriées pour le nom et le prénom de l'utilisateur
}


}
