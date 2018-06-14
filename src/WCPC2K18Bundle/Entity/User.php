<?php

namespace WCPC2K18Bundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use WCPC2K18Bundle\Entity\Prediction;
use WCPC2K18Bundle\Entity\Leaderboard;
use WCPC2K18Bundle\Entity\Crew;

/**
 * La classe Utilisateur implémente les utilisateurs de l'application de pronostics.
 *
 * @author Sébastien ZINS
 * 
 * @ORM\Entity
 * @ORM\Table("wcpc_users")
 */
class User extends BaseUser{
    
    
    /**
     * @var integer L'identifiant unique de l'utilisateur
     * 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    
    /**
     *
     * @var string Le nom de l'utilisateur
     * 
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    protected $lastName;
    
    /**
     *
     * @var string Le prénom de l'utilisateur
     * 
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    protected $firstName;
    
    /**
     *
     * @var string Le service d'appartenance de l'utilisateur
     * 
     * @ORM\Column(type="string", length=256, nullable=true)
     */
    protected $department;
    
    /**
     *
     * @var ArrayCollection | Prediction [] Les prédictions réalisées par 
     * l'utilisateur.
     * 
     * @ORM\OneToMany(targetEntity="Prediction", mappedBy="user")
     */
    protected $predictions;
    
    /**
     *
     * @var Leaderboard Le tableau des classements de l'utilisateur
     * 
     * @ORM\OneToOne(targetEntity="Leaderboard", mappedBy="user")
     */
    protected $leaderboard;
    
    /**
     *
     * @var Crew L'équipe d'appartenance de l'utilisateur
     * 
     * @ORM\ManyToOne(targetEntity="Crew", inversedBy="users")
     * @ORM\JoinColumn(name="crew_id", referencedColumnName="id")
     */
    protected $crew;
    
    
    /**
     * Constructeur
     */
    public function __construct() {
        parent::__construct();
        
        $this->predictions = new ArrayCollection();
    }
    
    /**
     * Récupère le nom de famille de l'utilisateur
     * 
     * @return string Le nom de famille de l'utilisateur
     */
    public function getLastName() {
        return $this->lastName;
    }

    /**
     * Récupère le prénom de l'utilisateur
     * 
     * @return string Le prénom de l'utilisateur
     */
    public function getFirstName() {
        return $this->firstName;
    }

    /**
     * Récupère le nom du service d'appartenance de l'utilisateur
     * 
     * @return string Le service d'appartenance de l'utilisateur
     */
    public function getDepartment() {
        return $this->department;
    }

    /**
     * Positionne le nom de famille de l'utilisateur
     * 
     * @param string $lastName Le nom à positionner
     */
    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    /**
     * Positionne le prénom de l'utilisateur
     * 
     * @param string $firstName Le prénom à positionner
     */
    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    /**
     * Positionne le service de l'utilisateur
     * 
     * @param string $department Le service à positionner
     */
    public function setDepartment($department) {
        $this->department = $department;
    }
    
    /**
     * Récupère la liste des pronostics réalisés par l'utilisateur
     * 
     * @return ArrayCollection La liste des pronostics réalisés par l'utilisateur
     */
    public function getPredictions() {
        return $this->predictions;
    }

    /**
     * Positionne la liste des pronostics réalisés par l'utilisateur
     * 
     * @param ArrayCollection $predictions La liste des pronostics à positionner
     */
    public function setPredictions(ArrayCollection $predictions) {
        $this->predictions = $predictions;
    }
    
    /**
     * Ajoute un nouveau pronostic à la liste des pronostics réalisés par l'utilisateur
     * 
     * @param Prediction $prediction Le pronostic à rajouter
     */
    public function addPrediction(Prediction $prediction) {
        $this->predictions->add($prediction);
    }
    
    /**
     * Récupère l'équipe d'appartenance de l'utilisateur
     * @return Crew L'équipe de l'utilisateur
     */
    public function getCrew() {
        return $this->crew;
    }

    /**
     * Positionne l'équipe d'appartenance de l'utilisateur
     * @param Crew $crew L'équipe à positionner
     */
    public function setCrew(Crew $crew = null) {
        $this->crew = $crew;
    }
    
    /**
     * Supprime le pronostic $prediction de la liste des pronostics réalisés par
     * l'utilisateur
     * 
     * @param Prediction $prediction Le pronostic à supprimer
     */
    public function removePrediction(Prediction $prediction) {
        $this->predictions->removeElement($prediction);
    }
    
    /**
     * {@inheritDoc}
     */
    public function __toString() {
        return $this->firstName . " " . strtoupper($this->lastName);
    }

}
