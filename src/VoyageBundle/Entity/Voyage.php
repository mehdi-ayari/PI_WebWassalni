<?php

namespace VoyageBundle\Entity;
use ReservationBundle\Entity\Reservation;
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
     * @ORM\Column(name="distance", type="float", precision=10, scale=0, nullable=true)
     */
    private $distance;


    /**
     * @var string
     *
     * @ORM\Column(name="date_voyage", type="datetime", nullable=false)
     */
    private $dateVoyage;

    /**
     * @var bool
     *
     * @ORM\Column(name="annul", type="boolean", nullable=true)
     */
    private $annul;

    /**
     * @var \Reservation
     *
     * @ORM\ManyToOne(targetEntity="ReservationBundle\Entity\Reservation")
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
     * @return datetime
     */
    public function getDateVoyage()
    {
        return $this->dateVoyage;
    }

    /**
     * @param datetime $dateVoyage
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

    /**
     * @param bool $annul
     */
    public function setAnnul($annul)
    {
        $this->annul = $annul;
    }

    /**
     * @return bool
     */
    public function isAnnul()
    {
        return $this->annul;
    }



}

