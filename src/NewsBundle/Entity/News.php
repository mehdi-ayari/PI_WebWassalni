<?php

namespace NewsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Tchoulom\ViewCounterBundle\Model\ViewCountable;
use Tchoulom\ViewCounterBundle\Entity\ViewCounter;
use http\Client\Curl\User;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * News
 *
 * @ORM\Table(name="news")
 * @ORM\Entity(repositoryClass="NewsBundle\Repository\NewsRepository")
 */
class News implements ViewCountable
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="descr", type="string", length=1000)
     */
    private $descr;



    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     * @Assert\File(maxSize="500k", mimeTypes={"image/jpeg", "image/jpg", "image/png", "image/GIF"})
     */
    private $image;

    /**
     * @ORM\Column(type="text")
     */
    private $introduction;


    /**
     * @return mixed
     */
    public function getIntroduction()
    {
        return $this->introduction;
    }

    /**
     * @param mixed $introduction
     * @return News
     */
    public function setIntroduction($introduction)
    {
        $this->introduction = $introduction;
        return $this;
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return News
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set descr
     *
     * @param string $descr
     *
     * @return News
     */
    public function setDescr($descr)
    {
        $this->descr = $descr;

        return $this;
    }

    /**
     * Get descr
     *
     * @return string
     */
    public function getDescr()
    {
        return $this->descr;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return News
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @ORM\OneToMany(targetEntity="Tchoulom\ViewCounterBundle\Entity\ViewCounter", mappedBy="news")
     */
    protected $viewCounters;

    /**
     * @ORM\Column(name="views", type="integer", nullable=true)
     */
    protected $views = 0;

    /**
     * Sets $views
     *
     * @param integer $views
     *
     * @return $this
     */
    public function setViews($views)
    {
        $this->views = $views;

        return $this;
    }

    /**
     * Gets $views
     *
     * @return integer
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * Get $viewCounters
     *
     * @return Collection
     */
    public function getViewCounters()
    {
        return $this->viewCounters;
    }

    /**
     * Add $viewCounter
     *
     * @param ViewCounter $viewCounter
     *
     * @return $this
     */
    public function addViewCounter(ViewCounter $viewCounter)
    {
        $this->viewCounters[] = $viewCounter;

        return $this;
    }

    /**
     * Remove $viewCounter
     *
     * @param ViewCounter $viewCounter
     */
    public function removeViewCounter(ViewCounter $viewCounter)
    {
        $this->viewCounters->removeElement($viewCounter);
    }

    public function __construct()
    {
        $this->views = new ArrayCollection();
        $this->reactions = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->viewCounters = new ArrayCollection();

    }



}



