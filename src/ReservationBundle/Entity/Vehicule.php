<?php

namespace ReservationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vehicule
 *
 * @ORM\Table(name="vehicule", indexes={@ORM\Index(name="fk_chauffeur_id", columns={"id_chauffeur"})})
 * @ORM\Entity
 */
class Vehicule
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_vehicule", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idVehicule;

    /**
     * @var string
     *
     * @ORM\Column(name="licence", type="blob", length=65535, nullable=false)
     */
    private $licence;

    /**
     * @var string
     *
     * @ORM\Column(name="permis", type="blob", length=65535, nullable=false)
     */
    private $permis;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=50, nullable=false)
     */
    private $photo;

    /**
     * @var string
     *
     * @ORM\Column(name="marque", type="string", length=50, nullable=false)
     */
    private $marque;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_chauffeur", referencedColumnName="id")
     * })
     */
    private $idChauffeur;

    /**
     * @return int
     */
    public function getIdVehicule()
    {
        return $this->idVehicule;
    }

    /**
     * @param int $idVehicule
     */
    public function setIdVehicule($idVehicule)
    {
        $this->idVehicule = $idVehicule;
    }

    /**
     * @return string
     */
    public function getLicence()
    {
        return $this->licence;
    }

    /**
     * @param string $licence
     */
    public function setLicence($licence)
    {
        $this->licence = $licence;
    }

    /**
     * @return string
     */
    public function getPermis()
    {
        return $this->permis;
    }

    /**
     * @param string $permis
     */
    public function setPermis($permis)
    {
        $this->permis = $permis;
    }

    /**
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param string $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    /**
     * @return string
     */
    public function getMarque()
    {
        return $this->marque;
    }

    /**
     * @param string $marque
     */
    public function setMarque($marque)
    {
        $this->marque = $marque;
    }

    /**
     * @return \User
     */
    public function getIdChauffeur()
    {
        return $this->idChauffeur;
    }

    /**
     * @param \User $idChauffeur
     */
    public function setIdChauffeur($idChauffeur)
    {
        $this->idChauffeur = $idChauffeur;
    }


}

