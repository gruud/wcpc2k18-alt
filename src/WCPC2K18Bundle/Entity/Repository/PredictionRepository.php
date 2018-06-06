<?php

namespace WCPC2K18Bundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use WCPC2K18Bundle\Entity\User;
use WCPC2K18Bundle\Entity\Game;

/**
 * La classe PredictionRepository implémente un dépôt Doctrine personnalisé pour
 * la classe Prediction, et comporte des méthodes utilitaires spécifiques de 
 * récupération de pronostics en base de données. 
 *
 * @author Sébastien ZINS
 */
class PredictionRepository extends EntityRepository{
    
    /**
     * Récupère le pronostic réalisé par l'utilisateur $user pour la rencontre
     * $game. 
     * 
     * @param User $user L'utilisateur dont on veut récupérer le pronostic.
     * @param Game $game La partie pour laquelle on souhaite récupérer le 
     * pronostic. 
     * 
     * @return Prediction | null Renvoie la prédiction si elle existe, et null
     * sinon.
     */
    public function findUserPredictionForGame(User $user, Game $game) {
        
        $qb = $this->createQueryBuilder('p');
        $qb->join('p.user', 'u')->join('p.game', 'g');
        $qb->where('u.id=:uid')->andWhere('g.id=:gid');
        
        $qb->setParameter('uid', $user->getId())
                ->setParameter('gid', $game->getId());
        
        return $qb->getQuery()->getOneOrNullResult();
    }
    
    /**
     * Récupère les rencontres d'une journée $day donnée pour lesquelles l'utilisateur
     * a doublé la mise. Renvoie une prédiction si le jackpot a déjà été utilisé,
     * et null sinon. Utilisé par le service de vérification de prédiction pour 
     * déterminer si le joueur a le droit de jouer double. 
     * 
     * @param User $user L'utilisateur considéré
     * @param string $day Le nom de la journée pour laquelle on cherche les mises doubles
     * de l'utilisateur
     * @return Prediction | null La prédiction pour laquelle l'utilisateur a joué
     * double, ou null sinon. 
     */
    public function findUserJackpotForDay(User $user, $day) {
        $qb = $this->createQueryBuilder('p');
        $qb->join('p.game', 'g')->addSelect('g');
        $qb->where('g.phase=:phase')->setParameter('phase', $day)
                ->andWhere($qb->expr()->eq('p.jackpot', ':jackpot'))
                ->setParameter('jackpot', true);
        
        return $qb->getQuery()->getOneOrNullResult();
    }
}
