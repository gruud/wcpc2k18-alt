<?php

namespace WCPC2K18Bundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * La classe LeaderboardRepository étend le dépôt standard Doctrine pour le
 * requêtage du classement.
 *
 * @author Sébastien Zins
 */
class LeaderboardRepository extends EntityRepository {
    
    
    /**
     * Renvoie le classement général, agrémenté des informations des utilisateurs,
     * et classé par rang de classement croissant, pour afficher le classement 
     * général de la compétition.
     */
    public function getFullLeaderboardOrderedForGeneral(){
        
        $qb = $this->createQueryBuilder('l');
        $qb->join('l.user', 'u')->addSelect('u');
        $qb->orderBy('l.points', "DESC")
                ->addOrderBy('u.firstName', "ASC")
                ->addOrderBy('u.lastName', "ASC");
        
        return $qb->getQuery()->getResult();
    }
}
