<?php


namespace WCPC2K18Bundle\Component;


use Doctrine\ORM\EntityManager;

/**
 * La classe LeaderBoardManager implémente le service de gestion des classements.
 * Il est responsable du calcul et de la mise à jour des classements à partir
 * des résultats entrés
 *
 * @author Sébastien ZINS
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
        
    }
}
