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
     * Nombre de points remportés si le résultat est correct ( 1 N 2)
     */
    const POINTS_STANDARD_WINNER    = 3;
    
    /**
     * Nombre de points remportés si le résultat et le goal average sont corrects
     */
    const POINTS_STANDARD_GA        = 4;
    
    /**
     * Nombre de points remportés si le pronostic est identique au résultat
     */
    const POINTS_STANDARD_PERFECT   = 5;
    
    /**
     * Coefficient multiplicateur en cas de Jackpot
     */
    const POINTS_JACKPOT_MULTIPLICATOR = 2;
    
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
        $rules = LeaderboardRule::createRules();
        
        
        $games = $this->manager->getRepository('WCPC2K18Bundle:Game')
                ->findForLeaderboardCalculation();
        
        
        foreach($games as $game) {
            echo "Prise en compte de la rencontre $game \n";
            foreach ($game->getPredictions() as $prediction) {
                echo "  - Traitement de la prédiction " .  $prediction . "\n";
                $points = $this->computePointsForPrediction($game, $prediction);
               
            }
        }
        
        
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
        if ($this->hasResult($result, $prediction)) {
            
        }
    }
    
    /**
     * Indique si l'utilisateur a trouvé le résultat de la rencontre
     */
    private function hasResult($gameResult, Prediction $prediction) {
        return $gameResult == $prediction->getResult();
    }
    
    
   
}
