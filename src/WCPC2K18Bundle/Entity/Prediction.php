<?php


namespace WCPC2K18Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use WCPC2K18Bundle\Entity\User;
use WCPC2K18Bundle\Entity\Game;

/**
 * La classe Prediction implémente le pronostic d'un joueur pour une rencontre
 *
 * @author Sébastien ZINS
 * 
 * @ORM\Entity
 * @ORM\Table(name="wcpc_predictions")
 */
class Prediction {
    
    /**
     *
     * @var integer L'identifiant unique du pronostic
     * 
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     *
     * @var integer Le nombre de buts pronostiqués pour l'équipe à domicile
     * 
     * @ORM\Column(type="integer")
     */
    private $goalsHome;
    
    /**
     *
     * @var integer Le nombre de buts pronostiqués pour l'équipe à l'extérieur
     * 
     * @ORM\Column(type="integer")
     */
    private $goalsAway;
    
    /**
     *
     * @var boolean Indique si le marqueur de bonus a été positionné pour le 
     * pronostic ou non
     * 
     * @ORM\Column(type="boolean")
     */
    private $jackpot = false;
    
    /**
     *
     * @var User L'utilisateur réalisant le pronostic
     * 
     * @ORM\ManyToOne(targetEntity="User", inversedBy="predictions")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    
    /**
     *
     * @var Game La rencontre pour laquelle le pronostic est réalisé
     * 
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="predictions")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id")
     */
    private $game;
    
    
    /**
     * Constructeur
     */
    public function __construct() {
        
    }
    
    /**
     * Récupère l'identifiant unique du pronostic
     * 
     * @return integer L'identifiant unique du pronostic
     */
    public function getId() {
        return $this->id;
    }

    /**
     * 
     * @return type
     */
    public function getGoalsHome() {
        return $this->goalsHome;
    }

    public function getGoalsAway() {
        return $this->goalsAway;
    }

    public function getJackpot() {
        return $this->jackpot;
    }

    public function getUser(): User {
        return $this->user;
    }

    public function getGame(): Game {
        return $this->game;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setGoalsHome($goalsHome) {
        $this->goalsHome = $goalsHome;
    }

    public function setGoalsAway($goalsAway) {
        $this->goalsAway = $goalsAway;
    }

    public function setJackpot($jackpot) {
        $this->jackpot = $jackpot;
    }

    public function setUser(User $user) {
        $this->user = $user;
    }

    public function setGame(Game $game) {
        $this->game = $game;
    }
}