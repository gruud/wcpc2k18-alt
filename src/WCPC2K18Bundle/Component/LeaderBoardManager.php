<?php


namespace WCPC2K18Bundle\Component;


use Doctrine\ORM\EntityManager;
use WCPC2K18Bundle\Entity\Leaderboard;
use WCPC2K18Bundle\Entity\Game;
use WCPC2K18Bundle\Entity\Prediction;
use WCPC2K18Bundle\Component\LeaderboardRule;

/**
 * La classe LeaderBoardManager implémente le service de gestion des classements.
 * Il est responsable du calcul et de la mise à jour des classements à partir
 * des résultats entrés
 *
 * @author Sébastien ZINS
 * @todo Remplacer les constantes par des paramètres applicatifs injectés dans
 * le service
 */
class LeaderBoardManager {
    
    
    /**
     *
     * @var EntityManager Le gestionnaire d'entités Doctrine
     */
    private $manager;
    
    public function __construct(EntityManager $manager) {
        $this->manager = $manager;
    }
    
    /**
     * Initialise le classement : supprime toutes les entrées existantes, et 
     * crée de nouvelles entrées vides.
     */
    public function initLeaderboard() {
        
       
        $items = $this->manager->getRepository('WCPC2K18Bundle:Leaderboard')->findAll();
        foreach ($items as $item) {
            $this->manager->remove($item);
        }
        
        
        $this->manager->flush();

        // Nettoyage du manager pour évite qu'il ne s'emmêle les pinceaux entre
        // les vieilles entitiés gérées (et supprimées) et les nouvelles 
        // créées.
        //cf. https://stackoverflow.com/questions/18215975/doctrine-a-new-entity-was-found-through-the-relationship
        $this->manager->clear();
        
        $users = $this->manager->getRepository('WCPC2K18Bundle:User')->findAll();
        foreach ($users as $user) {
            $lbItem = new Leaderboard();
            $lbItem->setPoints(0);
            $lbItem->setUser($user);
            $this->manager->persist($lbItem);
            $this->manager->flush();
        }
    }
    
    
    
    /**
     * Recalcule l'ensemble du classement général à partir de toutes les rencontres
     * et pronostics saisis. Cette méthode est accessible depuis la ligne de 
     * commande wcpc:leaderboard:compute
     */
    public function computeLeaderboard() {
        $this->initLeaderboard();
        $games = $this->manager->getRepository('WCPC2K18Bundle:Game')
                ->findForLeaderboardCalculation();
        
        $leaderboard = $this->manager->getRepository('WCPC2K18Bundle:Leaderboard')
                ->findIndexedByUserIdArray();
        
        echo "Nombre de rencontres prises en compte pour le recalcul : " . count($games) . "\n";
        
        foreach($games as $game) {
            echo "Prise en compte de la rencontre $game \n";
            foreach ($game->getPredictions() as $prediction) {
                echo "  - Traitement de la prédiction " .  $prediction . "\n";
                $points = $this->computePointsForPrediction($game, $prediction);
                echo "    - Points gagnés : " . $points . "\n";
                $userLb = $leaderboard[$prediction->getUser()->getId()];
                $userLb->addPoints($points);
                $prediction->setPoints($points);
                
            }
        }
        
        $this->manager->flush();
    }
    
    /**
     * Calcul les points gagné pour une prédiction de rencontre
     * 
     * @param \WCPC2K18Bundle\Component\Game $game
     * @param \WCPC2K18Bundle\Component\Prediction $prediction
     */
    private function computePointsForPrediction(Game $game, Prediction $prediction) {
        $result = $game->getResult();
        $points = 0;
        
        if ($this->userHasPerfectPrediction($game, $prediction)) {
            $points = $game->getRule()->getPointsForPerfect();
        }
        elseif ($this->userHasGA($game, $prediction)) {
            $points = $game->getRule()->getPointsForCorrectGA();
        }
        elseif ($this->userHasGameResult($result, $prediction)) {
            $points = $game->getRule()->getPointsForCorrectWinner();
        }
        
        //Application du coefficient multiplicateur si l'utilisateur a joué
        // un jackpot et que l'application de ce jackpot est possible
        if ($game->getRule()->isJackpotEnabled() && $prediction->getJackpot()) {
            $points *= $game->getRule()->getJackpotMultiplicator();
        }
        
        return $points;
    }
    
    /**
     * Indique si l'utilisateur a trouvé le résultat de la rencontre
     * 
     * @param integer $gameResult Un entier représentant le résultat de l'équipe
     * @param Prediction $prediction Le pronostic
     * @return boolean VRAI si le pronostic a le bon vainqueur
     */
    private function userHasGameResult($gameResult, Prediction $prediction) {
        
        return $gameResult == $prediction->getResult();
    }
    
    /**
     * Indique si l'utilisateur a le bon goal average pour la rencontre
     * @param Game $game La rencontre considérée
     * @param Prediction $prediction Le pronostic de l'utilisateur
     * 
     * @return boolean VRAI si le goal average du pronostic est bon, 
     * FAUX sinon
     */
    private function userHasGA(Game $game, Prediction $prediction) {
        return $game->getGA() == $prediction->getGA();
    }
    
    /**
     * Indique si le pronostic de l'utilisateur est parfaitement exact
     * @param Game $game La rencontre considérée
     * @param Prediction $prediction Le pronostic de l'utilisateur
     * @return boolean VRAI si l'utilisateur a pronostiqué le score exact.
     */
    private function userHasPerfectPrediction(Game $game, Prediction $prediction) {
        return ($game->getGoalsHome() == $prediction->getGoalsHome() && 
                $game->getGoalsAway() == $prediction->getGoalsAway());
    }

}
