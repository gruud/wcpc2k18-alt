<?php

namespace WCPC2K18Bundle\Component;

use \DateInterval;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
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
     *
     * @var User L'utilisateur connecté
     */
    private $user;
    
    /**
     *
     * @var EntityManager Le gestionnaire d'entité Doctrine
     */
    private $manager;
    
    
    /**
     * Constructeur
     * 
     * @param integer $predictionStartDelay La durée en minutes, relativement au début 
     * de la rencontre, avant laquelle l'utilisateur ne peut pas pronostiquer
     * @param integer $predictionEndDelay La durée en minutes, relativement
     * au début de la rencontre, après laquelle l'utilisateur ne peut plus
     * pronostiquer
     */
    public function __construct(EntityManager $manager, TokenStorage $tokenStorage, $predictionStartDelay, $predictionEndDelay) {
        $this->predictionStartDelay = new DateInterval('PT' . $predictionStartDelay . 'S');
        $this->predictionEndDelay = new DateInterval('PT' . $predictionEndDelay . 'S');
        $this->user = $tokenStorage->getToken()->getUser();
        $this->manager = $manager;
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
        return  !$this->gameStillClosed($game) && !$this->gameDeadlinePassed($game);
    }
    
    /**
     * Vérifie si une rencontre est encore fermée aux pronostics car la date 
     * d'ouverture n'est pas encore advenue. 
     * 
     * @param Game $game La rencontre pour laquelle on souhaite faire la vérification.
     * @return boolean VRAI si la rencontre est encore fermée, FAUX sinon. 
     */
    public function gameStillClosed(Game $game) {
        $start = clone $game->getKickoff();
        $now = new \DateTime();
        $start->sub($this->predictionStartDelay);
        
        return $now < $start;
    }
    
    /**
     * Vérifie si la date limite de saisie d'un pronostic est passée. 
     * 
     * @param Game $game La rencontre pour laquelle effectuer cette vérification
     * @return VRAI si la date limite de saisie est passée, FAUX sinon.
     */
    public function gameDeadlinePassed(Game $game) {
        $end = clone $game->getKickoff();
        $now = new \DateTime();

        $end->sub($this->predictionEndDelay);
        
        return $now > $end;
    }
    
    /**
     * Vérifie si l'utilisateur a déjà utilisé son jackpot pour la journée $day.
     * 
     * @param string $day Le nom de la journée considérée
     * @return boolean VRAI si l'utilisateur a déjà utilisé son jackpot pour la
     * journée $day, faux sinon.
     */
    public function jackpotUsedForDay($day) {
        $predictionWithJackpot = $this->manager
                ->getRepository('WCPC2K18Bundle:Prediction')
                ->findUserJackpotForDay($this->user, $day);
        
        return $predictionWithJackpot !== null;
    }
}
