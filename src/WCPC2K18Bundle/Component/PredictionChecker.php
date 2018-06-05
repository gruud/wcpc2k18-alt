<?php

namespace WCPC2K18Bundle\Component;

use \DateInterval;
use WCPC2K18Bundle\Entity\User;
use WCPC2K18Bundle\Entity\Game;

/**
 * La classe PredictionChecker implémente le service responsable de la vérification
 * de la faisabilité et de la validité des pronostics réalisés par un joueur. 
 *
 * @author Sébastien ZINS
 */
class PredictionChecker {
    
    /**
     *
     * @var DateInterval La durée en minute, relativement au début de la rencontre,
     * avant laquelle l'utilisateur ne peut pas pronostiquer
     */
    private $predictionStartDelay;
    
    /**
     *
     * @var DateInterval La temps en minutes séparant l'utilisateur du début de la 
     * rencontre, à partir duquel il ne peut plus pronostiquer le score. 
     */
    private $predictionEndDelay;
    
    
    /**
     * Constructeur
     * 
     * @param integer $predictionStartDelay La durée en minutes, relativement au début 
     * de la rencontre, avant laquelle l'utilisateur ne peut pas pronostiquer
     * @param integer $predictionEndDelay La durée en minutes, relativement
     * au début de la rencontre, après laquelle l'utilisateur ne peut plus
     * pronostiquer
     */
    public function __construct($predictionStartDelay, $predictionEndDelay) {
        $this->predictionStartDelay = new DateInterval('PT' . $predictionStartDelay . 'S');
        $this->predictionEndDelay = new DateInterval('PT' . $predictionEndDelay . 'S');
    }
    
    /**
     * Vérifie un utilisateur peut effectuer un pronostic pour la 
     * rencontre $game. Un utilisateur peut pronostiquer une rencontre si 
     * le match est ouvert aux pronostics, i.e. si la date limite d'ouverture 
     * est passé, etsi la date de clôture n'est pas encore advenue. 
     * Les dates d'ouvertures et de clôtures sont calculées relativement à la
     * date de la rencontre en utilisant des intervalles prédéfinis 
     * $predictionStartDelay (pour l'ouverture) et $predictionEndDelay (pour la 
     * clôture). 
     * 
     * @param Game $game La rencontre sur laquelle porte le pronostic
     * 
     * @return TRUE si l'utilisateur peut pronostiquer la rencontre, FALSE sinon.
     */
    public function canPredict(Game $game) {
        
        $start = clone $game->getKickoff();
        $end = clone $game->getKickoff();
        $now = new \DateTime();
        
        $start->sub($this->predictionStartDelay);
        $end->sub($this->predictionEndDelay);
        
        return $now > $start && $now < $end;
    }
}
