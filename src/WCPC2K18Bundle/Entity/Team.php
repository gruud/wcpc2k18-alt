<?php

namespace WCPC2K18Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * La classe Team implémente une équipe prenant part à la compétition. 
 *
 * @author Sébastien ZINS
 * 
 * @ORM\Entity
 * @ORM\Table(name="wcpc_teams")
 */
class Team {
    
    
    /**
     *
     * @var integer L'identifiant unique de l'équipe
     * 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     *
     * @var string Le nom de l'équipe
     * 
     * @ORM\Column(type="string", length=64)
     */
    private $name;
    
    /**
     *
     * @var ArrayCollection | Game[] Les rencontres jouées à domicile par cette équipe
     * 
     * @ORM\OneToMany(targetEntity="Game", mappedBy="homeTeam")
     */
    private $gamesHome;
    
    /**
     *
     * @var ArrayCollection | Game[] Les rencontres jouées à l'extérieur par cette équipe
     * 
     * @ORM\OneToMany(targetEntity="Game", mappedBy="awayTeam")
     */
    private $gamesAway;
    
    /**
     * Constructeur
     */
    public function __construct() {
        $this->gamesHome = new ArrayCollection();
        $this->gamesAway = new ArrayCollection();
    }
    
    
    /**
     *
     * @var string Le nom abrégé de l'équipe, sur trois caractères
     * 
     * @ORM\Column(type="string", length=3)
     */
    private $abbreviation;
    
    /**
     * Récupère l'identifiant unique de l'équipe
     * 
     * @return integer L'identifiant unique de l'équipe
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Récupère le nom de l'équipe
     * 
     * @return string Le nom de l'équipe
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Récupère l'abbréviation de l'équipe, sur trois lettres
     * 
     * @return string L'abbréviation de l'équipe, sur trois lettres
     */
    public function getAbbreviation() {
        return $this->abbreviation;
    }

    /**
     * Positionne le nom de l'équipe
     * 
     * @param string $name Le nom à positionner
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Positionne l'abbréviation de l'équipe
     * 
     * @param string $abbreviation L'abbréviation à positionner
     */
    public function setAbbreviation($abbreviation) {
        $this->abbreviation = $abbreviation;
    }


    
}
