<?php

namespace WCPC2K18Bundle\Entity\Repository;


use Doctrine\ORM\EntityRepository;
/**
 * La classe UserRepository implémente un dépôt personnalisé Doctrine pour
 * l'entité User
 *
 * @author Sébastien ZINS
 */
class UserRepository extends EntityRepository {
    
    
    public function findWithPredictionsOrderedByGameId($userId) {
        $qb = $this->createQueryBuilder('u');
        $qb->leftJoin('u.predictions', 'p')->addSelect('p')
                ->leftJoin('p.game', 'g')->addSelect('g')
                ->where($qb->expr()->eq('u.id', $userId))
                ->orderBy('g.id', 'ASC');
        
        return $qb->getQuery()->getOneOrNullResult();
    }
}
