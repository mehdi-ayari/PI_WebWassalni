<?php

namespace ReservationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Colis
 *
 * @ORM\Table(name="colis")
 * @ORM\Entity
 */
class Colis
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_colis", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idColis;

    /**
     * @var float
     *
     * @ORM\Column(name="poids", type="float", precision=10, scale=0, nullable=false)
     */
    private $poids;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="string", length=100, nullable=false)
     */
    private $contenu;

    /**
     * @return int
     */
    public function getIdColis()
    {
        return $this->idColis;
    }

    /**
     * @param int $idColis
     */
    public function setIdColis($idColis)
    {
        $this->idColis = $idColis;
    }

    /**
     * @return float
     */
    public function getPoids()
    {
        return $this->poids;
    }

    /**
     * @param float $poids
     */
    public function setPoids($poids)
    {
        $this->poids = $poids;
    }

    /**
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * @param string $contenu
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;
    }


}

