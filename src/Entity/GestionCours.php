<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GestionCours
 *
 * @ORM\Table(name="gestion_cours", indexes={@ORM\Index(name="fk_numero", columns={"numero_biblio"})})
 * @ORM\Entity
 */
class GestionCours
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
     * @ORM\Column(name="titre", type="string", length=255, nullable=false)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="pdf", type="string", length=255, nullable=false)
     */
    private $pdf;

    /**
     * @var string
     *
     * @ORM\Column(name="video", type="string", length=255, nullable=false)
     */
    private $video;

    /**
     * @var int
     *
     * @ORM\Column(name="nombre_pages", type="integer", nullable=false)
     */
    private $nombrePages;

    /**
     * @var \Bibliotheque
     *
     * @ORM\ManyToOne(targetEntity="Bibliotheque")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="numero_biblio", referencedColumnName="id")
     * })
     */
    private $numeroBiblio;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getPdf(): ?string
    {
        return $this->pdf;
    }

    public function setPdf(string $pdf): static
    {
        $this->pdf = $pdf;

        return $this;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(string $video): static
    {
        $this->video = $video;

        return $this;
    }

    public function getNombrePages(): ?int
    {
        return $this->nombrePages;
    }

    public function setNombrePages(int $nombrePages): static
    {
        $this->nombrePages = $nombrePages;

        return $this;
    }

    public function getNumeroBiblio(): ?Bibliotheque
    {
        return $this->numeroBiblio;
    }

    public function setNumeroBiblio(?Bibliotheque $numeroBiblio): static
    {
        $this->numeroBiblio = $numeroBiblio;

        return $this;
    }


}
