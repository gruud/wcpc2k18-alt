<?php


namespace WCPC2K18Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \DateTime;
use WCPC2K18Bundle\Entity\Team;

/**
 * La classe Game implémente une rencontre de la coupe du monde, sur laquelle les
 * joueurs vont pouvoir pronostiquer. 
 *
 * @author Sébastien ZINS
 * 
 * @ORM\Entity
 * @ORM\Table(name="wcpc_games")
 */
class Game {
    
    
    /**
     * Type de rencontre : régulière; se termine au bout du temps réglementaire,
     * et peut finir sur un match nul
     */
    const TYPE_REGULAR = 0;
    
    /**
     * Type de rencontre:  éliminatoire; il y a forcément un vainqueur, avec 
     * prolongations et tirs aux buts si nécessaire. 
     */
    const TYPE_PLAYOFF = 1;
    
    /**
     *
     * @var integer L'identifiant unique de la rencontre
     * 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     *
     * @var DateTime La date du coup d'envoi de la rencontre, heure universelle
     * 
     * @ORM\Column(name="kickoff", type="datetime")
     */
    private $kickoff;
    
    /**
     *
     * @var integer Le type de la rencontre. UNe rencontre peut être de deux types : 
     *  - Game::TYPE_REGULAR : Rencontre classique se terminant au bout de 90 minutes
     *  - Game::TYPE_PLAYOFF : Rencontre élminiatoire pouvant aller jusqu'aux tirs aux buts
     */
    private $type = self::TYPE_REGULAR;
    
    /**
     *
     * @var Team L'équipe jouant à domicile (première équipe décrite dans le 
     * détail de la rencontre)
     * 
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="gamesHome");
     * @ORM\JoinColumn(name="team_home_id", referencedColumnName="id")
     */
    private $homeTeam;
    
    /**
     *
     * @var Team L'équipe jouant à l'extérieur (seconde équipe décrite dans le 
     * détail de la rencontre).
     * 
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="gamesAway")
     * @ORM\JoinColumn(name="team_away_id", referencedColumnName="id")
     */
    private $awayTeam;
    
    /**
     *
     * @var integer Le nombre de buts marqués par l'équipe jouant à domicile.
     * 
     * @ORM\Column(type="integer", nullable=true)
     */
    private $goalsHome;
    
    /**
     *
     * @var integer Le nombre de buts marqués par l'équipe jouant à l'extérieur 
     * 
     * @ORM\Column(type="integer", nullable=true)
     */
    private $goalsAway;
    
    /**
     *
     * @var boolean Indique sur des prolongations ont été jouées pour ce match 
     * 
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $extraTime = false;
    
    /**
     *
     * @var integer Le nombre de pénalties marqués par l'équipe à domicile
     * 
     * @ORM\Column(type="integer", nullable=true)
     */
    private $penaltiesHome;
    
    /**
     *
     * @var integer Le nombre de penalties marqués par l'équipe à l'extérieur
     * 
     * @ORM\COlumn(type="integer", nullable=true)
     */
    private $penaltiesAway;
    
    
    /**
     * Récupère l'identifiant unique de la rencontre
     * @return type
     */
    public function getId() {
        return $this->id;
    }

    public function getKickoff(): DateTime {
        return $this->kickoff;
    }

    public function getType() {
        return $this->type;
    }

    public function getHomeTeam(): Team {
        return $this->homeTeam;
    }

    public function getAwayTeam(): Team {
        return $this->awayTeam;
    }

    public function getGoalsHome() {
        return $this->goalsHome;
    }

    public function getGoalsAway() {
        return $this->goalsAway;
    }

    public function getExtraTime() {
        return $this->extraTime;
    }

    public function getPenaltiesHome() {
        return $this->penaltiesHome;
    }

    public function getPenaltiesAway() {
        return $this->penaltiesAway;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setKickoff(DateTime $kickoff) {
        $this->kickoff = $kickoff;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function setHomeTeam(Team $homeTeam) {
        $this->homeTeam = $homeTeam;
    }

    public function setAwayTeam(Team $awayTeam) {
        $this->awayTeam = $awayTeam;
    }

    public function setGoalsHome($goalsHome) {
        $this->goalsHome = $goalsHome;
    }

    public function setGoalsAway($goalsAway) {
        $this->goalsAway = $goalsAway;
    }

    public function setExtraTime($extraTime) {
        $this->extraTime = $extraTime;
    }

    public function setPenaltiesHome($penaltiesHome) {
        $this->penaltiesHome = $penaltiesHome;
    }

    public function setPenaltiesAway($penaltiesAway) {
        $this->penaltiesAway = $penaltiesAway;
    }

}
