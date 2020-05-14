<?php
        // src/AppBundle/Entity/User.php

        namespace AppBundle\Entity;

        use FOS\UserBundle\Model\User as BaseUser;
        use Doctrine\ORM\Mapping as ORM;
        use Symfony\Component\Validator\Constraints as Assert;

        /**
         * @ORM\Entity
         * @ORM\Table(name="user")
         */
        class User extends BaseUser
        {
            /**
             * @ORM\Id
             * @ORM\Column(type="integer")
             * @ORM\GeneratedValue(strategy="AUTO")
             */
            protected $id;

            public function __construct()
            {
                parent::__construct();
                // your own logic
            }

            /**
             * @var string
             *
             * @ORM\Column(name="firstName", type="string", length=255)
             */
            private $prenom;

            /**
             * @var string
             *
             * @ORM\Column(name="lastName", type="string", length=255)
             */
            private $nom;

            /**
             * @var integer
             *
             * @ORM\Column(name="telephone", type="integer", nullable=false)
             */
            private $telephone;

            /**
             * @var string
             *
             * @ORM\Column(name="adresse", type="string", length=50, nullable=true)
             */
            private $adresse;

            /**
             * @var boolean
             *
             * @ORM\Column(name="etat", type="boolean", nullable=true)
             */
            private $etat;

            /**
             * @var integer
             *
             * @ORM\Column(name="nbr_emp", type="integer", nullable=true)
             */
            private $nbrEmp;

            /** @ORM\Column(type="string", columnDefinition="ENUM('client', 'administrateur', 'chauffeur', 'entreprise')", nullable=true ) */
            private $roleUser;

            public function setRole($roleUser)
            {
                if (!in_array($roleUser, array(self::client, self::administrateur, self::chauffeur, self::entreprise))) {
                    throw new \InvalidArgumentException("Invalid role");
                }
                $this->roleUser = $roleUser;

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
             * @return string
             */
            public function getPrenom()
            {
                return $this->prenom;
            }

            /**
             * @param string $prenom
             */
            public function setPrenom($prenom)
            {
                $this->prenom = $prenom;
            }

            /**
             * @return int
             */
            public function getTelephone()
            {
                return $this->telephone;
            }

            /**
             * @param int $telephone
             */
            public function setTelephone($telephone)
            {
                $this->telephone = $telephone;
            }

            /**
             * @return string
             */
            public function getAdresse()
            {
                return $this->adresse;
            }

            /**
             * @param string $adresse
             */
            public function setAdresse($adresse)
            {
                $this->adresse = $adresse;
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

            /**
             * @return int
             */
            public function getNbrEmp()
            {
                return $this->nbrEmp;
            }

            /**
             * @param int $nbrEmp
             */
            public function setNbrEmp($nbrEmp)
            {
                $this->nbrEmp = $nbrEmp;
            }


        }