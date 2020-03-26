<?php

namespace PromotionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Promotion
 *
 * @ORM\Table(name="promotion", indexes={@ORM\Index(name="fk_reservation_id", columns={"reservation_id"}), @ORM\Index(name="fk_id_user", columns={"user_id"})})
 * @ORM\Entity(repositoryClass="PromotionBundle\Repository\PromotionRepository")
 */
class Promotion
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_promotion", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPromotion;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50, nullable=false)
     */
    private $nom;

    /**
     * @var integer
     *
     * @ORM\Column(name="pourcentage", type="integer", nullable=false)
     */
    private $pourcentage;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut", type="datetime", nullable=false)
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin", type="datetime", nullable=false)
     */
    private $dateFin;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \Reservation
     *
     * @ORM\ManyToOne(targetEntity="Reservation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="reservation_id", referencedColumnName="id_res")
     * })
     */
    private $reservation;

    /**
     * @return int
     */
    public function getIdPromotion()
    {
        return $this->idPromotion;
    }

    /**
     * @param int $idPromotion
     */
    public function setIdPromotion($idPromotion)
    {
        $this->idPromotion = $idPromotion;
    }

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return int
     */
    public function getPourcentage()
    {
        return $this->pourcentage;
    }

    /**
     * @param int $pourcentage
     */
    public function setPourcentage($pourcentage)
    {
        $this->pourcentage = $pourcentage;
    }

    /**
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * @param \DateTime $dateDebut
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;
    }

    /**
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * @param \DateTime $dateFin
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return \User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param \User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \Reservation
     */
    public function getReservation()
    {
        return $this->reservation;
    }

    /**
     * @param \Reservation $reservation
     */
    public function setReservation($reservation)
    {
        $this->reservation = $reservation;
    }


}

