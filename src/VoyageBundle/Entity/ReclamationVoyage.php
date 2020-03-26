<?php

namespace VoyageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReclamationVoyage
 *
 * @ORM\Table(name="reclamation_voyage", indexes={@ORM\Index(name="fk_id_voyage", columns={"id_voy"})})
 * @ORM\Entity
 */
class ReclamationVoyage
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_reclamation_voyage", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idReclamationVoyage;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=50, nullable=false)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="string", length=255, nullable=false)
     */
    private $commentaire;

    /**
     * @var \Voyage
     *
     * @ORM\ManyToOne(targetEntity="Voyage")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_voy", referencedColumnName="id_voyage")
     * })
     */
    private $idVoy;

    /**
     * @return int
     */
    public function getIdReclamationVoyage()
    {
        return $this->idReclamationVoyage;
    }

    /**
     * @param int $idReclamationVoyage
     */
    public function setIdReclamationVoyage($idReclamationVoyage)
    {
        $this->idReclamationVoyage = $idReclamationVoyage;
    }

    /**
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * @param string $titre
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    }

    /**
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * @param string $commentaire
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;
    }

    /**
     * @return \Voyage
     */
    public function getIdVoy()
    {
        return $this->idVoy;
    }

    /**
     * @param \Voyage $idVoy
     */
    public function setIdVoy($idVoy)
    {
        $this->idVoy = $idVoy;
    }


}

