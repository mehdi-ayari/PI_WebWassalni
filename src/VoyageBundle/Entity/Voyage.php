<?php

namespace VoyageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Voyage
 *
 * @ORM\Table(name="voyage", indexes={@ORM\Index(name="fk_id_res", columns={"reservation_id_res"})})
 * @ORM\Entity(repositoryClass="VoyageBundle\Repository\VoyageRepository")
 */
class Voyage
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_voyage", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idVoyage;

    /**
     * @var float
     *
     * @ORM\Column(name="distance", type="float", precision=10, scale=0, nullable=false)
     */
    private $distance;

    /**
     * @var string
     *
     * @ORM\Column(name="date_voyage", type="string", length=20, nullable=false)
     */
    private $dateVoyage;

    /**
     * @var \Reservation
     *
     * @ORM\ManyToOne(targetEntity="Reservation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="reservation_id_res", referencedColumnName="id_res")
     * })
     */
    private $reservationRes;

    /**
     * @return int
     */
    public function getIdVoyage()
    {
        return $this->idVoyage;
    }

    /**
     * @param int $idVoyage
     */
    public function setIdVoyage($idVoyage)
    {
        $this->idVoyage = $idVoyage;
    }

    /**
     * @return float
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * @param float $distance
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;
    }

    /**
     * @return string
     */
    public function getDateVoyage()
    {
        return $this->dateVoyage;
    }

    /**
     * @param string $dateVoyage
     */
    public function setDateVoyage($dateVoyage)
    {
        $this->dateVoyage = $dateVoyage;
    }

    /**
     * @return \Reservation
     */
    public function getReservationRes()
    {
        return $this->reservationRes;
    }

    /**
     * @param \Reservation $reservationRes
     */
    public function setReservationRes($reservationRes)
    {
        $this->reservationRes = $reservationRes;
    }


}

