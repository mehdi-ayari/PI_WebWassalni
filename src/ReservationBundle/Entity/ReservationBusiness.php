<?php

namespace ReservationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * ReservationBusiness
 *
 * @ORM\Table(name="reservation_business", indexes={@ORM\Index(name="fk_id_entreprise", columns={"user_id_entreprise"})})
 * @ORM\Entity(repositoryClass="ReservationBundle\Repository\ReservationBusinessRepository")
 */
class ReservationBusiness
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_res_business", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idResBusiness;

    /**
     * @var \DateTime
     *@Assert\NotBlank()
     *@Assert\GreaterThanOrEqual("today")
     * @ORM\Column(name="date_depart", type="datetime", nullable=false)
     */
    private $dateDepart;


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
     * @var string
     *
     * @ORM\Column(name="nom_client_entreprise", type="string", length=20, nullable=false)
     */


    private $nomClientEntreprise;

    /**
     * @var string
     *
     * @ORM\Column(name="prenon_client_entreprise", type="string", length=20, nullable=false)
     */
    private $prenonClientEntreprise;

    /**
     * @var boolean
     *
     * @ORM\Column(name="etat", type="boolean", nullable=true)
     */
    private $etat;

    /**
     * @var string
     *
     * @ORM\Column(name="point_depart", type="string", length=50, nullable=false)
     */
    private $pointDepart;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id_entreprise", referencedColumnName="id", nullable=false)
     * })
     */
    private $userEntreprise;

    /**
     * ReservationBusiness constructor.
     */
    public function __construct()
    {
        $this->setDateReservation(new \DateTime('now'));
    }

    /**
     * @return int
     */
    public function getIdResBusiness()
    {
        return $this->idResBusiness;
    }

    /**
     * @param int $idResBusiness
     */
    public function setIdResBusiness($idResBusiness)
    {
        $this->idResBusiness = $idResBusiness;
    }

    /**
     * @return \DateTime
     */
    public function getDateDepart()
    {
        return $this->dateDepart;
    }

    /**
     * @param \DateTime $dateDepart
     */
    public function setDateDepart($dateDepart)
    {
        $this->dateDepart = $dateDepart;
    }

    /**
     * @return string
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @param string $destination
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;
    }

    /**
     * @return \DateTime
     */
    public function getDateReservation()
    {
        return $this->dateReservation;
    }

    /**
     * @param \DateTime $dateReservation
     */
    public function setDateReservation($dateReservation)
    {
        $this->dateReservation = $dateReservation;
    }

    /**
     * @return string
     */
    public function getNomClientEntreprise()
    {
        return $this->nomClientEntreprise;
    }

    /**
     * @param string $nomClientEntreprise
     */
    public function setNomClientEntreprise($nomClientEntreprise)
    {
        $this->nomClientEntreprise = $nomClientEntreprise;
    }

    /**
     * @return string
     */
    public function getPrenonClientEntreprise()
    {
        return $this->prenonClientEntreprise;
    }

    /**
     * @param string $prenonClientEntreprise
     */
    public function setPrenonClientEntreprise($prenonClientEntreprise)
    {
        $this->prenonClientEntreprise = $prenonClientEntreprise;
    }

    /**
     * @return string
     */
    public function getPointDepart()
    {
        return $this->pointDepart;
    }

    /**
     * @param string $pointDepart
     */
    public function setPointDepart($pointDepart)
    {
        $this->pointDepart = $pointDepart;
    }

    /**
     * @return \User
     */
    public function getUserEntreprise()
    {
        return $this->userEntreprise;
    }

    /**
     * @param \User $userEntreprise
     */
    public function setUserEntreprise($userEntreprise)
    {
        $this->userEntreprise = $userEntreprise;
    }

    /**
     * @return bool
     */
    public function isEtat()
    {
        return $this->etat;
    }

    /**
     * @param bool $etat
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;
    }


}
