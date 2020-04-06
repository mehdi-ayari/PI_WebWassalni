<?php

namespace ReservationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User;


/**
 * Reservation
 *
 * @ORM\Table(name="reservation", indexes={@ORM\Index(name="fk_id_client", columns={"user_id_client"}), @ORM\Index(name="fk_id_chauffeur", columns={"user_id_chauffeur"}), @ORM\Index(name="fk_id_colis", columns={"id_colis"})})
 * @ORM\Entity(repositoryClass="ReservationBundle\Repository\ReservationRepository")
 */
class Reservation
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_res", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="destination", type="string", length=50, nullable=false)
     */
    private $destination;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_reservation", type="datetime", nullable=false)
     */
    private $dateReservation;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     */
    private $prix;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id_chauffeur", referencedColumnName="id", nullable=false)
     * })
     */
    private $userChauffeur;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id_client", referencedColumnName="id", nullable=false)
     * })
     */
    private $userClient;

    /**
     * @var \Colis
     *
     * @ORM\ManyToOne(targetEntity="Colis")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_colis", referencedColumnName="id_colis")
     * })
     */
    private $idColis;


    const taxi = 'Taxi';
    const prive = 'Privée';
    const camion = 'camion';

    /** @ORM\Column(type="string", columnDefinition="ENUM('Taxi', 'Privée','camion')", nullable=false) */
    private $typeReservation;

    /**
     * Reservation constructor.
     */
    public function __construct()
    {
        $this->dateReservation=new \DateTime('now');

    }

    public function setTypeReservation($typeReservation)
    {
        if (!in_array($typeReservation, array(self::taxi, self::prive , self::camion))) {
            throw new \InvalidArgumentException("Invalid typereservation");
        }
        $this->typeReservation = $typeReservation;
    }


    const colis = 'colis';
    const passager = 'passager';


    /** @ORM\Column(type="string", columnDefinition="ENUM('colis', 'passager')",  nullable=false) */
    private $objet;

    public function setObjet($objet)
    {
        if (!in_array($objet, array(self::colis, self::passager))) {
            throw new \InvalidArgumentException("Invalid objet");
        }
        $this->objet = $objet;
    }



    /**
     * @return string
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @return \DateTime
     */
    public function getDateReservation()
    {
        return $this->dateReservation;
    }

    /**
     * @return float
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * @return \User
     */
    public function getUserChauffeur()
    {
        return $this->userChauffeur;
    }

    /**
     * @return \User
     */
    public function getUserClient()
    {
        return $this->userClient;
    }

    /**
     * @param \User $userClient
     */
    public function setUserClient($userClient)
    {
        $this->userClient = $userClient;
    }







    /**
     * @return \Colis
     */
    public function getIdColis()
    {
        return $this->idColis;
    }

    /**
     * @return mixed
     */
    public function getTypeReservation()
    {
        return $this->typeReservation;
    }

    /**
     * @return mixed
     */
    public function getObjet()
    {
        return $this->objet;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }



    /**
     * @param string $destination
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;
    }

    /**
     * @param \DateTime $dateReservation
     */
    public function setDateReservation($dateReservation)
    {
        $this->dateReservation = $dateReservation;
    }

    /**
     * @param float $prix
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;
    }

    /**
     * @param \User $userChauffeur
     */
    public function setUserChauffeur($userChauffeur)
    {
        $this->userChauffeur = $userChauffeur;
    }



    /**
     * @param \Colis $idColis
     */
    public function setIdColis($idColis)
    {
        $this->idColis = $idColis;
    }




}

